<?php
/**
 * Gravity Flow Merge Tag Assignee WooCommerce Coupon
 *
 * @package     GravityFlow
 * @copyright   Copyright (c) 2015-2019, Steven Henty S.L.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

if ( ! function_exists( 'WC' ) ) {
	return;
}

/**
 * Class Gravity_Flow_Merge_Tag_Assignee_WooCommerce
 *
 * @since 1.2
 */
class Gravity_Flow_Merge_Tag_Assignee_WooCommerce_Coupon extends Gravity_Flow_Merge_Tag_Assignee_Base {

	/**
	 * The name of the merge tag.
	 *
	 * @since 1.2
	 *
	 * @var string
	 */
	public $name = 'woocommerce_coupon';

	/**
	 * The regular expression to use for the matching.
	 *
	 * @since 1.2
	 *
	 * @var string
	 */
	protected $regex = '/{workflow_woocommerce_coupon(:(.*?))?}/';

	/**
	 * Replace the {workflow_woocommerce_coupon} merge tags.
	 *
	 * @since 1.2
	 *
	 * @param string $text The text being processed.
	 *
	 * @return string
	 */
	public function replace( $text ) {
		$matches = $this->get_matches( $text );

		if ( ! empty( $matches ) ) {
			$coupon_codes = gform_get_meta( $this->step->get_entry_id(), 'workflow_woocommerce_coupon_code' );

			if ( empty( $this->step ) || empty( $coupon_codes ) ) {
				foreach ( $matches as $match ) {
					$full_tag = $match[0];
					$text     = str_replace( $full_tag, '', $text );
				}

				return $text;
			}

			foreach ( $matches as $match ) {
				$full_tag      = $match[0];
				$property_name = isset( $match[2] ) ? $match[2] : '';

				if ( empty( $property_name ) ) {
					$text = str_replace( $full_tag, implode( ', ', $coupon_codes ), $text );
				} else {
					$coupon         = new WC_Coupon( $coupon_codes[0] );
					$property_value = '';

					if ( $property_name === 'products' ) {
						$property_name = 'product_ids';
					}

					if ( is_callable( array( $coupon, "get_{$property_name}" ) ) ) {
						$property_value = $coupon->{"get_{$property_name}"}();

						if ( empty( $property_value ) ) {
							$property_value = '-';
						}
					}

					$text = str_replace( $full_tag, $this->format_property_value( $property_value, $property_name ), $text );
				}
			}
		}

		return $text;
	}

	/**
	 * Formats the value which will replace the merge tag.
	 *
	 * @since 1.2
	 *
	 * @param string|array $value The value to be formatted.
	 * @param string       $name  The property name..
	 *
	 * @return string
	 */
	protected function format_property_value( $value, $name ) {
		switch ( $name ) {
			case 'discount_type':
				$value = wc_get_coupon_type( $value );
				break;
			case 'amount':
				$value = wp_strip_all_tags( wc_price( $value ) );
				break;
			case 'usage_limit':
				$value = ( $value !== '-' ) ? $value : '&infin;';
				break;
			case 'product_ids':
				$value = gravity_flow_woocommerce()->get_product_names( $value );
				break;
			case 'product_categories':
				$value = gravity_flow_woocommerce()->get_product_category_names( $value );
				break;
		}

		return parent::format_value( $value );
	}
}

Gravity_Flow_Merge_Tags::register( new Gravity_Flow_Merge_Tag_Assignee_WooCommerce_Coupon );
