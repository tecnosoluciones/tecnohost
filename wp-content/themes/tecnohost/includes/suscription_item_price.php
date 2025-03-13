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
class Get_price_item extends AutomateWoo\Variable {

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
        $items = $order->get_items();
        $addons = $this->obtener_adicioonales_vps($items);
        $addons_prices = $this->obtener_adicioonales_vps_prices($addons);
        $data_order = $order->get_data();
        $impuesto = $data_order['tax_lines'];
        if(count($impuesto) != 0){
            foreach($impuesto AS $key => $value){
                $impuesto = $value->get_data()['rate_percent'];
                break;
            }
        }

        $total_pagar = $data_order['total'];
        $total_pagar = number_format($total_pagar,wc_get_price_decimals(),wc_get_price_decimal_separator(),wc_get_price_thousand_separator());

        return $total_pagar;
    }

    public function obtener_adicioonales_vps($items){
        $metas = [];
        foreach ( $items as $item ) {
            foreach($item->get_meta_data() as $key => $value) {
                switch (explode(" (USD", $value->key)[0]) {
                    case"Hostname":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Dominio":
                    case"Nombre del Dominio":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Prefijo NS1":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Prefijo NS2":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Sistema operativo":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Panel de Hosting WHM/cPanel":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Servidor Web LiteSpeed":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Filtro de SpamExperts":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Instalador de Aplicaciones Adicional":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Gestor de Temas Gráficos para cPanel":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Software de Soporte y Facturación":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Microsoft SQL Web":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Certificado SSL Adicional":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Monitor de URL":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Dirección IP Adicional":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Ancho de Banda Adicional - 1TB":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Gestión de Servicios":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Espacio en Disco (VPS) - 10 GB":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Sistema Operativo (Mensual)":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Respaldo Adicional (Mensual)":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Servidor Web LiteSpeed (Mensual)":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Panel de Hosting WHM/cPanel (Mensual)":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Memoria RAM Adicional (Mensual)":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Gestión de Servicios (Mensual)":
                        $metas[$value->key] = $value->value;
                        break;
                    case"Certificado SSL Adicional (Mensual)":
                        $metas[$value->key] = $value->value;
                        break;
                }
            }
        }

        return $metas;
    }

    public function obtener_adicioonales_vps_prices($addons){
        $precio_addons = 0;
        foreach($addons AS $key => $value){
            // Buscar el precio en el formato dentro de paréntesis
            $patron = '/\((?:\D*\s*(\d+(?:[\.,]\d+)?)[^\)]*\s*)\)/';
            preg_match_all($patron, $key, $matches);
            
            // Obtener el precio del primer elemento coincidente
            $precio = isset($matches[1][0]) ? $matches[1][0] : 0; // Asegurarse de que $precio tenga un valor
    
            // Reemplazar coma por punto en el precio para asegurar el formato correcto
            $precio = str_replace(",", ".", $precio);
    
            // Verificar si el precio es un valor numérico antes de sumarlo
            if (is_numeric($precio)) {
                // Convertir el precio a un número flotante y sumarlo a precio_addons
                $precio_addons += floatval($precio);
            }
        }
    
        return $precio_addons;
    }
}

return new Get_price_item();