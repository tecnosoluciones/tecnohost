<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( isset( $_GET['switch-subscription'] ) && isset( $_GET['item'] ) ) {
    if($_SERVER['REMOTE_ADDR'] == "190.70.131.153"){
        //echo do_shortcode('[elementor-template id="111475"]');
    }
    echo do_shortcode('[elementor-template id="111475"]'); ?>
    <style>
        section#id_titu_mod{
            display:none;
        }

        .woocommerce-variation.single_variation, table.variations {
            padding-left: 10px;
        }

        table.variations select {
            font-size: 18px;
            padding: 7px;
        }

        table.variations label {
            color: #363636;
            font-family: "Source Sans Pro", Sans-serif;
            font-size: 22px;
            font-weight: bold;
        }

        span.price,
        .woocommerce-variation-price {
            color: #47932e;
            font-weight: bold;
            font-size: 18px;
        }

        div#switc-pro-conte section.elementor-section:first-child {
            display: none;
        }
    </style>
    <div id="loader" wfd-invisible="true">
        <div class="spinner">
            <div class="loaders l1"></div>
            <div class="loaders l2"></div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(window).on( "load", function() {
            let contene_dominios = jQuery(".product_cat-dominios");
            jQuery(".elementor-111475").insertAfter(jQuery(".elementor-location-header"));
            contene_dominios.children().find("section").each(function(e,q){
                if(parseInt(e) == 0 || parseInt(e) == 1){
                    jQuery(q).remove()
                }
            });
            jQuery("section#cam_dominio").removeAttr("style");
            jQuery(".woocommerce-notices-wrapper").attr("id","switc-pro");
            contene_dominios.attr("id","switc-pro-conte");
            dominio_actual();
            obtener_variation();
            jQuery("#loader").hide();
            jQuery("body").on("change","select#pa_periodicidad", function(){
                dom_actual = jQuery(this).attr("dom-actual");
                if(dom_actual == jQuery(this).val()){
                    alert("Estimado cliente actualmente usted paga con está opción");
                    jQuery("button.single_add_to_cart_button.button.alt").attr("disabled","disabled");
                    jQuery("button.single_add_to_cart_button.button.alt.camb_bt").hide();
                }
                else{
                    jQuery("button.single_add_to_cart_button.button.alt").removeAttr("disabled");
                    jQuery("button.single_add_to_cart_button.button.alt.camb_bt").show();
                }
            });

            let btn_atras = "<a id='btn_atras_cam' href='javascript:history.back()'><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d=\"M223.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L319.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L393.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34zm-192 34l136 136c9.4 9.4 24.6 9.4 33.9 0l22.6-22.6c9.4-9.4 9.4-24.6 0-33.9L127.9 256l96.4-96.4c9.4-9.4 9.4-24.6 0-33.9L201.7 103c-9.4-9.4-24.6-9.4-33.9 0l-136 136c-9.5 9.4-9.5 24.6-.1 34z\"/></svg> Regresar a la opción anterior</a>";
            jQuery("div#switc-pro-conte").append(btn_atras)
        });
    </script>
<?php }

?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php
	do_action( 'woocommerce_before_add_to_cart_quantity' );

	woocommerce_quantity_input(
		array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		)
	);

	do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>

	<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
