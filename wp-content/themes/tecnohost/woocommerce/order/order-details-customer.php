<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 5.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
$customer_id = get_current_user_id();
?>
<section class="woocommerce-customer-details">

    <?php if ( $show_shipping ) : ?>

    <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
        <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

            <?php endif; ?>

            <h2 class="woocommerce-column__title" id="tit_total"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>

            <address>
                <?php
                echo '<div class="direcc"><strong>Nombres y Apellidos: </strong>'.get_user_meta( $customer_id, 'billing_first_name', true ).' '.get_user_meta( $customer_id, 'billing_last_name', true ).'</div>';
                echo '<div class="direcc"><strong>Empresa / Institución: </strong>'.get_user_meta( $customer_id, 'billing_company', true ).'</div>';
                echo '<div class="direcc"><strong>Identificación Fiscal (NIT/CC/CE/RIF/EIN): </strong>'.get_user_meta( $customer_id, 'billing_nit', true ).'</div>';
                echo '<div class="direcc"><strong>Email de Facturación: </strong>'.get_user_meta( $customer_id, 'billing_email', true ).'</div>';
                echo '<div class="direcc"><strong>País: </strong>'.WC()->countries->countries[get_user_meta( $customer_id, 'billing_country', true )].'</div>';
                echo '<div class="direcc"><strong>Dirección Fiscal Línea 1: </strong>'.get_user_meta( $customer_id, 'billing_address_1', true ).'</div>';
                echo '<div class="direcc"><strong>Dirección Fiscal Línea 2: </strong>'.get_user_meta( $customer_id, 'billing_address_2', true ).'</div>';
                echo '<div class="direcc"><strong>Ciudad: </strong>'.get_user_meta( $customer_id, 'billing_city', true ).'</div>';
                echo '<div class="direcc"><strong>Departamento: </strong>'.get_user_meta( $customer_id, 'billing_state', true ).'</div>';
                echo '<div class="direcc"><strong>Código postal: </strong>'.get_user_meta( $customer_id, 'billing_postcode', true ).'</div>';
                echo '<div class="direcc"><strong>Teléfono: </strong>'.get_user_meta( $customer_id, 'billing_phone', true ).'</div>';
                ?>
            </address>

            <?php if ( $show_shipping ) : ?>

        </div><!-- /.col-1 -->

        <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
            <h2 class="woocommerce-column__title"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>
            <address>
                <?php echo wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce' ) ) ); ?>
            </address>
        </div><!-- /.col-2 -->

    </section><!-- /.col2-set -->

<?php endif; ?>

    <?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>
