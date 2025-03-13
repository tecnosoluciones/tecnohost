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
class Mensajes_ajustes_mes extends AutomateWoo\Variable {

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
        $id_suscripcion = $order->get_id();
        $moneda = $order->get_data()['currency'];
        $cambio_moneda = get_post_meta($order->get_id(), "wmc_order_info", true);
        $mes_pedido = date_format($order->get_data()['date_modified'],"m");
        $dia_pedido = date_format($order->get_data()['date_modified'],"d");
        $mes_control = $mes_pedido-1;
        $dia_control = $dia_pedido-1;
        $cambio_moneda = $cambio_moneda[$moneda]['rate'];

        foreach ($order->get_items() as $item_id => $item) {
            if ($item->get_variation_id() == 0) {
                $product_id = $item->get_product_id();
            } else {
                $product_id = $item->get_variation_id();
            }

            $_product = wc_get_product($product_id);


            $fecha_actuali_product = $_product->get_data()['date_modified'];

            $new_prices = $_product->get_price();

            $old_prices = $item->get_total();
            $new_prices = $new_prices * $cambio_moneda;
        }

        if($old_prices != $new_prices){
            $dif_precio = 1;
        }
        else{
            $dif_precio = 0;
        }

        $arg = [
            'post_type' => 'shop_order',
            'posts_per_page' => 1,
            'post_status' => 'wc-activar-deposito',
            'meta_query' => [
                [
                    'key' => '_subscription_renewal',
                    'value' => $id_suscripcion,
                    'compare' => '='
                ]
            ],
            'date_query' => [
                [
                    'column' => 'post_modified_gmt',
                    'before' => '0 month ago'
                ]
            ]
        ];

        $query  = new WP_Query($arg);

        $html = "";
        if($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
                $id_orden_renovacion = get_the_ID();
                $fecha_creaci_renovac = wc_get_order($id_orden_renovacion)->get_date_modified();
                $fecha_activaci = get_the_date("Y-m-d");
                $fecha_inicio_activa_mes = new DateTime($fecha_activaci);
                $diferencia_mes_deposito = $fecha_inicio_activa_mes->diff($fecha_creaci_renovac);

                if ($diferencia_mes_deposito->m <= 1) {
                    $html = ". Dicho pago comprende una Mensualidad, más un Mes de Depósito";
                }

                if($fecha_inicio_activa_mes->diff($fecha_actuali_product)->m == 0){
                    if($dif_precio == 1){
                        $html .= ", más el complemento del mes de depósito por el aumento de nuestras tarifas";
                    }
                }

            endwhile;
        }
        else{
           /* imprimir(count(wc_get_order($id_suscripcion)->get_fees()));
            if (count(wc_get_order($id_suscripcion)->get_fees()) != 0) {

            }*/
        }
        return $html;
    }
}

return new Mensajes_ajustes_mes();