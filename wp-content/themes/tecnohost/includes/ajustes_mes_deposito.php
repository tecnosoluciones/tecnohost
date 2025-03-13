<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 19/05/2021
 * Time: 2:06 PM
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Variable_Order_Pluralize
 */
class Ajustes_mes_deposito extends AutomateWoo\Variable {

    /** @var bool - whether to allow setting a fallback value for this variable  */
    public $use_fallback = false;


    public function load_admin_details() {
        $this->description = __( "Mostrar los ajustes relacionados al mes de deposito.", 'automatewoo');
    }

    /**
     * @param $order WC_Order
     * @param $parameters array
     * @return string
     */
    public function get_value( $order, $parameters ) {

        $items = $order->get_items();
        $html = "";
        $moneda = $order->get_data()['currency'];
        $mes_deposito_activo = 0;
        foreach ( $items as $item_id => $item ) {
            if(wc_get_order_item_meta("$item_id","¿Mes de depósito activo?",true) == "Si"){
                $mes_deposito_activo = 1;
            }
        }

        $total_a_pagar = $order->get_total();

        if($mes_deposito_activo == 1){

            $html .= "<strong>Cobro mes de depósito (" . $moneda . ")</strong>: " . wc_price($total_a_pagar, ['currency' => 'false']);
            $html .= "<br>";
            $total_a_pagar += $total_a_pagar;
        }

        $html .= "<strong>Total a Pagar (".$moneda.")</strong>: ".wc_price($total_a_pagar,['currency' => 'false']);

        return $html;
    }
}

return new Ajustes_mes_deposito();