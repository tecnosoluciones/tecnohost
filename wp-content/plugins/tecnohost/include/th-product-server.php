<?php
add_action('wp_ajax_nopriv_check_domain_server','check_domain_server');
add_action('wp_ajax_check_domain_server','check_domain_server');


function get_product_domain($tls){
    global $wpdb, $TWhois;

    $query = "SELECT  ".$wpdb->prefix."posts.* 
FROM ".$wpdb->prefix."posts 
WHERE ( ".$wpdb->prefix."posts.post_title REGEXP '.com$' )
AND ( ".$wpdb->prefix."posts.post_type = 'product') 
AND  ".$wpdb->prefix."posts.post_status = 'publish' 
GROUP BY  ".$wpdb->prefix."posts.ID 
ORDER BY  ".$wpdb->prefix."posts.post_date DESC";

$resultfirst = $wpdb->get_results($query);
$product = false;
$html = '<span>Continúe con este dominio para</span>';

if($resultfirst){
    foreach( $resultfirst as $result ){
        $product = wc_get_product( $result->ID );
    }
    
    $variations = $product->get_available_variations();
    $variations_html = '';
    foreach($variations as $key =>$variation){
        
        $variations_html .= "data-".$variation['variation_id']."='".$variation['price_html']."' ";
        
    }
    $html .= '&nbsp;<select name="interval" class="domain-period-select custom-select" >
                                                           ' . woo_display_variation_dropdown_on_shop_page($product->get_id(), "periodicidad", 5) . '
                                                          </select>';
                                                          
    $html .='&nbsp; <span class="dynamic-price" '.$variations_html.'>'.wc_price($product->get_price()).'</span>';    
    
    $html_buttom ='<button type="submit" class="btn-success-domain single_add_to_cart_button">
                                    Continuar &nbsp;<i class="fas fa-arrow-circle-right"></i>
                                </button>';
                                                          
    return "<div class='container-result-whois'>".$html."</div>".$html_buttom;
                    
}
return false;
}

function check_domain_server(){
     global $TWhois;
     
     if($_POST['domain']){
        
        $result = [];
        $domain = sanitize_text_field($_POST['domain']);
        $tls = sanitize_text_field($_POST['tls']);
        $domainoption = sanitize_text_field($_POST['domainoption']);
        
        
        $domain_full = $domain.$tls;
             switch($domainoption){
                 
                 case '0':
                     if(!$TWhois->viewWhois($domain_full)){
                          $result['message'] = '<div>
                                        <span class="domain-failed-message"><strong>'.$domain_full.'</strong> no elegible para transferencia.</span>
                                        <span class="domain-message-details">El dominio que ingresó no parece estar registrado.
                                            Si el dominio se registró recientemente, es posible que deba volver a intentarlo más tarde.
                                            Alternativamente, puede realizar una búsqueda para registrar este dominio.</span>
                                    </div>';
                     }else{
                         $result['message'] = '<span class="domain-success-message">Su dominio <strong>'.$domain_full.'</strong> es elegible para transferencia.</span>'.get_product_domain($tls);
                     }
                     break;
                     
                case '1':
                     if(!$TWhois->viewWhois($domain_full)){
                         $result['message'] = '<span class="domain-success-message">¡Felicidades! <strong>'.$domain_full.'</strong> está disponible.</span>'.get_product_domain($tls);
                     }else{
                         $result['message'] = '<span class="domain-failed-message"><strong>'.$domain_full.'</strong> no está disponible.</span>';
                     }
                     break;
                     
                case '2':
                     if(!$TWhois->viewWhois($domain_full)){
                          $result['message'] = '<div>
                                        <span class="domain-failed-message"><strong>'.$domain_full.'</strong> no registrado.</span>
                                        <span class="domain-message-details">El dominio que ingresó no parece estar registrado.
                                            Si el dominio se registró recientemente, es posible que deba volver a intentarlo más tarde.
                                            Alternativamente, puede realizar una búsqueda para registrar este dominio.</span>
                                    </div>';
                     }else{
                         $result['message'] = '<span class="domain-success-message">Puede usar su dominio <strong>'.$domain_full.'</strong> .</span>
                         <button type="submit" class="btn-success-domain single_add_to_cart_button">
                                    Continuar &nbsp;<i class="fas fa-arrow-circle-right"></i>';
                     }
                     break;
                     
                     default:
                          $result['message'] = '<span class="domain-failed-message"><strong>'.$domain_full.'</strong> no está disponible.</span>';
                     break;
             }
        
          return wp_send_json_success($result);

     }

    wp_die();
}

function validar_campos_switch_vps($suscription_id){
    $subscription = wc_get_order($suscription_id);
    $metas = [];
    foreach ( $subscription->get_items() as $item_id => $item ) {
        $producto = $item;
        break;
    }

    foreach($producto->get_meta_data() AS $key => $value){
        switch(explode(" (USD",$value->get_data()['key'])[0]){
            case"Hostname":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Dominio":
            case"Nombre del Dominio":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"pa_plan":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Prefijo NS1":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Prefijo NS2":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Sistema operativo":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Panel de Hosting WHM/cPanel":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Servidor Web LiteSpeed":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Filtro de SpamExperts":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Instalador de Aplicaciones Adicional":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Gestor de Temas Gráficos para cPanel":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Software de Soporte y Facturación":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Microsoft SQL Web":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Certificado SSL Adicional":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Monitor de URL":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Dirección IP Adicional":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Ancho de Banda Adicional - 1TB":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Gestión de Servicios":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Espacio en Disco (VPS) - 10 GB":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Sistema Operativo (Mensual)":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Respaldo Adicional (Mensual)":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Servidor Web LiteSpeed (Mensual)":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Panel de Hosting WHM/cPanel (Mensual)":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Memoria RAM Adicional (Mensual)":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Gestión de Servicios (Mensual)":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
            case"Certificado SSL Adicional (Mensual)":
                $metas[explode(" (USD",$value->get_data()['key'])[0]] = $value->get_data()['value'];
            break;
        }
    }

    return $metas;
}
