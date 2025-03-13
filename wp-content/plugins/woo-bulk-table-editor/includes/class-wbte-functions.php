<?php
/**
 * Bulk Table Editor functions
 *
 * @package BulkTableEditor/includes
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/class-wbte-templates.php';

/**
 * Class for functions
 */
class WbteFunctions {

	/**
	 * Products and ids
	 */
	public $json_products;

	/**
	 * WbteOptions
	 *
	 * @var var $wbte_options.
	 */
	public $wbte_options;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->json_products = array( 
			'rows'      => array(), 
			'ids'       => array(),
			'total'     => 0,
			'num-pages' => 0,
		);

		$this->wbte_options = get_option( 'wbte_options' );
	}

	/**
	 * Update product
	 *
	 * @param var $post object.
	 */
	public function wbte_update_product( $post ) {

		if ( ! isset( $post['id'] ) ) {
			die();
		}

		$custom_price_info = $this->wbte_get_custom_price_info();
		$options           = get_option( 'wbte_options' );
		$show_sku          = ( strlen( $options[ 'wbte_use_sku_main_page' ] ) > 0 ) ? $options[ 'wbte_use_sku_main_page' ] : 'no';
		$show_vendors      = ( strlen( $options[ 'wbte_vendor_integration' ] ) > 0 ) ? $options[ 'wbte_vendor_integration' ] : 'no';
		$disable_desc      = ( strlen( $options[ 'wbte_disable_description' ] ) > 0 ) ? $options[ 'wbte_disable_description' ] : 'no';
		$id                = sanitize_text_field( $post['id'] );
		$price             = sanitize_text_field( $post['price'] );
		$sale_price        = sanitize_text_field( $post['saleprice'] );
		$stock             = sanitize_text_field( $post['stock'] );
		$sale_from         = ( ! empty( $post['salefrom'] ) ) ? $this->wbte_date_to_time( $post['salefrom'] ) : '';
		$sale_to           = ( ! empty( $post['saleto'] ) ) ? $this->wbte_date_to_time( $post['saleto'] ) : '';
		$sale_time_from    = ( ! empty( $post['salefrom_time'] ) ) ? $post['salefrom_time'] : '00:00';
		$sale_time_to      = ( ! empty( $post['saleto_time'] ) ) ? $post['saleto_time'] : '23:59';
		$name              = sanitize_text_field( $post['name'] );
		$update_name       = sanitize_text_field( $post['name_update'] );
		$description       = isset( $post['description'] ) ? wc_clean( $post['description'] ) : '';
		$custom_price      = '';
		$product           = wc_get_product( $id );
		$prev_sale_price   = '_wbte_prev_sale_price';
		$meta_time_from    = '_wbte_sale_time_from';
		$meta_time_to      = '_wbte_sale_time_to';
		$meta_subscription = '_subscription_price';
		$prev_price        = '_wbte_prev_price';

		//Update custom price
		if ( 'yes' === $custom_price_info['active'] && 'no' === $show_sku ) {

			$custom_price = sanitize_text_field( $post['customprice'] );
			$product->update_meta_data( $custom_price_info['price'], $custom_price );

		} elseif ( 'yes' === $show_sku ) {

			$sku = sanitize_text_field( $post['sku'] );
			if ( strlen( $sku ) > 0 ) {
				$is_unique = wc_product_has_unique_sku( $id, $sku );
				if ( $is_unique ) {
					$product->set_sku( $sku );
				}
			} else {
				$product->set_sku( '' );
			}

		}

		//Update vendor
		if ( 'yes' === $show_vendors ) {
			$vendor_id = sanitize_text_field( $post['vendor'] );
			$_id       = $product->get_id();

			if ( $product->get_parent_id() > 0 ) {
				$_id = $product->get_parent_id();
			}

			wp_set_object_terms( $_id, absint( $vendor_id ), WC_PRODUCT_VENDORS_TAXONOMY );
		}

		if ( 'subscription' === $product->get_type() ) {
			$product->update_meta_data( $meta_subscription, $price );
		} else {
			$product->set_price( $price );
		}

		$product->update_meta_data( $prev_price, $product->get_regular_price() );
		$product->set_regular_price( $price );
		$product->set_stock_quantity( $stock );

		//Product name
		if ( 'true' === $update_name && strlen( $name ) > 0 ) {
			$product->set_name( $name );
		}

		//Description
		if ( 'yes' !== $disable_desc ) {
			$description = ( $description ) ? wp_filter_post_kses( $post['description'] ) : '';
			$product->set_description( $description );
		}
		
		$stock_status = ( strlen( $product->get_stock_status() ) > 0 ) ? $product->get_stock_status() : 'instock';

		//Stock
		if ( strlen( $stock ) > 0 ) {
			$product->set_manage_stock( 'true' );
			$product->set_stock_status( $stock_status );
		} else {
			if ( 'yes' === $options['wbte_no_stock_management'] ) {
				$product->set_manage_stock( 'false' );
				$product->set_stock_status( $stock_status );
			}
		}

		//Sale
		if ( strlen( $sale_price ) > 0 ) { 
			
			//Normal
			$product->set_sale_price( $sale_price );
			$product->update_meta_data( $prev_sale_price, $sale_price );
			$product->update_meta_data( $meta_time_from, $sale_time_from );
			$product->update_meta_data( $meta_time_to, $sale_time_to );
			
			if ( '' !== $sale_from && 0 !== $sale_from ) {
				$sale_from = gmdate( 'Y-m-d ' . $sale_time_from, $sale_from );
			}
			if ( '' !== $sale_to && 0 !== $sale_to ) {
				$sale_to = gmdate( 'Y-m-d ' . $sale_time_to, $sale_to );
			}

			$product->set_date_on_sale_from( $sale_from );
			$product->set_date_on_sale_to( $sale_to );

		} elseif ( '' === $sale_price && '' === $sale_from && '' === $sale_to ) {
			// Clear sales
			$product->set_sale_price( '' );
			$product->set_date_on_sale_from( '' );
			$product->set_date_on_sale_to( '' );
			$product->update_meta_data( $meta_time_from, '' );
			$product->update_meta_data( $meta_time_to, '' );
		} 

		$product->save();

	}

	/**
	 * Convert a date string into a timestamp
	 *
	 * @param var @date_string date.
	 */
	public function wbte_date_to_time( $date_string ) {

		if ( 0 === $date_string ) {
			return 0;
		}

		$date_format = get_option( 'date_format' );
		
		//Set correct date
		if ( strpos( $date_string, '-' ) === false ) {
			
			switch ( $date_format ) {
				case 'm/d/Y':
					$arr         = explode( '/', $date_string );
					$date_string = $arr[2] . '-' . $arr[0] . '-' . $arr[1];
					break;
				case 'd/m/Y':
					$arr         = explode( '/', $date_string );
					$date_string = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
					break;
				default:
					$ndt         = DateTime::createFromFormat( $date_format, $date_string );
					$date_string = $ndt->format( 'Y-m-d' );
					break;
			}

		}

		$date_time = new WC_DateTime( $date_string, new DateTimeZone( 'UTC' ) );
	
		return intval( $date_time->getTimestamp() );
	}

	/**
	 * Update product ext
	 *
	 * @param var $post object.
	 */
	public function wbte_update_product_ext( $post ) {

		if ( ! isset( $post['id'] ) ) {
			die();
		}

		$options      = get_option( 'wbte_options' );
		$id           = sanitize_text_field( $post['id'] );
		$featured     = sanitize_text_field( $post['featured'] );
		$sku          = sanitize_text_field( $post['sku'] );
		$backorder    = sanitize_text_field( $post['backorder'] );
		$instock      = sanitize_text_field( $post['instock'] );
		$weight       = sanitize_text_field( $post['weight'] );
		$length       = sanitize_text_field( $post['length'] );
		$width        = sanitize_text_field( $post['width'] );
		$height       = sanitize_text_field( $post['height'] );
		$terms        = sanitize_text_field( $post['tags'] );
		$name         = sanitize_text_field( $post['name'] );
		$update_name  = sanitize_text_field( $post['name_update'] );
		$image        = sanitize_text_field( $post['product_img'] );
		$parent_id    = sanitize_text_field( $post['parent_id'] );
		$visibility   = sanitize_text_field( $post['visibility'] );
		$description  = isset( $post['description'] ) ? wc_clean( $post['description'] ) : '';
		$product      = wc_get_product( $id );
		$stock        = ( null !== $product->get_stock_quantity() ) ? $product->get_stock_quantity() : 0;
		$disable_desc = ( strlen( $options[ 'wbte_disable_description' ] ) > 0 ) ? $options[ 'wbte_disable_description' ] : 'no';

		//Set values
		$product->set_featured( $featured );
	
		if ( strlen( $sku ) > 0 ) {
			$is_unique = wc_product_has_unique_sku( $id, $sku );
			if ( $is_unique ) {
				$product->set_sku( $sku );
			}
		} else {
			$product->set_sku( '' );
		}

		//Stock
		if ( $stock > 0 ) {
			$product->set_manage_stock( 'true' );
			$product->set_stock_status( $instock );
		} else {
			if ( 'yes' === $options['wbte_no_stock_management'] ) {
				$product->set_manage_stock( 'false' );
				$product->set_stock_status( $instock );
			} else {
				$product->set_manage_stock( 'true' );
				$product->set_stock_status( $instock );
			}
		}

		$product->set_backorders( $backorder );
		$product->set_catalog_visibility( $visibility );
		$product->set_weight( $weight );
		$product->set_length( $length );
		$product->set_width( $width );
		$product->set_height( $height );
		$product->set_image_id( $image );

		//Set terms
		$term_arr  = array();
		$term_list = explode( ',', $terms );
		
		foreach ( $term_list as $obj ) {
			array_push( $term_arr, rtrim( $obj, ' ' ) );
		}

		wp_set_object_terms( $id, $term_arr, 'product_tag', false );
		
		//If variable - set tags on parent product
		if ( strlen( $parent_id ) > 0 ) {
			wp_set_object_terms( $parent_id, $term_arr, 'product_tag', false );
		}
		
		//Product name
		if ( 'true' === $update_name && strlen( $name ) > 0 ) {
			$product->set_name( $name );
		}

		//Description
		if ( 'yes' !== $disable_desc ) {
			$description = ( $description ) ? wp_filter_post_kses( $post['description'] ) : '';
			$product->set_description( $description );
		}

		$product->save();

	}

	/**
	 * Move product to trash
	 *
	 * @param var $post product.
	 */
	public function wbte_move_to_trash( $post ) {
		
		if ( ! isset( $post['id'] ) ) {
			die();
		}

		$id      = sanitize_text_field( $post['id'] );
		$product = wc_get_product( $id );

		$product->set_status( 'trash' );
		$product->save();

	}

	/**
	 * Get category slug
	 *
	 * @param var $id int.
	 */
	public function wbte_get_category_slug( $id ) {
		$term = get_term_by( 'id', $id, 'product_cat', 'ARRAY_A' );
		$term = ( isset( $term['slug'] ) ) ? $term['slug'] : null;
		return $term;
	}

	/**
	 * Get products
	 */
	public function wbte_get_woo_products() {

		wp_reset_postdata();

		$wbte_options  = get_option( 'wbte_options' );
		$post_per_page = ( $wbte_options['wbte_posts_per_page'] > 0 ) ? $wbte_options['wbte_posts_per_page'] : 50;
		$prod_cat      = ( strlen( filter_input( 1, 'product_cat', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'product_cat', FILTER_DEFAULT ) : $wbte_options['wbte_product_cat'];
		$is_on_sale    = ( strlen( filter_input( 1, 'sales_filter', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'sales_filter', FILTER_DEFAULT ) : '';
		$is_download   = ( strlen( filter_input( 1, 'csv', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'csv', FILTER_DEFAULT ) : '';
		$prod_search   = filter_input( 1, 'product_search', FILTER_DEFAULT );
		$prod_order    = filter_input( 1, 'order', FILTER_DEFAULT );
		$page_num      = ( strlen( filter_input( 1, 'paged', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'paged', FILTER_DEFAULT ) : 1;
		$stype         = filter_input( 1, 'stype', FILTER_DEFAULT );

		if ( strlen( $prod_search ) > 0 ) {
			$prod_cat = '';
		}

		$args = array(
			'orderby' => 'title',
			'order'   => 'ASC',
		);
		
		$query = new WC_Product_Query( $args );

		//Product statuses
		if ( is_array( $wbte_options['wbte_product_status'] ) && count( $wbte_options['wbte_product_status'] ) > 0 ) {
			$query->set( 'status', $wbte_options['wbte_product_status'] );
		}

		if ( isset( $prod_cat ) ) {
			$query->set( 'category', $prod_cat );
		}

		if ( $prod_search ) {
			if ( 'sku' === $stype ) {
				$query->set( 'sku', $prod_search );
			} else {
				$query->set( 's', $prod_search );
			}
			$query->set( 'orderby', 'relevance' );
		}

		if ( $prod_order ) {
			$query->set( 'order', $prod_order );
		}
		
		if ( $post_per_page ) {
			$query->set( 'page', $page_num );
			$query->set( 'paginate', true );
			$query->set( 'limit', $post_per_page );
		}

		if ( 'yes' === $wbte_options['wbte_table_sale_filter'] && '' !== $is_on_sale || 'true' === $is_download ) {
			$query->set( 'limit', -1 );
		}

		return apply_filters( 'wbte_get_woo_products', $query );
	}

	/**
	 * Get product categories for settings - select box
	 *
	 * @param var $wbte_options object.
	 */
	public function wbte_settings_get_categories_select( $wbte_options ) {

		$args = array(
			'hide_empty'        => 1,
			'taxonomy'          => 'product_cat',
			'hierarchical'      => 1,
			'show_count'        => 1,
			'name'              => 'wbte_options[wbte_product_cat]',
			'orderby'           => 'product_cat',
			'order'             => 'asc',
			'show_option_none'  => __( 'All products', 'woo-bulk-table-editor' ),
			'option_none_value' => '',
			'selected'          => $wbte_options['wbte_product_cat'],
		);
		wp_dropdown_categories( $args );
		wp_reset_postdata();

	}

	/**
	 * Get product categories - select box
	 *
	 * @param var $prodcat object.
	 */
	public function wbte_get_categories_select( $prodcat ) {
		
		$view   = ( strlen( filter_input( 1, 'view', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'view', FILTER_DEFAULT ) : 'prod';
		$filter = ( strlen( filter_input( 1, 'sales_filter', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'sales_filter', FILTER_DEFAULT ) : '';
		
		$args = array(
			'pad_counts'         => 1,
			'show_count'         => 1,
			'hierarchical'       => 1,
			'hide_empty'         => 1,
			'show_uncategorized' => 1,
			'orderby'            => 'name',
			'selected'           => $prodcat,
			'show_option_none'   => __( 'Select Category ( All )', 'woo-bulk-table-editor' ),
			'option_none_value'  => '',
			'value_field'        => 'slug',
			'taxonomy'           => 'product_cat',
			'name'               => 'product_cat',
			'class'              => 'dropdown_product_cat',
		);
		?>
		<form name="gc" id="wbte-cat-select" type="get" action="edit.php" onchange="submit();">  
		<input type="hidden" name="post_type" value="product">  
		<input type="hidden" name="page" value="wbte-products">
		<input type="hidden" name="view" value="<?php echo esc_attr( $view ); ?>">
		<input type="hidden" name="sales_filter" value="<?php echo esc_attr( $filter ); ?>">
		<?php
			wp_dropdown_categories( apply_filters( 'wbte_category_selector_args', $args ) );
			wp_reset_postdata();
		?>
		</form>
		<?php
	}

	/**
	 * Get filter for sales products and variations
	 */
	public function wbte_get_sales_products_select() {

		$wbte_options = get_option( 'wbte_options' );
		$view         = ( strlen( filter_input( 1, 'view', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'view', FILTER_DEFAULT ) : 'prod';
		$filter       = ( strlen( filter_input( 1, 'sales_filter', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'sales_filter', FILTER_DEFAULT ) : '';
		$selected     = 'selected="selected"';
		$default      = '';
		$is_true      = '';
		$is_false     = '';

		switch ( $filter ) {
			case '':
				$default = $selected;
				break;
			case 'onSale':
				$is_true = $selected;
				break;
			case 'noSale':
				$is_false = $selected;
				break;
		}

		?>
		<?php if ( 'yes' !== $wbte_options['wbte_table_sale_filter'] ) : ?>
			<form name="wbte-filter-sales" id="wbte-filter-sales" onchange="wbteFilterSales();">
		<?php else : ?>
			<form name="wbte-filter-sales" id="wbte-filter-sales" action="edit.php" onchange="submit();">
		<?php endif; ?>
			<input type="hidden" name="post_type" value="product">  
			<input type="hidden" name="page" value="wbte-products">
			<input type="hidden" name="view" value="<?php echo esc_attr( $view ); ?>">  
			<select name="sales_filter" id="sales_filter">
				<option value="" <?php echo esc_attr( $default ); ?>><?php esc_attr_e( 'Filter Sales ( None )', 'woo-bulk-table-editor' ); ?></option>
				<option value="onSale" <?php echo esc_attr( $is_true ); ?>><?php esc_attr_e( 'Filter ( On Sale )', 'woo-bulk-table-editor' ); ?></option>
				<option value="noSale" <?php echo esc_attr( $is_false ); ?>><?php esc_attr_e( 'Filter ( Not On Sale )', 'woo-bulk-table-editor' ); ?></option>
			</select>
		</form>
		<?php
	}

	/**
	 * Get filter for tags
	 */
	public function wbte_get_tags_filter() {
		
		$filter = ( strlen( filter_input( 1, 'tags-filter', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'tags-filter', FILTER_DEFAULT ) : '';
		
		$args = array(
			'show_option_none'   => __( 'Filter Tags ( None )', 'woo-bulk-table-editor' ),
			'selected'           => $filter,
			'option_none_value'  => '',
			'hide_empty'         => 0,
			'orderby'            => 'name',
			'value_field'        => 'name',
			'taxonomy'           => 'product_tag',
			'name'               => 'product_tag',
			'class'              => 'dropdown_product_tag',
			//'wbte_multi_select'  => true,
		);
		?>
		<form name="tags-filter" id="tags-filter" onchange="wbteFilterTags();">  
		<?php
			wp_dropdown_categories( apply_filters( 'wbte_tags_selector_args', $args ) );
		?>
		</form>
		<?php
	}

	/**
	 * Get product tags
	 */
	public function wbte_get_product_tags( $product_id ) {

		$tags_arr = get_the_terms( $product_id, 'product_tag' );
		$tags     = '';
		
		if ( $tags_arr ) {
			foreach ( $tags_arr as $obj ) {
				$tags .= $obj->name . ', ';
			}
			$tags = substr( $tags, 0, strlen( $tags ) - 2 );
		}

		return $tags;
	}

	/**
	 * Get custom price info - array()
	 */
	public function wbte_get_custom_price_info() {

		$options   = get_option( 'wbte_options' );
		$price     = $options[ 'wbte_custom_price_1' ];
		$name      = $options[ 'wbte_custom_price_1_header' ];
		$is_active = $options[ 'wbte_custom_price_1_visible' ];
		$calc_type = $options[ 'wbte_custom_price_1_normal_calc' ];
		
		$price_arr = array(
			'price'       => $price,
			'name'        => $name,
			'active'      => $is_active,
			'normal_calc' => $calc_type,
		);
		
		return apply_filters( 'wbte_custom_price_array', $price_arr );
	}

	/**
	 * Finds and returns SKU
	 */
	public function wbte_get_product_sku( $product ) {
		
		$sku       = $product->get_sku();
		$is_unique = wc_product_has_unique_sku( $product->get_id(), $sku );
		
		if ( ! $is_unique ) {
			$sku = '';
		}

		return $sku;
	}

	/**
	 * Get sale in percent
	 *
	 * @param var $product product object.
	 */
	public function wbte_get_sale_percent( $product ) {

		$sale_percent = '';

		if ( $product->is_on_sale() && floatval( $product->get_regular_price() ) > 0 ) {
			$price_reg = $product->get_regular_price() ? floatval( $product->get_regular_price() ) : 0;
			$saleprice = $product->get_sale_price() ? floatval( $product->get_sale_price() ) : 0;
			$reduction = round( ( $price_reg - $saleprice ) * 100 / $price_reg );
			if ( ! $reduction ) {
				$reduction = '';
			}
			$sale_percent = $reduction;
		}

		return $sale_percent;
	}

	/**
	 * Get JSON products
	 *
	 */
	public function wbte_get_json_products() {
		
		$query        = $this->wbte_get_woo_products();
		$product_obj  = $query->get_products();
		$on_sale      = ( strlen( filter_input( 1, 'sales_filter', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'sales_filter', FILTER_DEFAULT ) : '';
		$wbte_options = get_option( 'wbte_options' );

		if ( $product_obj ) {
			
			foreach ( (array) $product_obj as $key => $value ) {
				
				$p_array = array();

				if ( 'products' === $key ) {
					
					foreach ( (array) $value as $prod_key => $product ) {
						if ( 'yes' !== $wbte_options['wbte_table_sale_filter'] ) {
							$p_array = $this->push_products_all( $product, $p_array );
						} else {
							$p_array = $this->push_products( $product, $p_array, $on_sale );
						}
						$p_array = array_unique( $p_array, SORT_REGULAR );
					}
				
				} elseif ( is_object( $value ) && 'product' === $value->post_type ) {

					$product = $value;
					if ( 'yes' !== $wbte_options['wbte_table_sale_filter'] ) {
						$p_array = $this->push_products_all( $product, $p_array );
					} else {
						$p_array = $this->push_products( $product, $p_array, $on_sale );
					}
					$p_array = array_unique( $p_array, SORT_REGULAR );

				}
				
				array_push( $this->json_products['rows'], $p_array );
				
			}

			asort( $this->json_products['rows'] );
			$this->json_products['total']     = $product_obj->total;
			$this->json_products['num-pages'] = $product_obj->max_num_pages;
			
			return $this->json_products;

		}
		
	}

	/**
	 * Function for push to product array
	 *
	 * @param var $product object.
	 * @param array $p_array array.
	 */
	public function push_products( $product, $p_array, $on_sale ) {

		if ( count( $product->get_children() ) > 0 ) {
			$children = $product->get_children();

			foreach ( $children as $val ) {
				$child = wc_get_product( $val );
				switch ( $on_sale ) {
					case 'onSale':
						if ( $child->get_sale_price() > 0 ) {
							array_push( $p_array, (object) $child );
						}
						break;
					case 'noSale':
						if ( '' === $child->get_sale_price() ) {
							array_push( $p_array, (object) $child );
						}
						break;
					case '':
						array_push( $p_array, (object) $child );
						break;
				}
			}
			
		} else {

			switch ( $on_sale ) {
				case 'onSale':
					if ( $product->get_sale_price() > 0 ) {
						array_push( $p_array, (object) $product );
					}
					break;
				case 'noSale':
					if ( '' === $product->get_sale_price() ) {
						array_push( $p_array, (object) $product );
					}
					break;
				case '':
					array_push( $p_array, (object) $product );
					break;
			}
		}
		
		return $p_array;
	}

	/**
	 * Function for push to product array
	 *
	 * @param var $product object.
	 * @param array $p_array array.
	 */
	public function push_products_all( $product, $p_array ) {

		if ( count( $product->get_children() ) > 0 ) {
			$children = $product->get_children();
	
			foreach ( $children as $val ) {
				$child = wc_get_product( $val );
				array_push( $p_array, (object) $child );
			}

		} else {
			
			array_push( $p_array, (object) $product );
			
		}
		
		return $p_array;
	}

	
	/**
	 * Loop products
	 *
	 */
	public function wbte_loop_products() {

		$wbte_templates = new WbteTemplates();

		$wbte_options  = get_option( 'wbte_options' );
		$post_per_page = ( $wbte_options['wbte_posts_per_page'] > 0 ) ? $wbte_options['wbte_posts_per_page'] : 50;
		$page_num      = ( strlen( filter_input( 1, 'paged', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'paged', FILTER_DEFAULT ) : 1;
		$view          = filter_input( 1, 'view', FILTER_DEFAULT );
		$p_obj         = $this->wbte_get_json_products();
		$products      = (array) $p_obj['rows'][0];
		$total_pages   = $p_obj['num-pages'];
		$items         = $products;
		$row_count     = $p_obj['total'];

		
		foreach ( (array) $items as $key => $value ) {
			
			if ( $value instanceof WC_Product && $value->is_type( 'simple' ) || $value instanceof WC_Product_Subscription ) {
				if ( isset( $view ) && 'ext' === $view ) {
					$wbte_templates->extended->wbte_get_table_row( $value, false, 0 );
				} else {
					$wbte_templates->wbte_get_table_row( $value, false, 0 );
				}
			} else {
				if ( $value instanceof WC_Product_Variation || $value instanceof WC_Product_Variable_Subscription ) {
					if ( isset( $view ) && 'ext' === $view ) {
						$wbte_templates->extended->wbte_get_table_row( $value, true, 0 );
					} else {
						$wbte_templates->wbte_get_table_row( $value, true, 0 );
					}
				}
			}

		}

		$this->wbte_make_paging_buttons( $total_pages, $page_num, $row_count );

	}

	/**
	 * Create paging buttons
	 */
	public function wbte_make_paging_buttons( $total_pages, $page_num, $total_rows ) {

		$product_cat    = filter_input( 1, 'product_cat', FILTER_DEFAULT );
		$product_search = filter_input( 1, 'product_search', FILTER_DEFAULT );
		$get_row_search = filter_input( 1, 'row_search', FILTER_DEFAULT );
		$radio_type     = filter_input( 1, 'stype', FILTER_DEFAULT );
		$view           = ( strlen( filter_input( 1, 'view', FILTER_DEFAULT ) ) > 0 ) ? filter_input( 1, 'view', FILTER_DEFAULT ) : 'prod';
		$query_strings  = '';

		if ( strlen( $product_cat ) > 0 ) {
			$query_strings .= '&product_cat=' . $product_cat;
		}
		if ( strlen( $product_search ) > 0 ) {
			$query_strings .= '&product_search=' . $product_search;
		}
		if ( strlen( $radio_type ) > 0 ) {
			$query_strings .= '&stype=' . $radio_type;
		}
		
		$current_url = admin_url( 'edit.php?post_type=product&page=wbte-products&view=' . $view . '&paged=%1$s' . $query_strings . '&row_search=' . $get_row_search );

		$pages_txt = '';
		$pages_of  = sprintf( $pages_txt, $total_rows );

		$button_html  = '<div id="wbte-pages">';
		$button_html .= '<span class="wbte-pages-txt">' . $pages_of . '</span>';

		if ( $total_pages > 1 ) {
			$prev_page  = 1;
			$next_page  = $total_pages;
			$prev_class = '';
			$next_class = '';

			if ( $page_num > 1 ) {
				$prev_page = $page_num - 1;
			} else {
				$prev_class = 'disabled';
			}

			if ( $page_num < $total_pages ) {
				$next_page = $page_num + 1;
			} else {
				$next_class = 'disabled';
			}

			$button_html .= '<a class="cyp-button ' . $prev_class . '" href="' . sprintf( esc_url( $current_url ), 1 ) . '"><i class="fas fa-angle-double-left"></i></a>';
			$button_html .= '<a class="cyp-button ' . $prev_class . '" href="' . sprintf( esc_url( $current_url ), $prev_page ) . '"><i class="fas fa-angle-left"></i></a>';
			
			/* translators: %s: page of pages */
			$page_txt     = __( ' page %1$s of %2$s ', 'woo-bulk-table-editor' );
			$button_html .= sprintf( $page_txt, $page_num, $total_pages );
			
			$button_html .= '<a class="cyp-button ' . $next_class . '" href="' . sprintf( esc_url( $current_url ), $next_page ) . '"><i class="fas fa-angle-right"></i></a>';
			$button_html .= '<a class="cyp-button ' . $next_class . '" href="' . sprintf( esc_url( $current_url ), $total_pages ) . '"><i class="fas fa-angle-double-right"></i></a>';
		}
		$button_html .= '</div>';
		?>
		<script>
			var $              = jQuery;
			var paging_buttons = '<?php echo wp_kses( $button_html, $this->wbte_get_allowed_html() ); ?>';
			
			$( '#wbte-paging-top' ).empty();
			$( '#wbte-paging-top' ).append( paging_buttons );

		</script>
		<?php
		
	}

	/**
	 * Get allowed html
	 */
	public function wbte_get_allowed_html() {
		
		$html = array(
			'div'    => array(
				'id'    => array(),
				'class' => array(),
				'style' => array(),
			),
			'span'   => array(
				'id'    => array(),
				'class' => array(),
				'style' => array(),
			),
			'i'   => array(
				'id'    => array(),
				'class' => array(),
				'style' => array(),
			),
			'a'   => array(
				'id'    => array(),
				'class' => array(),
				'style' => array(),
				'href'  => array(),
			),
			'img'   => array(
				'id'     => array(),
				'class'  => array(),
				'style'  => array(),
				'src'    => array(),
				'height' => array(),
			),
			'select' => array(
				'id'     => array(),
				'class'  => array(),
				'name'   => array(),
				'style'  => array(),
				'src'    => array(),
			),
			'option' => array(
				'id'       => array(),
				'class'    => array(),
				'name'     => array(),
				'style'    => array(),
				'value'    => array(),
				'selected' => array(),
			),
		);
		return $html;
	}

	/**
	 * Check for autofocus
	 */
	public function wbte_get_autofocus_value( $type ) {
		
		$mouseover_value = 'this.focus();';

		if ( 'date' === $type ) {
			if ( 'yes' !== $this->wbte_options['wbte_date_format_autofocus'] ) {
				$mouseover_value = '';
			}
		} else {
			if ( 'yes' === $this->wbte_options['wbte_no_autofocus'] ) {
				$mouseover_value = '';
			}
		}

		return $mouseover_value;
	
	}

	/** 
	* Get options for bulk functions
	*
	* @param var $type type of select.
	* @param var $value value.
	*/
	public function wbte_get_bulk_options( $type, $value = '' ) {

		$option_blank                 = __( 'Select', 'woo-bulk-table-editor' );
		$option_featured              = __( 'Featured', 'woo-bulk-table-editor' );
		$option_increase              = __( 'Increase', 'woo-bulk-table-editor' );
		$option_increase_percent      = __( 'Increase by %', 'woo-bulk-table-editor' );
		$option_increase_number       = __( 'Increase by fixed amount', 'woo-bulk-table-editor' );
		$option_decrease              = __( 'Decrease', 'woo-bulk-table-editor' );
		$option_decrease_percent      = __( 'Decrease by %', 'woo-bulk-table-editor' );
		$option_decrease_number       = __( 'Decrease by fixed amount', 'woo-bulk-table-editor' );
		$option_set_fixed             = __( 'Set fixed', 'woo-bulk-table-editor' );
		$option_set_fixed_number      = __( 'Set fixed amount', 'woo-bulk-table-editor' );
		$option_round_up              = __( 'Round upwards', 'woo-bulk-table-editor' );
		$option_round_down            = __( 'Round downwards', 'woo-bulk-table-editor' );
		$option_round_two             = __( 'Round two decimals', 'woo-bulk-table-editor' );
		$option_yes                   = __( 'Yes', 'woo-bulk-table-editor' );
		$option_no                    = __( 'No', 'woo-bulk-table-editor' );
		$option_yes_bakorder          = __( 'Allow', 'woo-bulk-table-editor' );
		$option_no_backorder          = __( 'Do not allow', 'woo-bulk-table-editor' );
		$option_notify_backorder      = __( 'Allow, but notify customer', 'woo-bulk-table-editor' );
		$option_clear                 = __( 'Clear', 'woo-bulk-table-editor' );
		$option_replace               = __( 'Set SKU', 'woo-bulk-table-editor' );
		$option_set_tags              = __( 'Set tags (use comma to split)', 'woo-bulk-table-editor' );
		$option_add                   = __( 'Add', 'woo-bulk-table-editor' );
		$option_add_after             = __( 'Add after', 'woo-bulk-table-editor' );
		$option_add_before            = __( 'Add before', 'woo-bulk-table-editor' );
		$option_add_id_after          = __( 'Add product ID after', 'woo-bulk-table-editor' );
		$option_add_id_before         = __( 'Add product ID before', 'woo-bulk-table-editor' );
		$option_instock               = __( 'In stock', 'woo-bulk-table-editor' );
		$option_outofstock            = __( 'Out of stock', 'woo-bulk-table-editor' );
		$option_onbackorder           = __( 'On backorder', 'woo-bulk-table-editor' );
		$option_generate_sku          = __( 'Generate SKU', 'woo-bulk-table-editor' );
		$option_upper_case            = __( 'Make uppercase', 'woo-bulk-table-editor' );
		$option_lower_case            = __( 'Make lowercase', 'woo-bulk-table-editor' );
		$option_prev_sale_price       = __( 'Get previous sale prices', 'woo-bulk-table-editor' );
		$option_v_visible             = __( 'Catalog & Search', 'woo-bulk-table-editor' );
		$option_v_catalog             = __( 'Catalog', 'woo-bulk-table-editor' );
		$option_v_search              = __( 'Search', 'woo-bulk-table-editor' );
		$option_v_hidden          	  = __( 'Hidden', 'woo-bulk-table-editor' );
		$option_add_weight       	  = __( 'Add Weight', 'woo-bulk-table-editor' );
		$option_add_length       	  = __( 'Add Length', 'woo-bulk-table-editor' );
		$option_add_width             = __( 'Add Width', 'woo-bulk-table-editor' );
		$option_add_height            = __( 'Add Height', 'woo-bulk-table-editor' );
		$option_increase_self_percent = __( 'Increase Saleprice by %', 'woo-bulk-table-editor' );
		$option_increase_self_number  = __( 'Increase Saleprice by fixed amount', 'woo-bulk-table-editor' );
		$option_decrease_self_number  = __( 'Decrease Saleprice by fixed amount', 'woo-bulk-table-editor' );
		$option_decrease_self_percent = __( 'Decrease Saleprice by %', 'woo-bulk-table-editor' );
		$option_increase_percent_s    = __( 'Increase by % (from Price)', 'woo-bulk-table-editor' );
		$option_increase_number_s     = __( 'Increase by fixed amount (from Price)', 'woo-bulk-table-editor' );
		$option_decrease_percent_s    = __( 'Decrease by % (from Price)', 'woo-bulk-table-editor' );
		$option_decrease_number_s     = __( 'Decrease by fixed amount (from Price)', 'woo-bulk-table-editor' );
		$option_prev_price            = __( 'Get previous price', 'woo-bulk-table-editor' );
		
		if ( 'stock' === $type ) {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_blank ); ?></option>
			<option value="up_n"><?php echo esc_html( $option_increase ); ?></option>
			<option value="down_n"><?php echo esc_html( $option_decrease ); ?></option>
			<option value="fix_n"><?php echo esc_html( $option_set_fixed ); ?></option>
			<?php
		} elseif ( 'featured' === $type ) {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_featured ); ?></option>
			<option value="yes"><?php echo esc_html( $option_yes ); ?></option>
			<option value="no"><?php echo esc_html( $option_no ); ?></option>
			<?php
		} elseif ( 'visibility' === $type ) {
			$selected_txt = 'selected="selected"';
			$default      = $selected_txt;
			$opt_visible  = '';
			$opt_catalog  = '';
			$opt_search   = '';
			$opt_hidden   = '';
			if ( strlen( $value ) > 0 ) {
				switch ( $value ) {
					case 'visible':
						$opt_visible = $selected_txt;
						$default     = '';
						break;
					case 'catalog':
						$opt_catalog = $selected_txt;
						$default     = '';
						break;
					case 'search':
						$opt_search = $selected_txt;
						$default    = '';
						break;
					case 'hidden':
						$opt_hidden = $selected_txt;
						$default    = '';
						break;
				}
			}
			?>
			<option value="visible" <?php echo esc_attr( $default ); ?>><?php echo esc_html( $option_blank ); ?></option>
			<option value="visible" <?php echo esc_attr( $opt_visible ); ?>><?php echo esc_html( $option_v_visible ); ?></option>
			<option value="catalog" <?php echo esc_attr( $opt_catalog ); ?>><?php echo esc_html( $option_v_catalog ); ?></option>
			<option value="search" <?php echo esc_attr( $opt_search ); ?>><?php echo esc_html( $option_v_search ); ?></option>
			<option value="hidden" <?php echo esc_attr( $opt_hidden ); ?>><?php echo esc_html( $option_v_hidden ); ?></option>
			<?php
		} elseif ( 'backorder' === $type ) {
			$selected_txt = 'selected="selected"';
			$default      = $selected_txt;
			$opt_no       = '';
			$opt_notify   = '';
			$opt_yes      = '';
			if ( strlen( $value ) > 0 ) {
				switch ( $value ) {
					case 'no':
						$opt_no  = $selected_txt;
						$default = '';
						break;
					case 'notify':
						$opt_notify = $selected_txt;
						$default    = '';
						break;
					case 'yes':
						$opt_yes = $selected_txt;
						$default = '';
						break;
				}
			}
			?>
			<option value="0" <?php echo esc_attr( $default ); ?>><?php echo esc_html( $option_blank ); ?></option>
			<option value="no" <?php echo esc_attr( $opt_no ); ?>><?php echo esc_html( $option_no_backorder ); ?></option>
			<option value="notify" <?php echo esc_attr( $opt_notify ); ?>><?php echo esc_html( $option_notify_backorder ); ?></option>
			<option value="yes" <?php echo esc_attr( $opt_yes ); ?>><?php echo esc_html( $option_yes_bakorder ); ?></option>
			<?php
		} elseif ( 'sku' === $type ) {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_blank ); ?></option>
			<option value="replace"><?php echo esc_html( $option_replace ); ?></option>
			<option value="generate"><?php echo esc_html( $option_generate_sku ); ?></option>
			<option value="add-before"><?php echo esc_html( $option_add_before ); ?></option>
			<option value="add-after"><?php echo esc_html( $option_add_after ); ?></option>
			<option value="add-id-before"><?php echo esc_html( $option_add_id_before ); ?></option>
			<option value="add-id-after"><?php echo esc_html( $option_add_id_after ); ?></option>
			<option value="add-weight"><?php echo esc_html( $option_add_weight ); ?></option>
			<option value="add-length"><?php echo esc_html( $option_add_length); ?></option>
			<option value="add-width"><?php echo esc_html( $option_add_width ); ?></option>
			<option value="add-height"><?php echo esc_html( $option_add_height ); ?></option>
			<option value="toupper"><?php echo esc_html( $option_upper_case ); ?></option>
			<option value="tolower"><?php echo esc_html( $option_lower_case ); ?></option>
			<option value="clear"><?php echo esc_html( $option_clear ); ?></option>
			<?php
		} elseif ( 'tags' === $type ) {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_blank ); ?></option>
			<option value="add"><?php echo esc_html( $option_add ); ?></option>
			<option value="replace"><?php echo esc_html( $option_set_tags ); ?></option>
			<option value="toupper"><?php echo esc_html( $option_upper_case ); ?></option>
			<option value="tolower"><?php echo esc_html( $option_lower_case ); ?></option>
			<option value="clear"><?php echo esc_html( $option_clear ); ?></option>
			<?php
		} elseif ( 'instock' === $type ) {
			$selected_txt = 'selected="selected"';
			$default      = $selected_txt;
			$opt_in       = '';
			$opt_out_of   = '';
			$opt_on_back  = '';
			if ( strlen( $value ) > 0 ) {
				switch ( $value ) {
					case 'instock':
						$opt_in  = $selected_txt;
						$default = '';
						break;
					case 'outofstock':
						$opt_out_of = $selected_txt;
						$default    = '';
						break;
					case 'onbackorder':
						$opt_on_back = $selected_txt;
						$default     = '';
						break;
				}
			}
			?>
			<option value="0" <?php echo esc_attr( $default ); ?>><?php echo esc_html( $option_blank ); ?></option>
			<option value="instock" <?php echo esc_attr( $opt_in ); ?>><?php echo esc_html( $option_instock ); ?></option>
			<option value="outofstock" <?php echo esc_attr( $opt_out_of ); ?>><?php echo esc_html( $option_outofstock ); ?></option>
			<option value="onbackorder" <?php echo esc_attr( $opt_on_back ); ?>><?php echo esc_html( $option_onbackorder ); ?></option>
			<?php
		} elseif ( 'numbers' === $type ) {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_blank ); ?></option>
			<option value="fix_n"><?php echo esc_html( $option_set_fixed ); ?></option>
			<option value="up_n"><?php echo esc_html( $option_increase ); ?></option>
			<option value="down_n"><?php echo esc_html( $option_decrease ); ?></option>
			<option value="clear"><?php echo esc_html( $option_clear ); ?></option>
			<?php
		} elseif ( 'sale_price' === $type ) {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_blank ); ?></option>
			<option value="up_p"><?php echo esc_html( $option_increase_percent_s ); ?></option>
			<option value="up_n"><?php echo esc_html( $option_increase_number_s ); ?></option>
			<option value="up_s_p"><?php echo esc_html( $option_increase_self_percent ); ?></option>
			<option value="up_s_n"><?php echo esc_html( $option_increase_self_number ); ?></option>
			<option value="down_p"><?php echo esc_html( $option_decrease_percent_s ); ?></option>
			<option value="down_n"><?php echo esc_html( $option_decrease_number_s ); ?></option>
			<option value="down_s_p"><?php echo esc_html( $option_decrease_self_percent ); ?></option>
			<option value="down_s_n"><?php echo esc_html( $option_decrease_self_number ); ?></option>
			<option value="fix_n"><?php echo esc_html( $option_set_fixed_number ); ?></option>
			<option value="round_up"><?php echo esc_html( $option_round_up ); ?></option>
			<option value="round_down"><?php echo esc_html( $option_round_down ); ?></option>
			<option value="round_two"><?php echo esc_html( $option_round_two ); ?></option>
			<option value="prev_sale_price"><?php echo esc_html( $option_prev_sale_price ); ?></option>
			<?php
		} elseif ( 'price' === $type ) {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_blank ); ?></option>
			<option value="up_p"><?php echo esc_html( $option_increase_percent ); ?></option>
			<option value="up_n"><?php echo esc_html( $option_increase_number ); ?></option>
			<option value="down_p"><?php echo esc_html( $option_decrease_percent ); ?></option>
			<option value="down_n"><?php echo esc_html( $option_decrease_number ); ?></option>
			<option value="fix_n"><?php echo esc_html( $option_set_fixed_number ); ?></option>
			<option value="round_up"><?php echo esc_html( $option_round_up ); ?></option>
			<option value="round_down"><?php echo esc_html( $option_round_down ); ?></option>
			<option value="round_two"><?php echo esc_html( $option_round_two ); ?></option>
			<option value="prev_price"><?php echo esc_html( $option_prev_price ); ?></option>
			<option value="clear_price"><?php echo esc_html( $option_clear ); ?></option>
			<?php
		} else {
			?>
			<option value="0" selected="selected"><?php echo esc_html( $option_blank ); ?></option>
			<option value="up_p"><?php echo esc_html( $option_increase_percent ); ?></option>
			<option value="up_n"><?php echo esc_html( $option_increase_number ); ?></option>
			<option value="down_p"><?php echo esc_html( $option_decrease_percent ); ?></option>
			<option value="down_n"><?php echo esc_html( $option_decrease_number ); ?></option>
			<option value="fix_n"><?php echo esc_html( $option_set_fixed_number ); ?></option>
			<option value="round_up"><?php echo esc_html( $option_round_up ); ?></option>
			<option value="round_down"><?php echo esc_html( $option_round_down ); ?></option>
			<option value="round_two"><?php echo esc_html( $option_round_two ); ?></option>
			<?php
		}

	}

	/**
	 * Get selected vendors
	 *
	 * @param var $product_id product id.
	 */
	public function wbte_get_vendor_selected( $product_id ) {
		
		$args = array(
			'hide_empty'   => false,
			'hierarchical' => false,
		);

		$terms = get_terms( WC_PRODUCT_VENDORS_TAXONOMY, $args );

		if ( ! empty( $terms ) ) {
			
			$post_term = wp_get_post_terms( $product_id, WC_PRODUCT_VENDORS_TAXONOMY );
			$post_term = ! empty( $post_term ) ? $post_term[0]->term_id : '';
			$output    = '<select class="input-select" name="vendor_selected">';
			$output   .= '<option value="">' . esc_html__( 'Select', 'woo-bulk-table-editor' ) . '</option>';

			foreach ( $terms as $term ) {
				$output .= '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( $post_term, $term->term_id, false ) . '>' . esc_html( $term->name ) . '</option>';
			}

			$output .= '</select>';

			echo wp_kses( $output, $this->wbte_get_allowed_html() );
		} 
	}

	/**
	 * Get vendors
	 *
	 */
	public function wbte_get_vendor_select() {
		
		$args = array(
			'hide_empty'   => false,
			'hierarchical' => false,
		);

		$terms = get_terms( WC_PRODUCT_VENDORS_TAXONOMY, $args );

		if ( ! empty( $terms ) ) {

			$output = '<option value="">' . esc_html__( 'Select', 'woo-bulk-table-editor' ) . '</option>';

			foreach ( $terms as $term ) {
				$output .= '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
			}

			echo wp_kses( $output, $this->wbte_get_allowed_html() );

		} else {
			esc_attr_e( 'None found', 'woo-bulk-table-editor' ); 
		}
	}

	/**
	 * Create export file (.csv)
	 */
	public function wbte_create_csv_export_file() {
		
		$file_name    = 'wbte-products-' . gmdate('Y-M-d H:i') . '.csv';
		$p_obj        = $this->wbte_get_json_products();
		$products     = (array) $p_obj['rows'][0];
		$custom_price = $this->wbte_get_custom_price_info();
		$date_format  = get_option( 'date_format' );

		$head_csv = array(
			__( 'ID', 'woo-bulk-table-editor' ),
			__( 'Name', 'woo-bulk-table-editor' ),
			__( 'SKU', 'woo-bulk-table-editor' ),
			__( 'Categories', 'woo-bulk-table-editor' ),
			__( 'Featured', 'woo-bulk-table-editor' ),
			__( 'Price', 'woo-bulk-table-editor' ), 
			__( 'Sale price', 'woo-bulk-table-editor' ),
			__( 'Sale start date', 'woo-bulk-table-editor' ),
			__( 'Sale end date', 'woo-bulk-table-editor' ),
			__( 'Sale %', 'woo-bulk-table-editor' ),
			__( 'Stock', 'woo-bulk-table-editor' ),
			__( 'Stock value', 'woo-bulk-table-editor' ),
			__( 'Tags', 'woo-bulk-table-editor' ),
		);

		if ( 'yes' === $custom_price['active'] ) {
			array_splice( $head_csv, 5, 0, $custom_price['name'] );
		}

		$csv      = implode( ';', $head_csv );
		$rows     = array();
		$t_sep    = get_option( 'woocommerce_price_thousand_sep' );
		$d_point  = get_option( 'woocommerce_price_decimal_sep' );
		$decimals = get_option( 'woocommerce_price_num_decimals' );
		
		foreach ( $products as $key => $p ) {
				
			$id           = $p->get_id();
			$product_name = $p->get_title();
			$attributes   = $p->get_attributes();

			if ( isset( $attributes ) && is_array( $attributes ) ) {
				foreach ( $attributes as $key => $val ) {
					if ( isset( $val ) && is_string( $val ) && strlen( $val ) > 0 ) {
						$product_name .= ', ' . $val;
					}
				}

				$id = $p->get_parent_id();
			
				if ( 0 === $id ) {
					$id = $p->get_id();
				}
			}

			$terms        = get_the_terms( $id, 'product_cat' );
			$categories   = '';
			$tags_arr     = get_the_terms( $id, 'product_tag' );
			$tags         = '';
			$sale_percent = '';

			if ( $terms ) {
				foreach ( $terms as $category ) {
					$categories .= $category->name . ', ';
				}
				$categories = substr( $categories, 0, strlen( $categories ) -2 );
			}
			
		
			if ( $tags_arr ) {
				foreach ( $tags_arr as $obj ) {
					$tags .= $obj->name . ', ';
				}
				$tags = substr( $tags, 0, strlen( $tags ) - 2 );
			}

			if ( strlen( $p->get_sale_price() ) > 0 ) {
				$sale_percent = ( $p->get_regular_price() - $p->get_sale_price() ) * 100 / $p->get_regular_price();
			}

			$row = array(
				$p->get_id(),
				$product_name,
				$p->get_sku(),
				$categories,
				( $p->get_featured() ) ? 'on' : '',
				( $p->get_regular_price() ) ? $p->get_regular_price() : '',
				( $p->get_sale_price() ) ? $p->get_sale_price() : '',
				( $p->get_date_on_sale_from() ) ? date_format( $p->get_date_on_sale_from(), $date_format ) : '',
				( $p->get_date_on_sale_to() ) ? date_format( $p->get_date_on_sale_to(), $date_format ) : '',
				( '' !== $sale_percent ) ? number_format( (float) $sale_percent, $decimals, $d_point, $t_sep ) : '',//sale %
				$p->get_stock_quantity(),
				( $p->get_stock_quantity() > 0 && $p->get_regular_price() > 0 ) ? number_format( (float) ( (int) $p->get_stock_quantity() * $p->get_regular_price() ), $decimals, $d_point, $t_sep ) : '',
				$tags,
			);

			if ( 'yes' === $custom_price['active'] ) {
				array_splice( $row, 5, 0, get_post_meta( $id, $custom_price['price'], true ) );
			}

			array_push( $rows, $row );

		}
		
		$csv .= "\n";
		foreach ( $rows as $item ) {
			$csv .= implode( ';', $item );
			$csv .= "\n";
		}
	  
		header( 'Content-Type: text/csv' ); 
		header( 'Content-Disposition: attachment; filename="' . $file_name . '"' );
		header( 'Pragma: no-cache' ); 
		header( 'Expires: Sat, 26 Jul 2010 05:00:00 GMT' );
	
		echo esc_attr( $csv );

		exit;

	}


}

