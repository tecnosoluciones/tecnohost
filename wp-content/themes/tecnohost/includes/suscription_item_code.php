<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 19/05/2021
 * Time: 12:37 PM
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Variable_Order_Pluralize
 */
class Get_code_item extends AutomateWoo\Variable {

    /** @var bool - whether to allow setting a fallback value for this variable  */
    public $use_fallback = false;


    public function load_admin_details() {
        $this->description = __( "Muestre el nombre del servicio contratado con esta variable.", 'automatewoo');

        // setting parameters fields is optional
        //$this->add_parameter_text_field( 'single', __( 'Used when there is one item purchased.', 'your-text-domain' ) );
        //$this->add_parameter_text_field( 'plural', __( 'Used when there are more than one item purchased.', 'your-text-domain' ) );
    }

    /**
     * @param $order WC_Order
     * @param $parameters array
     * @return string
     */
    public function get_value( $order, $parameters ) {
        // Inicializa la variable para almacenar el SKU
        $code = null;
        
        foreach ($order->get_items() as $item_id => $item) {
            // Obtén el ID del producto o variación
            $product_id = $item->get_data()['product_id'];
            
            // Verifica si el producto es una variación
            if ($item->get_data()['variation_id']) {
                // Si es una variación, obtenemos el SKU de la variación
                $product = wc_get_product($item->get_data()['variation_id']);
                $code = $product ? $product->get_sku() : null;
            } else {
                // Si no es una variación, obtenemos el SKU del producto simple
                $product = wc_get_product($product_id);
                $code = $product ? $product->get_sku() : null;
            }
        }
    
        return $code;
    }
}

return new Get_code_item ();