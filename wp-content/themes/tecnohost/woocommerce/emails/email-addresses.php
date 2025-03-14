<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 5.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
$address    = $order->get_formatted_billing_address();
$shipping   = $order->get_formatted_shipping_address();

?><table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<h2><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2>

			<address class="address">
                <?php echo "<strong>Nombres y Apellidos:</strong> ".$order->get_billing_first_name()." ".$order->get_billing_last_name(); ?>
                <br/><?php echo "<strong>Empresa / Institución:</strong> ".$order->get_billing_company(); ?>
                <br/><?php echo "<strong>Identificación Fiscal (NIT/CC/CE/RIF/EIN):</strong> ".get_post_meta($order->get_id(), '_billing_nit', true); ?>
                <?php if ( $order->get_billing_email() ) : ?>
                    <br/><?php echo "<strong>Email de Facturación:</strong> ".esc_html( $order->get_billing_email() ); ?>
                <?php endif; ?>
                <br/><?php echo "<strong>País:</strong> ".WC()->countries->countries[$order->get_billing_country()]; ?>
                <br/><?php echo "<strong>Dirección Fiscal Línea 1:</strong> ".$order->get_billing_address_1(); ?>
                <br/><?php echo "<strong>Dirección Fiscal Línea 2:</strong> ".$order->get_billing_address_2(); ?>
                <br/><?php echo "<strong>Ciudad:</strong> ".$order->get_billing_city(); ?>
                <br/><?php echo "<strong>Departamento:</strong> ".$order->get_billing_state(); ?>
                <br/><?php echo "<strong>Código postal:</strong> ".$order->get_billing_postcode(); ?>
                <?php if ( $order->get_billing_phone() ) : ?>
					<br/><?php echo " <strong>Teléfono:</strong> ".wc_make_phone_clickable( $order->get_billing_phone() ); ?>
				<?php endif; ?>

			</address>
		</td>
		<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && $shipping ) : ?>
			<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding:0;" valign="top" width="50%">
				<h2><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2>

				<address class="address"><?php echo wp_kses_post( $shipping ); ?></address>
			</td>
		<?php endif; ?>
	</tr>
</table>
