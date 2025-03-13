<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
		'shipping' => __( 'Shipping address', 'woocommerce' ),
	), $customer_id );
} else {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
	), $customer_id );
}

$oldcol = 1;
$col    = 1;
?>

<p>
	<?php echo apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); ?>
</p>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	<div class="u-columns woocommerce-Addresses col2-set addresses">
<?php endif; ?>

<?php

foreach ( $get_addresses as $name => $title ) : ?>

    <div class="u-column<?php echo ( ( $col = $col * -1 ) < 0 ) ? 1 : 2; ?> col-<?php echo ( ( $oldcol = $oldcol * -1 ) < 0 ) ? 1 : 2; ?> woocommerce-Address">
        <header class="woocommerce-Address-title title">
            <h3><?php echo $title; ?></h3>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit"><?php _e( 'Edit', 'woocommerce' ); ?></a>
        </
            header>
        <address><?php $address = wc_get_account_formatted_address( $name );
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
            ?></address>
    </div>

<?php endforeach;
?>

<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
	</div>
<?php endif;
