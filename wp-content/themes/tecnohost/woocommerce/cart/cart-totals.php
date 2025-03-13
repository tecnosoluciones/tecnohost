<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;
$location = WC_Geolocation::geolocate_ip();
$customer = new WC_Customer(get_current_user_id());

if ( is_user_logged_in() ) {
    $country = WC()->session->get('customer')['shipping_country'];
}
else{
    $country = $location['country'];
}
$cont_dom_swith = 0;
$iva_dom_swith = 0;
$vps_cambio = 0;
foreach(WC()->cart->cart_contents as $key => $value) {
    if ($value['subscription_switch'] && count($value['subscription_switch']) != 0) {
        $precio = $value['line_subtotal'];
        $product_id = $value['product_id'];
        $val_cate = wc_get_object_terms($product_id, 'product_cat', "term_id");
        $taxable = get_post_meta($product_id,"_tax_status",true);
        if ($val_cate[0] == 26) {
            if("taxable" == $taxable) {
                $cont_dom_swith = 1;
                $impuestos = taxes_cambi_dom($precio);
                $iva_dom_swith = $impuestos['iva'];
            }
            break;
        }
        else if($val_cate[0] == "447" ||$val_cate[0] == "448" || $val_cate[0] == "446" || $val_cate[0] == "449"){
            $vps_cambio = 1;
            echo '
                <div id="loader">
                    <div class="spinner">
                        <div class="loaders l1"></div>
                        <div class="loaders l2"></div>
                    </div>
                </div>
                <script >
                    jQuery(window).on( "load", function() {
                        setTimeout(() => {
                          jQuery("td.product-price span.subscription-details").hide();
                          jQuery("td.product-subtotal span.subscription-details").hide();
                          jQuery("#loader").hide();
                        }, "3000");
                    });
                </script>';
        }
    }
}
?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2><?php esc_html_e( 'Cart totals', 'woocommerce' ); ?></h2>

	<table cellspacing="0" class="shop_table shop_table_responsive">

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php echo wc_price(WC()->cart->get_cart_contents_total() - $iva_dom_swith); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php

        $taxable_address = WC()->customer->get_taxable_address();
        $estimated_text  = '';

        $des_feed = WC()->cart->get_fees();
        if(count($des_feed) != 0){
            $mensaje_iva = ' (despues de descuento incluido)';
        }
        else{
            $mensaje_iva = '';
        }

        if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
            /* translators: %s location. */
            $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
        }
        if($country == "CO") {
            if ('itemized' === get_option('woocommerce_tax_total_display')) {
                foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                    ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <th><?php echo esc_html($tax->label) . $estimated_text . $mensaje_iva; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                        <td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="tax-total">
                    <th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text . $mensaje_iva; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                    <td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
                </tr>
                <?php
            }

            if($cont_dom_swith == 1){ ?>
                <tr class="tax-total">
                    <th><?php echo esc_html("IVA" ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                    <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php echo apply_filters( 'woocommerce_cart_totals_taxes_total_html', wc_price($iva_dom_swith));; ?></td>
                </tr>
            <?php }
        }

		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
                <?php
                    if($country == "CO") {
                        wc_cart_totals_order_total_html();
                    }else{
                        echo wc_price(WC()->cart->get_cart_contents_total());
                    }
                ?>
            </td>
		</tr>

	</table>



	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

    <div class="cart_totals_btn" id="con_btn">

        <div class="wc-proceed-to-checkout" style="margin-bottom: initial;">
            <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
        </div>

    </div>

</div>

<div class="cart_totals_recurring" id="recurring_total">

    <table cellspacing="0" class="shop_table shop_table_responsive">

        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

    </table>

</div>

<div class="cart_totals_btn suscrip_aba" id="con_btn">

    <div class="wc-proceed-to-checkout">
        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
    </div>

</div>