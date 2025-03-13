<?php
/**
 * Subscription details table
 *
 * @author  Prospress
 * @package WooCommerce_Subscription/Templates
 * @since 2.6.0
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<table class="shop_table order_details">
    <thead>
    <tr>
        <?php if ( $allow_item_removal ) : ?>
            <th class="product-remove" style="width: 3em;">&nbsp;</th>
        <?php endif; ?>
        <th class="product-name"><?php echo esc_html_x( 'Product', 'table headings in notification email', 'woocommerce-subscriptions' ); ?></th>
        <th class="product-total"><?php echo esc_html_x( 'Total', 'table heading', 'woocommerce-subscriptions' ); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ( $subscription->get_items() as $item_id => $item ) {
        $producto = $item;
        $_product  = apply_filters( 'woocommerce_subscriptions_order_item_product', $subscription->get_product_from_item( $item ), $item );
        if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
            ?>
            <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $subscription ) ); ?>">
                <?php if ( $allow_item_removal ) : ?>
                    <td class="remove_item">
                        <?php if ( wcs_can_item_be_removed( $item, $subscription ) ) : ?>
                            <?php $confirm_notice = apply_filters( 'woocommerce_subscriptions_order_item_remove_confirmation_text', __( 'Are you sure you want remove this item from your subscription?', 'woocommerce-subscriptions' ), $item, $_product, $subscription );?>
                            <a href="<?php echo esc_url( WCS_Remove_Item::get_remove_url( $subscription->get_id(), $item_id ) );?>" class="remove" onclick="return confirm('<?php printf( esc_html( $confirm_notice ) ); ?>');">&times;</a>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td class="product-name">
                    <?php
                    if ( $_product && ! $_product->is_visible() ) {
                        echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item['name'], $item, false ) );
                    } else {
                        echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item, false ) );
                    }

                    echo wp_kses_post( apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item ) );

                    /**
                     * Allow other plugins to add additional product information here.
                     *
                     * @param int $item_id The subscription line item ID.
                     * @param WC_Order_Item|array $item The subscription line item.
                     * @param WC_Subscription $subscription The subscription.
                     * @param bool $plain_text Wether the item meta is being generated in a plain text context.
                     */
                    do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $subscription, false );

                    //wcs_display_item_meta( $item, $subscription );

                    /**
                     * Allow other plugins to add additional product information here.
                     *
                     * @param int $item_id The subscription line item ID.
                     * @param WC_Order_Item|array $item The subscription line item.
                     * @param WC_Subscription $subscription The subscription.
                     * @param bool $plain_text Wether the item meta is being generated in a plain text context.
                     */
                    do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $subscription, false );
                    ?>
                </td>
                <td class="product-total">
                    <?php echo wp_kses_post( $subscription->get_formatted_line_subtotal( $item ) ); ?>
                </td>
            </tr>
            <?php
        }

        if ( $subscription->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
            ?>
            <tr class="product-purchase-note">
                <td colspan="3"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
    <tfoot>
    <?php
    foreach ( $totals as $key => $total ) : ?>
        <tr>
            <th scope="row" <?php echo ( $allow_item_removal ) ? 'colspan="2"' : ''; ?>><?php echo esc_html( $total['label'] ); ?></th>
            <td><?php echo wp_kses_post( $total['value'] ); ?></td>
        </tr>
    <?php endforeach; ?>
    </tfoot>
</table>

<?php
$mos_dat = 0;
if($subscription->get_status() == "active"){
    $mos_dat = 1;
}

?>

<h2>Datos de la Suscripción</h2>

<table class="">
    <tbody>
    <?php
    $currency = get_post_meta($subscription->get_id(),"_order_currency", true);
    foreach($producto->get_meta_data() AS $key => $value){

        if($value->get_data()['key'] == "Clave"){
            if($mos_dat == 1){
                echo '
                    <tr>
                        <td width="30%"><strong>'.$value->get_data()['key'].'</strong></td>
                        <td width="70%">
                            <p id="bloquear" style="display: none !important;">'.$value->get_data()['value'].'</p>
                            <p id="ocultar">*****</p>
                            <button id="desbloq_clav" estado-data="ocultar"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                ';
            }
        }
        else if($value->get_data()['key'] == "Clave TecnoCloud"){
            echo '
                    <tr>
                        <td width="30%"><strong>'.$value->get_data()['key'].'</strong></td>
                        <td width="70%">
                            <p id="bloquear" style="display: none !important;">'.$value->get_data()['value'].'</p>
                            <p id="ocultar">*****</p>
                            <button id="desbloq_clav" estado-data="ocultar"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                ';
        }
        else{
            if ($mos_dat == 1) {
                if ($value->get_data()['key'] == "Usuario" || $value->get_data()['key'] == "IP" || $value->get_data()['key'] == "DNS1" || $value->get_data()['key'] == "Usuario TecnoCloud"
                    || $value->get_data()['key'] == "Enlace a TecnoCloud"
                    || $value->get_data()['key'] == "DNS2" || $value->get_data()['key'] == "Servidor" || $value->get_data()['key'] == "DNS3"
                    || $value->get_data()['key'] == "DNS4" || $value->get_data()['key'] == "DNS5"
                    || $value->get_data()['key'] == "URL Administración" || $value->get_data()['key'] == "Email" || $value->get_data()['key'] == "¿Se realizó la gestión?"
                    || $value->get_data()['key'] == "Dominio" || $value->get_data()['key'] == "Grupo" || $value->get_data()['key'] == "Nombre del Dominio"
                    || $value->get_data()['key'] == "Selector Dominios" || $value->get_data()['key'] == "Código de Pre-Diseño" || $value->get_data()['key'] == "Selector Dominios"
                ) {
                    if ($mos_dat == 1) {
                        echo '
                        <tr>
                            <td width="30%"><strong>' . $value->get_data()['key'] . '</strong></td>
                            <td width="70%">' . $value->get_data()['value'] . '</td>
                        </tr>
                    ';
                    } else {
                        echo '
                            <tr>
                                <td width="30%"><strong>' . $value->get_data()['key'] . '</strong></td>
                                <td width="70%">' . $value->get_data()['value'] . '</td>
                            </tr>
                        ';
                    }
                }
            }else{
                if ($value->get_data()['key'] == "Grupo"
                    || $value->get_data()['key'] == "Nombre del Dominio"
                    || $value->get_data()['key'] == "Usuario TecnoCloud"
                    || $value->get_data()['key'] == "Enlace a TecnoCloud"
                    || $value->get_data()['key'] == "Selector Dominios" || $value->get_data()['key'] == "Dominio" || $value->get_data()['key'] == "Código de Pre-Diseño" || $value->get_data()['key'] == "Selector Dominios") {
                    echo '
                        <tr>
                            <td width="30%"><strong>' . $value->get_data()['key'] . '</strong></td>
                            <td width="70%">' . $value->get_data()['value'] . '</td>
                        </tr>
                    ';
                }
                else if($value->get_data()['key'] == "Clave TecnoCloud"){
                    echo '
                    <tr>
                        <td width="30%"><strong>'.$value->get_data()['key'].'</strong></td>
                        <td width="70%">
                            <p id="bloquear" style="display: none !important;">'.$value->get_data()['value'].'</p>
                            <p id="ocultar">*****</p>
                            <button id="desbloq_clav" estado-data="ocultar"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                ';
                }
            }

        }
    }
    $val_cate = wc_get_object_terms($_product->get_id(), 'product_cat', "term_id");
    if(count($val_cate) == 0){
        $val_cate = wc_get_object_terms($_product->get_parent_id(), 'product_cat', "term_id");
    }
    if (strpos($_product->name, '.ve') !== false || $val_cate[0] == 447) {?>
        <script type="text/javascript">
            jQuery(window).on( "load", function() {
                jQuery("a.wcs-switch-link.button").hide();
                jQuery("#loader").hide();
            });
        </script>
    <?php } ?>
    <input type="hidden" name="moneda" id="moneda" value="<?php echo $currency ?>">
    </tbody>
</table>
<div id="loader">
    <div class="spinner">
        <div class="loaders l1"></div>
        <div class="loaders l2"></div>
    </div>
</div>
<script>
    jQuery(window).on( "load", function() {
        validar_moneda_orden(jQuery("#moneda").val(),jQuery('.wmc-select-currency-js').val());
    });

    jQuery("button#desbloq_clav").click(function(){
        if(jQuery(this).attr("estado-data")=="ocultar"){
            jQuery("p#bloquear").removeAttr("style");
            jQuery("p#ocultar").attr("style", "display: none !important;");
            jQuery(this).attr("estado-data", "mostrar");
        }
        else {
            jQuery("p#bloquear").attr("style", "display: none !important;");
            jQuery("p#ocultar").removeAttr("style");
            jQuery(this).attr("estado-data", "ocultar");
        }
    });
</script>
