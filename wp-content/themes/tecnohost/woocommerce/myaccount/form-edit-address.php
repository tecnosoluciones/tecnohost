<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );
$customer_id = get_current_user_id();
do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
    <?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>
    <h3><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></h3><?php // @codingStandardsIgnoreLine ?>
    <?php echo do_shortcode('[gravityform id=4 title=false description=false ajax=true]'); ?>
<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>

<script>
    jQuery("li#field_4_19  select#input_4_19 option:selected").text()
    if(jQuery("li#field_4_19  select#input_4_19 option:selected").text() === "Colombia"){
        jQuery('select#input_4_24 option[value="'+jQuery("#input_4_26").val()+'"]').attr("selected", true);
    }
    else if(jQuery("li#field_4_19  select#input_4_19 option:selected").text() ===  "Estados Unidos"){
        jQuery('select#input_4_18 option[value="'+jQuery("#input_4_26").val()+'"]').attr("selected", true);
    }
    else if(jQuery("li#field_4_19  select#input_4_19 option:selected").text() === "Venezuela"){
        jQuery('select#input_4_25 option[value="'+jQuery("#input_4_26").val()+'"]').attr("selected", true);
    }
    else{
        jQuery('#input_4_20').val(jQuery("#input_4_26").val());
        jQuery('#input_4_20').attr('value', jQuery("#input_4_26").val());
    }
</script>
