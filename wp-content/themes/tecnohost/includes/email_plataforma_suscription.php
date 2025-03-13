<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 23/06/2021
 * Time: 4:16 PM
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Variable_Order_Pluralize
 */
class Get_email_plataforma extends AutomateWoo\Variable {

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

        foreach($order->get_items() AS $key => $value){
            foreach ($value->get_meta_data() AS $key_meta => $value_meta){
                if($value_meta->get_data()['key'] == "Email"){
                    $url_administracion = $value_meta->get_data()['value'];
                    break(2);
                }
            }
        }

        return $url_administracion;
    }
}

return new Get_email_plataforma();