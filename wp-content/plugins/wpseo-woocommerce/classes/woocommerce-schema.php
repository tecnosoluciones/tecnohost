<?php
/**
 * WooCommerce Yoast SEO plugin file.
 *
 * @package WPSEO/WooCommerce
 */

use Yoast\WP\SEO\Config\Schema_IDs;

/**
 * Class WPSEO_WooCommerce_Schema.
 */
class WPSEO_WooCommerce_Schema {

	/**
	 * The schema data we're going to output.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * WooCommerce version number.
	 *
	 * @var string
	 */
	protected $wc_version;

	/**
	 * WPSEO_WooCommerce_Schema constructor.
	 *
	 * @param string $wc_version The WooCommerce version.
	 */
	public function __construct( $wc_version = WC_VERSION ) {
		$this->wc_version = $wc_version;

		// Filters & actions below in order of execution.
		add_filter( 'wpseo_frontend_presenters', [ $this, 'remove_unneeded_presenters' ] );
		add_filter( 'wpseo_schema_webpage', [ $this, 'filter_webpage' ], 10, 2 );
		add_filter( 'woocommerce_structured_data_product', [ $this, 'change_product' ], 10, 2 );
		add_filter( 'woocommerce_structured_data_type_for_page', [ $this, 'remove_woo_breadcrumbs' ] );

		// Only needed for WooCommerce versions before 3.8.1.
		if ( version_compare( $this->wc_version, '3.8.1' ) < 0 ) {
			add_filter( 'woocommerce_structured_data_review', [ $this, 'change_reviewed_entity' ] );
		}

		add_action( 'wp_footer', [ $this, 'output_schema_footer' ] );
	}

	/**
	 * If this is a product page, remove some of the presenters so we don't output them.
	 *
	 * @param array $presenters Array of presenters.
	 *
	 * @return array Array of presenters.
	 */
	public function remove_unneeded_presenters( $presenters ) {
		if ( is_product() ) {
			foreach ( $presenters as $key => $object ) {
				if (
					is_a( $object, 'Yoast\WP\SEO\Presenters\Open_Graph\Article_Publisher_Presenter' )
					|| is_a( $object, 'Yoast\WP\SEO\Presenters\Open_Graph\Article_Author_Presenter' )
				) {
					unset( $presenters[ $key ] );
				}
			}
		}

		return $presenters;
	}

	/**
	 * Should the yoast schema output be used.
	 *
	 * @return bool Whether the Yoast SEO schema should be output.
	 */
	public static function should_output_yoast_schema() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- Using WPSEO hook.
		return apply_filters( 'wpseo_json_ld_output', true );
	}

	/**
	 * Outputs the Woo Schema blob in the footer.
	 *
	 * @return bool False when there's nothing to output, true when we did output something.
	 */
	public function output_schema_footer() {
		if ( empty( $this->data ) || $this->data === [] || ! is_array( $this->data ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to output HTML. If we escape this we break it.
		echo new WPSEO_WooCommerce_Schema_Presenter(
			[ $this->data ],
			[
				'yoast-schema-graph',
				'yoast-schema-graph--woo',
				'yoast-schema-graph--footer',
			]
		);

		return true;
	}

	/**
	 * Changes the WebPage output to point to Product as the main entity.
	 *
	 * @param array $webpage_data Product Schema data.
	 *
	 * @return array Product Schema data.
	 */
	public function filter_webpage( $webpage_data ) {
		if ( is_product() ) {
			if ( ! is_array( $webpage_data['@type'] ) ) {
				$webpage_data['@type'] = [ $webpage_data['@type'] ];
			}
			$webpage_data['@type'][] = 'ItemPage';
			// We normally add a `ReadAction` on pages, we're replacing with a `BuyAction` on product pages.
			$webpage_data['potentialAction'] = [
				'@type'  => 'BuyAction',
				'target' => YoastSEO()->meta->for_current_page()->canonical,
			];
			unset( $webpage_data['datePublished'], $webpage_data['dateModified'] );
		}
		if ( is_checkout() || is_checkout_pay_page() ) {
			$webpage_data['@type'] = 'CheckoutPage';
			// We normally add a `ReadAction` on pages, adding that on a checkout makes no sense.
			unset( $webpage_data['potentialAction'] );
		}

		return $webpage_data;
	}

	/**
	 * Changes the Review output to point to Product as the reviewed Item.
	 *
	 * @param array $data Review Schema data.
	 *
	 * @return array Review Schema data.
	 */
	public function change_reviewed_entity( $data ) {
		unset( $data['@type'] );
		unset( $data['itemReviewed'] );

		$this->data['review'][] = $data;

		/**
		 * Filter: 'wpseo_schema_review' - Allow changing the Review type.
		 *
		 * @api array $data The Schema Review data.
		 */
		$this->data = apply_filters( 'wpseo_schema_review', $this->data );

		return [];
	}

	/**
	 * Filter Schema Product data to work.
	 *
	 * @param array      $data    Schema Product data.
	 * @param WC_Product $product Product object.
	 *
	 * @return array Schema Product data.
	 */
	public function change_product( $data, $product ) {
		$data = $this->change_seller_in_offers( $data );
		$data = $this->filter_reviews( $data, $product );
		$data = $this->filter_offers( $data, $product );
		$data = $this->filter_sku( $data, $product );

		// This product is the main entity of this page, so we set it as such.
		$data['mainEntityOfPage'] = [
			'@id' => YoastSEO()->meta->for_current_page()->main_schema_id,
		];

		// Now let's add this data to our overall output.
		$this->data = $data;

		$this->add_image();
		$this->add_brand( $product );
		$this->add_manufacturer( $product );
		$this->add_color( $product );
		$this->add_pattern( $product );
		$this->add_material( $product );
		$this->add_global_identifier( $product );

		/**
		 * Filter: 'wpseo_schema_product' - Allow changing the Product type.
		 *
		 * @api array $data The Schema Product data.
		 */
		$this->data = apply_filters( 'wpseo_schema_product', $this->data );

		return [];
	}

	/**
	 * Filters the offers array to enrich it.
	 *
	 * @param array      $data    Schema Product data.
	 * @param WC_Product $product The product.
	 *
	 * @return array Schema Product data.
	 */
	protected function filter_offers( $data, $product ) {
		if ( ! isset( $data['offers'] ) || $data['offers'] === [] ) {
			return $data;
		}

		$data['offers'] = $this->filter_sales( $data['offers'], $product );

		foreach ( $data['offers'] as $key => $offer ) {

			// Add an @id to the offer.
			if ( $offer['@type'] === 'Offer' ) {
				$price                         = WPSEO_WooCommerce_Utils::get_product_display_price( $product );
				$data['offers'][ $key ]['@id'] = YoastSEO()->meta->for_current_page()->site_url . '#/schema/offer/' . $product->get_id() . '-' . $key;

				$data['offers'][ $key ]['priceSpecification']['@type'] = 'PriceSpecification';
				$data['offers'][ $key ]['priceSpecification']['price'] = $price;
				if ( wc_tax_enabled() ) {
					$data['offers'][ $key ]['priceSpecification']['valueAddedTaxIncluded'] = WPSEO_WooCommerce_Utils::prices_have_tax_included();
				}
				else {
					unset( $data['offers'][ $key ]['priceSpecification']['valueAddedTaxIncluded'] );
				}

				$data['offers'][ $key ]['seller'] = [ '@id' => YoastSEO()->meta->for_current_page()->site_url . '#organization' ];

				// Remove price property from Schema output by WooCommerce.
				if ( isset( $data['offers'][ $key ]['price'] ) ) {
					unset( $data['offers'][ $key ]['price'] );
				}
				// Remove priceCurrency property from Schema output by WooCommerce.
				if ( isset( $data['offers'][ $key ]['priceCurrency'] ) ) {
					unset( $data['offers'][ $key ]['priceCurrency'] );
				}
			}
			if ( $offer['@type'] === 'AggregateOffer' ) {
				$data['offers'][ $key ]['@id']    = YoastSEO()->meta->for_current_page()->site_url . '#/schema/aggregate-offer/' . $product->get_id() . '-' . $key;
				$data['offers'][ $key ]['offers'] = $this->add_individual_offers( $product );
				if ( $product instanceof WC_Product_Variable ) {
					$decimals = wc_get_price_decimals();
					$lowest   = $product->get_variation_price( 'min', true );
					$highest  = $product->get_variation_price( 'max', true );

					$data['offers'][ $key ]['lowPrice']  = wc_format_decimal( $lowest, $decimals );
					$data['offers'][ $key ]['highPrice'] = wc_format_decimal( $highest, $decimals );
				}
			}

			// Alter availability when product is "on backorder".
			if ( $product->is_on_backorder() ) {
				$data['offers'][ $key ]['availability'] = 'https://schema.org/PreOrder';
			}
		}

		// We don't want an array with keys, we just need the offers.
		$data['offers'] = array_values( $data['offers'] );

		return $data;
	}

	/**
	 * Filters the offers array on sales, possibly unset them.
	 *
	 * @param array      $offers  Schema Offer data.
	 * @param WC_Product $product The product.
	 *
	 * @return array Schema Offer data.
	 */
	protected function filter_sales( $offers, $product ) {
		foreach ( $offers as $key => $offer ) {
			/*
			 * WooCommerce assumes all prices will be valid until the end of next year,
			 * unless on sale and there is an end date. We keep the `priceValidUntil`
			 * property only for products with a sale price and a sale end date.
			 */

			if ( ! $product->is_on_sale() || ! $product->get_date_on_sale_to() ) {
				unset( $offers[ $key ]['priceValidUntil'] );
			}
		}

		return $offers;
	}

	/**
	 * Removes the SKU when it's empty to prevent the WooCommerce fallback to the product's ID.
	 *
	 * @param array      $data    Schema Product data.
	 * @param WC_Product $product The product.
	 *
	 * @return array Schema Product data.
	 */
	protected function filter_sku( $data, $product ) {
		/*
		 * When the SKU of a product is left empty, WooCommerce makes it the value of the product's id.
		 * In this method we check for that and unset it if done so.
		 */
		if ( empty( $product->get_sku() ) ) {
			unset( $data['sku'] );
		}

		return $data;
	}

	/**
	 * Removes the Woo Breadcrumbs from their Schema output.
	 *
	 * @param array $types Types of Schema Woo will render.
	 *
	 * @return array Types of Schema Woo will render.
	 */
	public function remove_woo_breadcrumbs( $types ) {
		foreach ( $types as $key => $type ) {
			if ( $type === 'breadcrumblist' ) {
				unset( $types[ $key ] );
			}
		}

		return $types;
	}

	/**
	 * Retrieve the global identifier type and value if we have one.
	 *
	 * @param WC_Product $product Product object.
	 *
	 * @return bool True on success, false on failure.
	 */
	protected function add_global_identifier( $product ) {
		$product_id               = $product->get_id();
		$global_identifier_values = get_post_meta( $product_id, 'wpseo_global_identifier_values', true );

		if ( ! is_array( $global_identifier_values ) || $global_identifier_values === [] ) {
			return false;
		}

		foreach ( $global_identifier_values as $type => $value ) {
			if ( empty( $value ) ) {
				continue;
			}
			$this->data[ $type ] = $value;
			if ( $type === 'isbn' ) {
				$this->data['@type'] = [ 'Book', 'Product' ];
			}
		}

		return true;
	}

	/**
	 * Update the seller attribute to reference the Organization, when it is set.
	 *
	 * @param array $data Schema Product data.
	 *
	 * @return array Schema Product data.
	 */
	protected function change_seller_in_offers( $data ) {
		$company_or_person = WPSEO_Options::get( 'company_or_person', false );
		$company_name      = WPSEO_Options::get( 'company_name' );

		if ( $company_or_person !== 'company' || empty( $company_name ) ) {
			return $data;
		}

		if ( ! empty( $data['offers'] ) ) {
			foreach ( $data['offers'] as $key => $offer ) {
				$data['offers'][ $key ]['seller'] = [
					'@id' => trailingslashit( YoastSEO()->meta->for_current_page()->site_url ) . Schema_IDs::ORGANIZATION_HASH,
				];
			}
		}

		return $data;
	}

	/**
	 * Add brand to our output.
	 *
	 * @param WC_Product $product Product object.
	 */
	private function add_brand( $product ) {
		$schema_brand = WPSEO_Options::get( 'woo_schema_brand' );
		if ( ! empty( $schema_brand ) ) {
			$this->add_attribute_as( 'brand', $product, $schema_brand, 'Brand' );
		}
	}

	/**
	 * Add manufacturer to our output.
	 *
	 * @param WC_Product $product Product object.
	 */
	private function add_manufacturer( $product ) {
		$schema_manufacturer = WPSEO_Options::get( 'woo_schema_manufacturer' );
		if ( ! empty( $schema_manufacturer ) ) {
			$this->add_attribute_as( 'manufacturer', $product, $schema_manufacturer );
		}
	}

	/**
	 * Adds an attribute to our Product data array with the value from a taxonomy, as an Organization,
	 *
	 * @param string     $attribute The attribute we're adding to Product.
	 * @param WC_Product $product   The WooCommerce product we're working with.
	 * @param string     $taxonomy  The taxonomy to get the attribute's value from.
	 * @param string     $type      The Schema type to use.
	 */
	private function add_attribute_as( $attribute, $product, $taxonomy, $type = 'Organization' ) {
		$term = $this->get_primary_term_or_first_term( $taxonomy, $product->get_id() );

		if ( $term !== null ) {
			$this->data[ $attribute ] = [
				'@type' => $type,
				'name'  => \wp_strip_all_tags( $term->name ),
			];
		}
	}

	/**
	 * Adds image schema.
	 */
	private function add_image() {
		/**
		 * WooCommerce will set the image to false if none is available. This is incorrect schema and we should fix it
		 * for our users for now.
		 *
		 * See https://github.com/woocommerce/woocommerce/issues/24188.
		 */
		if ( isset( $this->data['image'] ) && $this->data['image'] === false ) {
			unset( $this->data['image'] );
		}

		if ( has_post_thumbnail() ) {
			$this->data['image'] = [
				'@id' => YoastSEO()->meta->for_current_page()->canonical . Schema_IDs::PRIMARY_IMAGE_HASH,
			];

			return;
		}

		// Fallback to WooCommerce placeholder image.
		if ( function_exists( 'wc_placeholder_img_src' ) ) {
			$image_schema_id     = YoastSEO()->meta->for_current_page()->canonical . '#woocommerceimageplaceholder';
			$placeholder_img_src = wc_placeholder_img_src();
			$this->data['image'] = YoastSEO()->helpers->schema->image->generate_from_url( $image_schema_id, $placeholder_img_src, '', false, false );
		}
	}

	/**
	 * Add a custom schema property to the Schema output.
	 *
	 * @param WC_Product $product The product object.
	 * @param string     $option_name The option name.
	 * @param string     $schema_id The schema identifier to use.
	 *
	 * @return void
	 */
	private function add_custom_schema_property( $product, $option_name, $schema_id ) {
		$schema_data = WPSEO_Options::get( $option_name );

		if ( ! empty( $schema_data ) ) {
			$terms = get_the_terms( $product->get_id(), $schema_data );

			if ( is_array( $terms ) ) {
				// Variable products can have more than one color.
				$is_variable_product = false;
				if ( isset( $this->data['offers'] ) ) {
					foreach ( $this->data['offers'] as $offer ) {
						if ( $offer['@type'] === 'AggregateOffer' ) {
							$is_variable_product = true;
						}
					}
				}

				if ( count( $terms ) === 1 ) {
					$term                     = reset( $terms );
					$this->data[ $schema_id ] = strtolower( $term->name );
				}
				elseif ( $is_variable_product ) {
					$schema_data_content = [];
					foreach ( $terms as $term ) {
						$schema_data_content[] = strtolower( $term->name );
					}

					$this->data[ $schema_id ] = $schema_data_content;
				}
			}
		}
	}

	/**
	 * Adds the product color property to the Schema output.
	 *
	 * @param WC_Product $product The product object.
	 *
	 * @return void
	 */
	private function add_color( $product ) {
		$this->add_custom_schema_property( $product, 'woo_schema_color', 'color' );
	}

	/**
	 * Adds the product pattern property to the Schema output.
	 *
	 * @param WC_Product $product The product object.
	 *
	 * @return void
	 */
	private function add_pattern( $product ) {
		$this->add_custom_schema_property( $product, 'woo_schema_pattern', 'pattern' );
	}

	/**
	 * Adds the product material property to the Schema output.
	 *
	 * @param WC_Product $product The product object.
	 *
	 * @return void
	 */
	private function add_material( $product ) {
		$this->add_custom_schema_property( $product, 'woo_schema_material', 'material' );
	}

	/**
	 * Tries to get the primary term, then the first term, null if none found.
	 *
	 * @param string $taxonomy_name Taxonomy name for the term.
	 * @param int    $post_id       Post ID for the term.
	 *
	 * @return WP_Term|null The primary term, the first term or null.
	 */
	protected function get_primary_term_or_first_term( $taxonomy_name, $post_id ) {
		$primary_term    = new WPSEO_Primary_Term( $taxonomy_name, $post_id );
		$primary_term_id = $primary_term->get_primary_term();

		if ( $primary_term_id !== false ) {
			$primary_term = get_term( $primary_term_id );
			if ( $primary_term instanceof WP_Term ) {
				return $primary_term;
			}
		}

		$terms = get_the_terms( $post_id, $taxonomy_name );

		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			return $terms[0];
		}

		return null;
	}

	/**
	 * Adds the individual product variants as variants of the offer.
	 *
	 * @param WC_Product $product The WooCommerce product we're working with.
	 *
	 * @return array Schema Offers data.
	 */
	protected function add_individual_offers( $product ) {
		$variations = $product->get_available_variations();

		$currency           = get_woocommerce_currency();
		$tax_enabled        = wc_tax_enabled();
		$prices_include_tax = WPSEO_WooCommerce_Utils::prices_have_tax_included();
		$decimals           = wc_get_price_decimals();
		$data               = [];
		$product_id         = $product->get_id();
		$product_name       = $product->get_name();
		$product_global_ids = get_post_meta( $product_id, 'wpseo_global_identifier_values', true );

		foreach ( $variations as $key => $variation ) {
			$variation_name = implode( ' / ', $variation['attributes'] );

			$offer = [
				'@type'              => 'Offer',
				'@id'                => YoastSEO()->meta->for_current_page()->site_url . '#/schema/offer/' . $product_id . '-' . $key,
				'name'               => $product_name . ' - ' . $variation_name,
				'url'                => get_permalink( $variation['variation_id'] ),
				'priceSpecification' => [
					'@type'                 => 'PriceSpecification',
					'price'                 => wc_format_decimal( $variation['display_price'], $decimals ),
					'priceCurrency'         => $currency,
				],
			];

			if ( ! empty( $variation['sku'] ) ) {
				$offer['sku'] = $variation['sku'];
			}

			if ( $tax_enabled ) {
				$offer['priceSpecification']['valueAddedTaxIncluded'] = $prices_include_tax;
			}

			// Adds variation's global identifiers to the $offer array.
			$variation_global_ids    = get_post_meta( $variation['variation_id'], 'wpseo_variation_global_identifiers_values', true );
			$global_identifier_types = [
				'gtin8',
				'gtin12',
				'gtin13',
				'gtin14',
				'mpn',
			];

			foreach ( $global_identifier_types as $global_identifier_type ) {
				if ( isset( $variation_global_ids[ $global_identifier_type ] ) && ! empty( $variation_global_ids[ $global_identifier_type ] ) ) {
					$offer[ $global_identifier_type ] = $variation_global_ids[ $global_identifier_type ];
				}
				elseif ( isset( $product_global_ids[ $global_identifier_type ] ) && ! empty( $product_global_ids[ $global_identifier_type ] ) ) {
					$offer[ $global_identifier_type ] = $product_global_ids[ $global_identifier_type ];
				}
			}

			/**
			 * Filter: 'wpseo_schema_offer' - Allow changing the offer schema.
			 *
			 * @param array                $offer     The schema offer data.
			 * @param WC_Product_Variation $variation The WooCommerce product variation we're working with.
			 * @param WC_Product           $product   The WooCommerce product we're working with.
			 */
			$data[] = apply_filters( 'wpseo_schema_offer', $offer, $variation, $product );
		}

		return $data;
	}

	/**
	 * Enhances the review data output by WooCommerce.
	 *
	 * @param array      $data    Review Schema data.
	 * @param WC_Product $product The WooCommerce product we're working with.
	 *
	 * @return array Review Schema data.
	 */
	protected function filter_reviews( $data, $product ) {
		if ( ! isset( $data['review'] ) || $data['review'] === [] ) {
			return $data;
		}

		$product_id   = $product->get_id();
		$product_name = $product->get_name();

		foreach ( $data['review'] as $key => $review ) {
			$data['review'][ $key ]['@id']  = YoastSEO()->meta->for_current_page()->site_url . '#/schema/review/' . $product_id . '-' . $key;
			$data['review'][ $key ]['name'] = $product_name;
		}

		return $data;
	}
}
