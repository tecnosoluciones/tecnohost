<?php


/* Registro de Widget buscador de dominio */
add_action( 'widgets_init', function(){
    register_widget( 'TecnoWhois' );
});


function ts_whois_js(){

    $cart_page_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();
    $dependencies = array( 'jquery' );

    $script_params['ajaxLoaderImage'] = WC()->plugin_url() . '/assets/images/select2-spinner.gif';
    $script_params['ajaxUrl']         = admin_url( 'admin-ajax.php' );
    $script_params['url_carrito'] = $cart_page_url;
    $script_params['siteurl'] = get_option('siteurl');
    
    wp_register_script('custom-script', plugins_url('/js/main.js',__FILE__), array('jquery'), 1.2, true);
    wp_enqueue_script('custom-script');
    wp_localize_script('custom-script', 'WPURLS', $script_params);

}

add_action("wp_enqueue_scripts", "ts_whois_js");




function custom_styles() {
    wp_enqueue_style( 'custom-style',
        plugins_url( '/css/style.css',__FILE__) ,
        array()
    );
}

add_action( 'wp_enqueue_scripts', 'custom_styles');

add_action( 'admin_enqueue_scripts', 'custom_styles_admin');

function custom_styles_admin() {
  wp_enqueue_style( 'admin-style',  plugins_url( '/css/admin.css',__FILE__), array());
}

//unset($_SESSION['options_service_input']);
//unset($_SESSION['whois_domain']);
//die;

add_action('wp_ajax_nopriv_iniciarl_proceso','iniciarl_proceso');
add_action('wp_ajax_iniciarl_proceso','iniciarl_proceso');
function iniciarl_proceso(){
    $datos_dominios = json_decode(stripslashes($_POST['datos']), true);
    foreach($datos_dominios AS $key => $value){
        $product_id = $value['product_id'];
        $variation_id = $value['variation_id'];
        
        foreach($value['cart_item_data']['addons'] AS $key_ => $cart_item_data){
            $meta['addons'][$key_] =  [
                'name' => $cart_item_data['name'],
                'value' => $cart_item_data['value'],
                'field_name' => $cart_item_data['field_name']

            ];
        }
        /*$meta = [
            'addons' => [[
                'name' => $value['cart_item_data']['addons'][0]['name'],
                'value' => $value['cart_item_data']['addons'][0]['value'],
                'field_name' => $value['cart_item_data']['addons'][0]['field_name']

            ]]
        ];*/

        WC()->cart->add_to_cart( $product_id,  1, $variation_id,"",$meta);
    }
    wp_die();
}


/* Registro de shortcode para la contratacion */




function contratacion() {

    global $TWhois, $THosting;


    if ((isset($_POST['whois_domain']) and isset($_POST['options_service_input'])) or (THSession::hasSession('whois_domain') and isset($_POST['wcj-currency'])) or (THSession::hasSession('organi') and isset($_POST['wcj-currency'])) ){


        $options_service_input = setOption('options_service_input');


        switch ($options_service_input){

            /**
             * Si selecciona solo dominios cargará la funcionalidad del whois y lista los servicios con la categoría "dominios"
             */
            case 'a':

                domains_help();

                break;

            case 'd':
                   /* $whois_domain = $_POST['whois_domain'];
                   $options_service_input = $_POST['options_service_input'];
                   $mode_domain = $_POST['mode_domain'];*/
              domains();
                break;

            case 'h':

                hosting();

                break;
            case 'dyh':

                if(THSession::hasSession('step_combo') and THSession::getSession('step_combo')!=1 and !isset($_POST['mode_domain'])){
                    hosting();
                }else{
                   /*$whois_domain = $_POST['whois_domain'];
                   $options_service_input = $_POST['options_service_input'];
                   $mode_domain = $_POST['mode_domain'];
                  */
                    domains('combo');
                }
                break;

            case 'dys':
                domains('combo_ser');
                break;

            case 'ssl':
            case 'redirect':

                otherServices();
                break;
            default:
                /**
                 * De no seleccionar una opción válida
                 */
                echo __('Debe seleccionar un servicio y un dominio');
                break;
        }



    }else{

        if (isset($_GET['category'])){

            switch (strtolower(trim(addslashes($_GET['category'])))){

                case 'registro':

                    breadcrumbs('Registro de Dominios');
                    $info = 'Por favor introduzca el <b>Nombre de Dominio</b> deseado para buscar dicho dominio por todas las extensiones posibles.Le recomendamos leer los Tips Importantes abajo para mayor información.';

                   echo form_domain_search('d','domain-form',true,'Introduzca el Nombre de Dominio deseado sin la extensión. Ej. sin el .com',$info,'r');

//                   echo tecnoFAQ(64);

                    break;

                case 'transferencia':

                    breadcrumbs('Transferencia de Dominios');
                    $info = 'Por favor introduzca el <b>Nombre de Dominio</b> que desea transferir y luego haga clic en el ícono de la lupa para buscar dicho dominio. Le recomendamos leer los Tips Importantes para mayor información.';

                    echo form_domain_search('d','domain-form',true,'Introduzca el Nombre de Dominio deseado sin la extensión. Ej. sin el .com',$info,'t');

//                    echo tecnoFAQ(64);

                    break;

                case 'hosting':
                case 'hosting-directadmin':
                case 'hosting-cpanel':

                    breadcrumbs('Planes de Hospedajes');

                    $info = 'Por favor coloque abajo el <b>Nombre del Dominio</b> que desea asociar al plan de hospedaje deseado y seleccione la extensión correspondiente.';

                    echo form_domain_search_ex('h','domain-form',true,'Nombre de Dominio',$info);

//                    echo tecnoFAQ(66);

                    break;

                case 'combo':

                    breadcrumbs('Combo de Hospedaje + Dominio');


                    $info = 'Por favor introduzca el <b>Nombre de Dominio</b> deseado y luego haga clic en el ícono de la lupa "Buscar" para buscar dicho dominio por todas las extensiones posibles. Le recomendamos leer los Tips Importantes abajo para mayor información.';

                    echo form_domain_search('dyh','domain-form','dominio-hosting','Introduzca el Nombre de Dominio deseado sin la extensión. Ej. sin el .com',$info,'c');

//                    echo tecnoFAQ(66);

                    break;

                case 'ayudante':

                    breadcrumbs('Ayudante de Nombres');


                    $info = 'Bienvenido al Ayudante de <b>Nombres de Dominio</b>, esta herramienta funciona combinando palabras 
                    descriptivas asociadas con la misma, a su vez el sistema comprobará de forma automática la disponibilidad 
                    del dominio o dominios y finalmente creará un informe. <br>
                    Por favor, tenga en cuenta que éste proceso puede tardar hasta un minuto para completarse en función de 
                    la velocidad actual de Internet o de los servidores consultados. Para que el Ayudante de <b>Nombres de Dominio</b>
                     pueda efectuar la búsqueda, es necesario completar lo más detalladamente posible el siguiente formulario.';

                    echo form_domain_search_help_name('a','domain-form','ayudante-domain-form',$info);


//                    echo tecnoFAQ(64);

                    break;

                case 'certificados':

                    breadcrumbs('Certificados de Seguridad');

                    $info = '';
                    echo form_domain_search_ex('ssl','domain-form','certificado-ssl-form','Nombre de Dominio',$info);

                    break;

                case 'redireccion':

                    breadcrumbs('Redireccionamientos');

                    $info = '';
                    echo form_domain_redirect('redirect','domain-form redirect-form','redirect-form','Escriba su Nombre de dominio con extensión. Ej. conel.com',$info);

                    break;

                default:

                    echo showCategories();

                    break;
            }


        }else{
            echo showCategories();
        }

    }
    navegatorCart();
}
add_shortcode('contratacion', 'contratacion');



function stepContratacion($atts){


    $attr = shortcode_atts( array ('paso'), $atts );
     $current_step = 1;

    $url = $_SERVER['REQUEST_URI'];
    
    $en_carrito = false;

     foreach(WC()->cart->get_cart() as $cart_item_key => $values ) {

    if ( has_term( '1. Planes', 'product_cat', $values['product_id'] ) ) {
        $en_carrito = true;
    }
        
 }

    $http_refer = $_SERVER['HTTP_REFERER'];
    if(preg_match('/producto/', $http_refer) or ($en_carrito and (isset($_GET['category']) and $_GET['category']=='combo')) or $en_carrito){
        $with = 12.5;
        $cantidad_pasos = 8;
        $steps = array(
        1=>'Selección del Plan',
        2=>'Selección del Dominio',
        3=>'Selección del Hosting',
        4=>'Confirmación',
        5=>'Ingresar',
        6=>'Pedido',
        7=>'Pagar',
        8=>'Finalizar'
        );
        if(isset($_POST['options_service_input']) and $_POST['options_service_input']== 'h') $current_step = 3;
            else 
            $current_step = 2;
        
        if($atts['paso']>=2){
            $current_step = $atts['paso']+1;
        }
        
    }elseif(!preg_match('/producto/', $http_refer) and $_GET['category']=='combo'){
        $with = 14.28;
        $cantidad_pasos = 7;
        $steps = array(
        1=>'Selección del Dominio',
        2=>'Selección del Hosting',
        3=>'Confirmación',
        4=>'Ingresar',
        5=>'Pedido',
        6=>'Pagar',
        7=>'Finalizar'
        );
        
        if($atts['paso']>=2){
            $current_step = $atts['paso']+1;
        }

        if(preg_match('/combo/', $http_refer)){
            if(isset($_POST['options_service_input']) and $_POST['options_service_input']== 'h') $current_step = 2;
            else 
            $current_step = 1;
        }
        
        
    }else{
        $with = 16.6666;
        $cantidad_pasos = 6;
        $steps = array(
        1=>'Selección',
        2=>'Confirmación',
        3=>'Ingresar',
        4=>'Pedido',
        5=>'Pagar',
        6=>'Finalizar'
        );
        
        $current_step = $atts['paso'];
    }
   
//conocer paso actual


?>
   <div class="menu-step container-tecnohost">
       <?php
       $style = "style='width: $with%;'";
       //echo $cantidad_pasos;

       foreach($steps as $key=>$step)
       {
            echo '<div '.$style.' class="step '.(($current_step>=$key)?'active':"").'"><h2>'.$step.'</h2><span>'.$key.'</span></div>';
       }
       
       ?>
        <!--    <div class="step active">
                <h2>Selección</h2>
                <span>1</span>
            </div>
              <div class="step <?php if($atts['paso'] >= 2) echo 'active'?>">
                <h2>Confirmación</h2>
                <span>2</span>
            </div>
            <div class="step <?php if($atts['paso'] >= 3) echo 'active'?>">
                <h2>Ingresar</h2>
                <span>3</span>
            </div>
            <div class="step <?php if($atts['paso'] >= 4) echo 'active'?>">
                <h2>Pedido</h2>
                <span>4</span>
            </div>
           <div class="step <?php if($atts['paso'] >= 5) echo 'active'?>">
                <h2>Pagar</h2>
                <span>5</span>
            </div>
            <div class="step <?php if($atts['paso'] >= 6) echo 'active'?>">
                <h2>Finalizar</h2>
                <span>6</span>
            </div>
            -->
   </div>

<?php

}
add_shortcode('pasos-contratacion', 'stepContratacion');


function tecnohost_mini_cart(){
    //woocommerce_mini_cart();
    //$mini_cart = woocommerce_mini_cart();// ob_get_clean();
    return woocommerce_mini_cart();
}
add_shortcode('mini-carrito', 'tecnohost_mini_cart');

add_filter('query_vars', 'my_register_query_vars' );
function my_register_query_vars( $qvars ){
    //Add query variable to $qvars array
    $qvars[] = 'dominio';
    return $qvars;
}
function whois_domain(){
    global $TWhois;

   
   $html_extensions = '';
   $extensions = $TWhois->extensions;
   foreach($extensions as $value){
       $html_extensions.='<option value="'.$value.'">.'.$value.'</option>';
   }
   
   $formulario = '<div id="tsv-whois" class="tsv-whois-widget container-tecnohost">
    <form method="POST">
                   <div class="widget_container">
                    <div class="container-whois">
                        <input id="whois_domain" name="whois_domain" type="text" autocomplete="off" required="" placeholder="Nombre de Dominio">
                        <i id="icon_domain_check" class="fa" aria-hidden="true"></i>    
                    </div>
                    <select name="domain_ex"  class="domain_ex_widget domain_ex extension_whois">'.$html_extensions.'</select>
                </div>
                <label for="" id="whois_domain_message" class="whois_domain_message"></label>
                <button class="button_domain"  type="submit">Buscar &nbsp;<i class="fa fa-search"></i>  </button>
            </form>
             </div>';
   echo $formulario;
   
   if(isset($_POST['whois_domain'])){
       $dominio = $_POST['whois_domain'].'.'.$_POST['domain_ex'];
   }else{
       $dominio = (get_query_var('dominio')) ? get_query_var('dominio') : false;
   }
    if($dominio){
        $respuesta = $TWhois->viewWhois($dominio);
        if($respuesta)
            echo "<pre>".$respuesta."</pre>";
        else
            echo "<h2>No se ha podido encontrar información del dominio <strong>$dominio</strong></h2>";  
    }
}
add_shortcode('whois-domain', 'whois_domain');

/*API REST TECNOHOST*/

add_action( 'rest_api_init', function () {
  register_rest_route( 'tecnohost/v1', '/add-to-cart', array(
    'methods' => 'POST',
    'callback' => 'tecnohost_add_to_cart',
  ) );
} );

function tecnohost_add_to_cart($data){
     defined( 'WC_ABSPATH' ) || exit;
     
    global $woocommerce;
    WC()->frontend_includes();
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
    WC()->customer = new WC_Customer( get_current_user_id(), true );
    WC()->cart = new WC_Cart();
    
    foreach($data->get_params() as $product){
        $product_id = sanitize_text_field($product['product_id']);
        $woocommerce->cart->add_to_cart($product_id, 1, NULL, NULL,  $product['cart_item_data']);
    }
    
    $response = new WP_REST_Response($data, 200); 
    return $response;
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'tecnohost/v1', '/remove-to-cart', array(
    'methods' => 'DELETE',
    'callback' => 'tecnohost_remove_to_cart',
  ) );
} );

function tecnohost_remove_to_cart($data){
     defined( 'WC_ABSPATH' ) || exit;
     
    global $woocommerce;
    WC()->frontend_includes();
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
    WC()->customer = new WC_Customer( get_current_user_id(), true );
    WC()->cart = new WC_Cart();
    

    $cart_item_key = sanitize_text_field($data['cart_item_key']);
    WC()->cart->remove_cart_item( $cart_item_key );
    
    $response = new WP_REST_Response($data, 200); 
    return $response;
}
