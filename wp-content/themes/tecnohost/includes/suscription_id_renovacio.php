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
class Get_id_renovacio extends AutomateWoo\Variable {

    /** @var bool - whether to allow setting a fallback value for this variable  */
    public $use_fallback = false;


    public function load_admin_details() {
        $this->description = __( "Muestre el precio del producto en la suscripción y no el precio de la suscripción.", 'automatewoo');

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
        $data = $order->get_data();


        $args = array(
            'post_type'	=> 'shop_order',
            'numberposts' => -1,
            'post_status' => 'wc-pending',
            'meta_query' => array(
                array(
                    'key'		=> '_subscription_renewal',
                    'value'		=> $data['id'],
                    'compare'	=> '=',
                )
            )
        );

        $custom_posts = get_posts( $args );

        foreach ( $custom_posts as $p ){
            $id_renovacio = $p->ID;
            break;
        }

        return $id_renovacio;
    }
}

return new Get_id_renovacio();