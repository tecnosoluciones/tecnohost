<?php
/**
 * Recurring totals
 *
 * @author  Prospress
 * @package WooCommerce Subscriptions/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$display_th = true;

?>
<tr class="recurring-titl_tabl">
    <th colspan="3">Detalle de la suscripción </th>
</tr>
<tr class="recurring-totals">
    <th colspan="1"><?php esc_html_e( 'Suscripciones', 'woocommerce-subscriptions' ); ?></th>
    <th colspan="1" class="no_mobilie"><?php esc_html_e( 'Total/Recurrencias', 'woocommerce-subscriptions' ); ?></th>
    <th colspan="1" class="no_mobilie"><?php esc_html_e( 'Fechas estimadas de renovaciones', 'woocommerce-subscriptions' ); ?></th>
</tr>

<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
    <?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
        <?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
            <?php continue; ?>
        <?php endif; ?>
        <?php foreach ( $recurring_cart->get_coupons() as $recurring_code => $recurring_coupon ) : ?>
            <?php if ( $recurring_code !== $code ) { continue; } ?>
            <tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?> recurring-total">
                <?php if ( $display_th ) : $display_th = false; ?>
                    <th rowspan="<?php echo esc_attr( $carts_with_multiple_payments ); ?>"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                    <td data-title="<?php wc_cart_totals_coupon_label( $coupon ); ?>"><?php wcs_cart_totals_coupon_html( $recurring_coupon, $recurring_cart ); ?>
                        <?php echo ' '; wcs_cart_coupon_remove_link_html( $recurring_coupon ); ?></td>
                <?php else : ?>
                    <td><?php wcs_cart_totals_coupon_html( $recurring_coupon, $recurring_cart ); ?></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <?php $display_th = true; ?>
<?php endforeach; ?>

<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
    <?php wcs_cart_totals_shipping_html(); ?>
<?php endif; ?>
<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
    <?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
        <?php continue; ?>
    <?php endif; ?>
<?php endforeach; ?>

<?php /*if ( wc_tax_enabled() && WC()->cart->tax_display_cart === 'excl' ) : */?><!--
    <?php /*if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : */?>

        <?php /*foreach ( WC()->cart->get_taxes() as $tax_id => $tax_total ) : */?>
            <?php /*foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : */?>
                <?php /*if ( 0 == $recurring_cart->next_payment_date ) : */?>
                    <?php /*continue; */?>
                <?php /*endif; */?>
                <?php /*foreach ( $recurring_cart->get_tax_totals() as $recurring_code => $recurring_tax ) : */?>
                    <?php /*if ( ! isset( $recurring_tax->tax_rate_id ) || $recurring_tax->tax_rate_id !== $tax_id ) { continue; } */?>
                    <tr class="tax-rate tax-rate-<?php /*echo esc_attr( sanitize_title( $recurring_code ) ); */?> recurring-total">
                        <?php /*if ( $display_th ) : $display_th = false; */?>
                            <th><?php /*echo esc_html( $recurring_tax->label ); */?></th>
                            <td><?php /*echo wp_kses_post( wcs_cart_price_string( $recurring_tax->formatted_amount, $recurring_cart ) ); */?></td>
                        <?php /*else : */?>
                            <th></th>
                            <td><?php /*echo wp_kses_post( wcs_cart_price_string( $recurring_tax->formatted_amount, $recurring_cart ) ); */?></td>
                        <?php /*endif; */?>
                    </tr>
                <?php /*endforeach; */?>
            <?php /*endforeach; */?>
            <?php /*$display_th = true; */?>
        <?php /*endforeach; */?>

    <?php /*else : */?>

        <?php /*foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : */?>
            <?php /*if ( 0 == $recurring_cart->next_payment_date ) : */?>
                <?php /*continue; */?>
            <?php /*endif; */?>
            <tr class="tax-total recurring-total">
                <?php /*if ( $display_th ) : $display_th = false; */?>
                    <th><?php /*echo esc_html( WC()->countries->tax_or_vat() ); */?></th>
                    <td data-title="<?php /*echo esc_attr( WC()->countries->tax_or_vat() ); */?>"><?php /*echo wp_kses_post( wcs_cart_price_string( $recurring_cart->get_taxes_total(), $recurring_cart ) ); */?></td>
                <?php /*else : */?>
                    <th></th>
                    <td><?php /*echo wp_kses_post( wcs_cart_price_string( $recurring_cart->get_taxes_total(), $recurring_cart ) ); */?></td>
                <?php /*endif; */?>
            </tr>
        <?php /*endforeach; */?>
        <?php /*$display_th = true; */?>
    <?php /*endif; */?>
--><?php /*endif; */?>

<?php

foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) :
    $periodo = wcs_cart_price_string( $recurring_cart->get_total(), $recurring_cart );
    $total_conperiodo = '<strong>' . $periodo . '</strong> ';

    if ( 0 !== $recurring_cart->next_payment_date ) {
        $first_renewal_date = date_i18n( wc_date_format(), wcs_date_to_time( get_date_from_gmt( $recurring_cart->next_payment_date ) ) );
        // translators: placeholder is a date
        $fecha  = '<div class="first-payment-date">' . sprintf( __( 'Próxima renovación: %s', 'woocommerce-subscriptions' ), $first_renewal_date ) .  '</div>';
    }


    foreach($recurring_cart->cart_contents as $key => $value){
        $_product = apply_filters( 'woocommerce_cart_item_product', $value['data'], $key, $value );
        $nombre = $_product->get_name();//apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), "", "" );
        foreach($value['addons'] as $key_addons => $value_addons){
            $addons = "<dt class='variation-NombredelDominio'>".$value_addons['name']."</dt> ".$value_addons['value'];
        }
    }

    ?>

    <tr class="order-total recurring-total">
        <?php if ( $display_th ) : $display_th = false; ?>
            <td class="car_recurrent">
                <?php echo $nombre."<br>".$addons; ?>
            </td>
            <td>
                <?php echo $total_conperiodo; ?>
            </td>
            <td>
                <?php echo $fecha; ?>
            </td>
        <?php else : ?>
            <td class="car_recurrent">
                <?php echo $nombre."<br>".$addons; ?>
            </td>
            <td><?php echo $total_conperiodo; ?></td>
            <td>
                <?php echo $fecha; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>


<script>
    jQuery(function(){
        jQuery("div.suscrip_aba").show()
    })
</script>
