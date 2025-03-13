<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 7.4.0
 */

defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_before_cart' );

do_shortcode('[pasos-contratacion paso=2]');

$moneda = get_woocommerce_currency();
$rates = WC_Tax::get_rates();
$location = WC_Geolocation::geolocate_ip();
$customer = new WC_Customer(get_current_user_id());

if ( is_user_logged_in() ) {
    $country = WC()->session->get('customer')['shipping_country'];
}
else{
    $country = $location['country'];
}


foreach ($rates AS $key => $value){
    $base_iva = $value['rate'];
}
/*imprimir( WC()->cart->get_fees());
imprimir( WC()->cart->get_tax_totals());*/
?>
<script>
    function sendFormCart(action, options_service_input, cart_item_key, id_product, whois_domain, group){

    jQuery('#cart_item_key').val(cart_item_key);
    jQuery('#whois_domain').val(whois_domain);
    jQuery('#id_product').val(id_product);
    jQuery('#options_service_input').val(options_service_input);
    jQuery('#id_group').val(group);
    
    jQuery('form').attr('action', action);
    jQuery('form').submit();
}
</script>
<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php do_action( 'woocommerce_before_cart_table' ); ?>
    
    <input type="hidden" name="whois_domain" id="whois_domain" value="" />
    <input type="hidden" name="options_service_input" id="options_service_input" value="" />
    <input type="hidden" name="mode_hosting" id="mode_hosting" value="0" />
    <input type="hidden" name="cart_item_key" id="cart_item_key" value="" />
    <input type="hidden" name="id_product" id="id_product" value="" />
    <input type="hidden" name="id_group" id="id_group" value="" />
    
    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
        <thead>
        <tr>
            <th class="product-remove">&nbsp;</th>
            <th class="product-thumbnail">&nbsp;</th>
            <th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
            <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>

            <?php if($country == "CO"){ ?>
                <th class="product-iva"><?php esc_html_e( 'IVA', 'woocommerce' ); ?></th>
            <?php } ?>
            <th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
          
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
            $iva = $cart_item["line_tax_data"]["total"][1];
            $prod_cat_args = wc_get_object_terms( $product_id, 'product_cat', 'term_id' , true);
            foreach ($prod_cat_args AS $key => $value){
                if($value == "25" || $value  == "32" || $value  == "26"){
                    $no_enlace = true;
                }
                else {
                    $no_enlace = false;
                }
            }

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                if($no_enlace == false) {
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                }
                else{
                    $product_permalink = "";
                }
                ?>
                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                    <td class="product-remove">
                        <?php
                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                esc_html__( 'Remove this item', 'woocommerce' ),
                                esc_attr( $product_id ),
                                esc_attr( $_product->get_sku() )

                            ), $cart_item_key);
                        ?>
                    </td>

                    <td class="product-thumbnail">
                        <?php
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                        if ( ! $product_permalink ) {
                            echo $thumbnail; // PHPCS: XSS ok.
                        } else {
                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                        }
                        ?>
                    </td>

                    <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                        <?php
                        if ( ! $product_permalink ) {
                            
                            
                        $categories = explode(',', wc_get_product_category_list(absint(  $cart_item['product_id'])));
                        foreach($categories as $category){
                            $categories[] = trim(strip_tags($category));
                        }
                        
                            if(in_array('Hosting Avanzado (Revendedores)', $categories) or in_array('Hosting Estándar', $categories) or in_array('Dominios', $categories)){
                                                                
                                $domain = $cart_item["addons"][0]['value'];
                                foreach($cart_item['addons'] as $item_2 => $value) { 
                                    if($value['name'] == 'Grupo'){
                                       $group = $value["value"];
                                    }
                                }
                          
                                if(in_array('Dominios', $categories)){
                                    $options_service_input = 'd';
                                    $action_ =  site_url().'/contratacion/?category=registro';
                                    $domain = explode('.', $domain);
                                    $domain = $domain[0];
                                }else{
                                    $options_service_input = 'h';
                                    $action_ = site_url().'/contratacion/?category=hosting';
                                }
                                

                                echo    '<a href="javascript:sendFormCart(\''.$action_.'\',\''.$options_service_input.'\', \''.$cart_item_key.'\',  \''.$_product->get_id().'\', \''.$domain.'\', \''.$group.'\'  );">'.$_product->get_name().'</a>';
                            }else{
                               echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                            }
                        } else {
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                        }

                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                        // Meta data.
                        echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                        // Backorder notification.
                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                        }
                        ?>
                    </td>

                    <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                        <?php
                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
                    </td>
                    <?php if($country == "CO"){ ?>
                        <td class="product-iva" data-title="<?php esc_attr_e( 'IVA', 'woocommerce' ); ?>">
                            <?php echo wc_price($iva); ?>
                        </td>
                    <?php } ?>

                    <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
                        <?php
                        $ID_variacion = $cart_item['variation_id'];
                        $pago_inicial = apply_filters( 'woocommerce_subscriptions_product_sign_up_fee', get_post_meta($ID_variacion, "_subscription_sign_up_fee", true), wc_get_product($ID_variacion));
                        $billing_period      = WC_Subscriptions_Product::get_period( $ID_variacion );
                        $billing_interval = WC_Subscriptions_Product::get_interval( $ID_variacion );
                        if($country == "CO"){
                            $precio_iva = $_product->get_price() + $iva;
                        }
                        else{
                            $precio_iva = $_product->get_price();
                        }

                        if($pago_inicial != 0){
                            if($country == "CO") {
                                if ($iva != 0) {
                                    $iva_pago_inicial = ($pago_inicial * $base_iva) / 100;
                                    $pago_inicial = $pago_inicial + $iva_pago_inicial;

                                    $iva_precio = ($_product->get_price() * $base_iva) / 100;
                                    $precio_iva = $_product->get_price() + $iva_precio;
                                } else {
                                    if ($iva != 0) {
                                        $iva_pago_inicial = ($pago_inicial * $base_iva) / 100;
                                        $pago_inicial = $pago_inicial + $iva_pago_inicial;

                                        $iva_precio = ($_product->get_price() * $base_iva) / 100;
                                        $precio_iva = $_product->get_price() + $iva_precio;
                                    }
                                }
                            }
                            $subscription_string = '<span class="subscription-details">' . sprintf(
                                    __( 'every %s', 'woocommerce-subscriptions' ),
                                    wcs_get_subscription_period_strings( $billing_interval, $billing_period )
                                );
                            $subscription_string = sprintf( __( '%1$s and a %2$s sign-up fee', 'woocommerce-subscriptions' ), $subscription_string, wc_price($pago_inicial ));
                        }
                        else{
                            if($ID_variacion == 0){
                                $subscription_string = "";
                            }
                            else{
                                if(empty($billing_period)){
                                    $subscription_string = "";
                                }
                                else {
                                    $subscription_string = '<span class="subscription-details">' . sprintf(
                                            __('every %s', 'woocommerce-subscriptions'),
                                            wcs_get_subscription_period_strings($billing_interval, $billing_period)
                                        );
                                    $subscription_string = sprintf(__('%1$s', 'woocommerce-subscriptions'), $subscription_string);
                                }
                            }

                        }

                        echo wc_price($precio_iva)." ".$subscription_string;
                        //echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                        ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>

        <?php do_action( 'woocommerce_cart_contents' ); ?>

        <tr>
            <td colspan="6" class="actions">

                <?php if ( wc_coupons_enabled() ) { ?>
                    <div class="coupon">
                        <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
                        <?php do_action( 'woocommerce_cart_coupon' ); ?>
                    </div>
                <?php } ?>

                <!-- <button type="submit" class="button" name="update_cart" value="<?php //esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php //esc_html_e( 'Update cart', 'woocommerce' ); ?></button>-->

                <?php do_action( 'woocommerce_cart_actions' ); ?>

                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </td>
        </tr>

        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
        </tbody>
    </table>
    <?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
    <?php
    /**
     * Cart collaterals hook.
     *
     * @hooked woocommerce_cross_sell_display
     * @hooked woocommerce_cart_totals - 10
     */
    do_action( 'woocommerce_cart_collaterals' );
    ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
