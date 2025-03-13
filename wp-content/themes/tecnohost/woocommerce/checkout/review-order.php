<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

$cont_dom_swith = 0;
$iva_dom_swith = 0;
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
    }
}
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );
        $cont_dom_swith = 0;
        $iva_dom_swith = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $subscription_resubscribe = false;
            $ID_variacion = $cart_item['variation_id'];
            $pago_inicial = apply_filters( 'woocommerce_subscriptions_product_sign_up_fee', get_post_meta($ID_variacion, "_subscription_sign_up_fee", true), wc_get_product($ID_variacion));
            $billing_period      = WC_Subscriptions_Product::get_period( $ID_variacion );
            $billing_interval = WC_Subscriptions_Product::get_interval( $ID_variacion );
            if($cart_item['subscription_resubscribe']){
                $subscription_resubscribe = false;
            }
            else if($cart_item['subscription_renewal']){
                $id_suscripcion = $cart_item['subscription_renewal']['subscription_id'];
                $id_orden_renovacion = $cart_item['subscription_renewal']['renewal_order_id'];
                foreach($cart_item['data']->get_meta_data() AS $key_2 => $value_2){
                    if($value_2->get_data()['key'] == "_subscription_period"){
                        if($value_2->get_data()['value'] != "month"){
                            $subscription_resubscribe = false;
                            break;
                        }
                    }
                }

                $arg = array(
                    'post_type' => 'shop_order',
                    'post_status' => 'wc-activar-deposito',
                    'meta_query' => array(
                        array(
                            'key' => '_subscription_renewal',
                            'value' => $id_suscripcion,
                            'compare' => '=',
                        )
                    )
                );

                $query  = new WP_Query($arg);
                $fecha_creaci_renovac = wc_get_order($id_orden_renovacion)->get_date_created();
                $uso_mes_deposito = $query->have_posts();

                $norequieremesdeposito = false;

                if($uso_mes_deposito == 1){
                    while ( $query->have_posts() ) : $query->the_post();
                        $fecha_activaci = get_the_date("Y-m-d");
                        $fecha_inicio_activa_mes = new DateTime($fecha_activaci);
                        $diferencia = $fecha_inicio_activa_mes->diff($fecha_creaci_renovac);
                        if($diferencia->m < 1){
                            $subscription_resubscribe = true;
                        }
                    endwhile;
                }
            }
            else if($cart_item['subscription_switch']){
                $precio = $cart_item['line_subtotal'];
                $product_id = $cart_item['product_id'];
                $val_cate = wc_get_object_terms($product_id, 'product_cat', "term_id");
                $taxable = get_post_meta($product_id,"_tax_status",true);
                if ($val_cate[0] == 26) {
                    if("taxable" == $taxable) {
                        $cont_dom_swith = 1;
                        $impuestos = taxes_cambi_dom($precio);
                        $iva_dom_swith = $impuestos['iva'];
                    }
                }
                else if($val_cate[0] == "447" ||$val_cate[0] == "448" || $val_cate[0] == "446" || $val_cate[0] == "449"){
                    $vps_cambio = 1;
                    echo '
                        <script >
                            jQuery(window).on( "load", function() {
                                setTimeout(() => {
                                  jQuery("td.product-total span.subscription-details").hide();
                                  jQuery("td.product-subtotal span.subscription-details").hide();
                                  jQuery("#loader").hide();
                                }, "4000");
                            });
                        </script>';
                }
            }



			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-name">
						<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
					<td class="product-total">
						<?php
                        if($subscription_resubscribe == 1){
                                $subscription_string = '<span class="subscription-details">' . sprintf(
                                        __( 'every %s', 'woocommerce-subscriptions' ),
                                        wcs_get_subscription_period_strings( $billing_interval, $billing_period )
                                    );

                                $subscription_string = sprintf( __( '%1$s y un mes de deposito de %2$s', 'woocommerce-subscriptions' ), $subscription_string, wc_price($_product->get_price() ));

                                echo wc_price($_product->get_price())." ".$subscription_string;
                            }
                            else {
                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                        ?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td><?php echo wc_price(WC()->cart->get_cart_contents_total()-$iva_dom_swith); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach;

        $des_feed = WC()->cart->get_fees();
        if(count($des_feed) != 0){
            $mensaje_iva = ' (despues de descuento incluido)';
        }
        else{
            $mensaje_iva = '';
        }

        ?>

        <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
            <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                    <th><?php echo esc_html( $tax->label ) . $mensaje_iva; ?></th>
                    <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr class="tax-total">
                <th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $mensaje_iva; ?></th>
                <td><?php wc_cart_totals_taxes_total_html(); ?></td>
            </tr>
        <?php endif;
        if($cont_dom_swith == 1){ ?>
            <tr class="tax-total">
                <th><?php echo esc_html("IVA" ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                <td><?php echo apply_filters( 'woocommerce_cart_totals_taxes_total_html', wc_price($iva_dom_swith));; ?></td>
            </tr>
        <?php }

		do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
