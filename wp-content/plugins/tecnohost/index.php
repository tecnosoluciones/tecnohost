<?php
/*
Plugin Name: TecnoHost
Plugin URI: https://tecnosoluciones.com
Description:
Version: 1.0
Author: TecnoSoluciones de Colombia S.A.S
Author URI: https://tecnosoluciones.com
License: Undefined
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define constants
 */
if ( ! defined( 'WCTH_PLUGIN_VERSION' ) ) {
    define( 'WCTH_PLUGIN_VERSION', '1.0.0' );
}
if ( ! defined( 'WCTH_PLUGIN_DIR_PATH' ) ) {
    define( 'WCTH_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}


require_once WCTH_PLUGIN_DIR_PATH . 'class/class-onlinenic-api.php';
$user_api_onlinenic = get_option('user_api_onlinenic');
$pass_api_onlinenic = get_option('pass_api_onlinenic');
$test_api_onlinenic = (boolean) get_option('test_api_onlinenic');
$api_key_onlinenic = get_option('api_key_onlinenic');

if(empty($user_api_onlinenic) or empty($pass_api_onlinenic) or empty($api_key_onlinenic)) $test_api_onlinenic = true;

$online_nic = new API_Onlinenic($user_api_onlinenic, $pass_api_onlinenic, $api_key_onlinenic, $test_api_onlinenic);



require_once 'loader.php';

require_once WCTH_PLUGIN_DIR_PATH . 'class/class-wcth-admin.php';

/**
 * Start the plugin.
 */
function wcth_init() {
    if ( is_admin() ) {
        $TPWCP = new WCTH_Admin();
        $TPWCP->init();
    }
}
add_action( 'plugins_loaded', 'wcth_init' );


/*---------------*/

/*Tarifas adicionales*/
add_action('woocommerce_cart_calculate_fees', function() {
    global $TWhois;
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

     if(is_cart() or is_checkout()) {
    $moneda = get_woocommerce_currency();
    $array_fees = WC()->cart->get_fees();

 // WC()->cart->add_fee('DESCUENTO', '-157500', true, 'standard');
 // return;

    //VALIDACION DE FEES
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

        $domain = $cart_item["addons"][0]['value'];
        $_product = wc_get_product($cart_item['data']->get_id());
        $t_fee_name = 'Tarifa de transferencia (' . $domain . ')';
        $t_fee_key = 'tarifa-de-transferencia-' . str_replace(array(' ','.'), "-", strtolower($domain));
        $r_fee_name = 'Descuento de Registro 1er A&ntilde;o ('.$domain.')';
        $r_fee_key = 'descuento-de-registro-1er-ao-' . str_replace(array(' ','.'), "-", strtolower($domain));
        $costo_transferencia = get_post_meta($_product->get_parent_id(), 'transferencia_costo_' . $moneda, true);
        $descuento_registro = get_post_meta($_product->get_parent_id(),'registro_costo_'.$moneda, true);


        if (has_term('Dominios', 'product_cat', $_product->get_parent_id())) {


        if(!empty($descuento_registro) or !empty($costo_transferencia)){



        if(is_array($cart_item['subscription_renewal'])) {
        if(!array_key_exists($r_fee_key, $array_fees) AND !array_key_exists($t_fee_key, $array_fees) AND (count($cart_item['subscription_renewal']) == 0)) {


                //Verifica si es para transferir
                $tls = get_post_meta($_product->get_parent_id(), 'domain_tls', true);
                $server = get_post_meta($_product->get_parent_id(), 'server_domain', true);
                $checkPurchaseType = $TWhois->checkPurchaseType($domain, $tls, $server);


                if ($checkPurchaseType == 'transferir') {
                    if (!empty($costo_transferencia)) {
                        if (preg_match('/Dominio/', $_product->get_title()))
                            WC()->cart->add_fee($t_fee_name, $costo_transferencia);
                    }
                } elseif ($checkPurchaseType == 'registrar') {

                    if (!empty($descuento_registro)) {

                        if ($TWhois->viewWhois($domain) == false) {
                            if (preg_match('/Dominio/', $_product->get_title()))
                                WC()->cart->add_fee($r_fee_name, -$descuento_registro);

                        }
                    }
                }
            }
            }
        }
        }


    }
     }
});

function excludeCartFeesTaxes($taxes, $fee, $cart)
{
    return [];
}

//add_action('woocommerce_cart_totals_get_fees_from_cart_taxes', 'excludeCartFeesTaxes', 10 ,3);

add_action('woocommerce_cart_totals_get_fees_from_cart_taxes', function($taxes, $fee, $cart) {
    {
        // IMPORTANT: You must always add the discount fee amount excluding tax
        // For example if your tax rate is 10% and you want add a discount total of $120,
        // your add_fee should be like that: add_fee('Discount', -(120 / 1.10) , true)
        // if tax system enabled and the fee is negative
        if(wc_tax_enabled() && $fee->object->amount < 0) {

            $rates = WC_Tax::get_rates(); // Get Standart Rates
            // OR WC_Tax::get_rates('Your Tax Class Name')

            // Get the array index of the first tax rate in the tax class you selected above
            // (Unfortunately, you can only select one of them)
            $rate_index = array_keys($rates)[0];

            // Get the rate
            $standard_tax_rate = $rates[$rate_index]['rate'];

            // Clear the wrong calculated tax amounts
            $fee->taxes = array();

            // Calculate and set the correct taxes
            $moneda = get_woocommerce_currency();

            if($moneda == "COP"){
                $fee->taxes[$rate_index] = ($fee->object->amount * $standard_tax_rate)/100;
            }
            else{
                $fee->taxes[$rate_index] = $fee->object->amount * $standard_tax_rate;
            }

            // set the fee taxes
            $taxes = $fee->taxes;
        }
        return $taxes;
    }
}, 10 ,3);

//Mostrar whois ajax

function my_awesome_func( $data )

{
    global $TWhois;


    $dominio = $data['dominio'];

    return $TWhois->viewWhois($dominio);
}


add_filter('woocommerce_add_to_cart_redirect', 'redirect_to_checkout', 20);

function redirect_to_checkout() {

    global $woocommerce;

    $checkout_url = site_url().'/carrito';
    $allow = array('1. Planes');
    //$product = wc_get_product(absint( $_REQUEST['add-to-cart']));
    //$categories = wc_get_product_category_list(absint( $_REQUEST['add-to-cart']));
    //var_dump($product->get_categories());die;
    $categories = explode(',', wc_get_product_category_list(absint( $_REQUEST['add-to-cart'])));
    foreach($categories as $category){
        $categories[] = trim(strip_tags($category));
    }
    
    $dominio = '';
    $selector_dominio = '';
    $category_tipo = 'combo';
    $modo = 'dyh';
    $modo_domain = 'c';
    
    $exclude = array('1. TecnoSocial', '2. TecnoAds Facebook', '3. TecnoAds Google', '3. TecnoOffice');    
    

    if(count($categories)>=1){

            if(in_array('1. Planes', $categories) and empty(array_intersect($categories, $exclude))){

            foreach(array_keys($_POST) as $key){
                
                if(preg_match("/dominio/", $key)){
                    $dominio = $_POST[$key];
                }
                if(preg_match("/selector/", $key)){
                    $selector_dominio = $_POST[$key];
                }
                
        }
        
        if(preg_match("/registrado/", $selector_dominio)){
            $category_tipo = 'hosting';
            $modo = 'h';
        }
       
  if(preg_match("/transferir/", $selector_dominio)){
            $modo_domain = 't';
        }
   
            echo '<div class="button loading plan-domains-loader"><span>Comprobando disponibilidad de dominios..
 </span></div>  
      <div style="display:none">  <form  method="POST" action="'.site_url().'/contratacion/?category='.$category_tipo.'" name="frm1">
          <input name="whois_domain" value="'.$dominio.'" />
          <input type="hidden" name="options_service_input" value="'.$modo.'">
           <input type="hidden" name="mode_domain" value="'.$modo_domain.'">
          <input name="variable2" />
        </form>
        <div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.frm1.submit();

   
}, false);
</script>';

            }else{
                return $checkout_url;
            }
       
    }
    
}


add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_data', 25, 3 );

function add_cart_item_data( $cart_item_meta, $product_id, $variation_id ) {
    global $woocommerce;
    $data = array();
    $groups_cart = array();
    $abc = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

    $items = $woocommerce->cart->get_cart();
    foreach($items as $item => $values) { 
    
        foreach($values["addons"] as $item_2 => $value) { 
            if($value['name'] == 'Grupo'){
               $groups_cart[$value["value"]] = $value["value"];
            }
        }

    } 

	if(WC()->session->get( 'custom_data_')){
	    $grupo = WC()->session->get( 'custom_data_')['value'];
	}else{
	    if(count($groups_cart)>=1)
	        $letra = $abc[count($groups_cart)];
	    else
	        $letra = 'A';
	   $grupo = $letra;//.rand(1,99).chr(rand(65,90)).chr(rand(65,90));
	}

    
    if($_GET['id_group']){
       $grupo = $_GET['id_group'];
    }
    
    $data = array('name'=>'Grupo', 'value'=>$grupo);
    array_push($cart_item_meta['addons'], $data);
	//$cart_item_meta['addons']['custom_data_'] = $data['group'] = $grupo;
	//$cart_item_meta['custom_data_']['unique_key'] = md5( microtime().rand() );
	
    WC()->session->set( 'custom_data_', $data );
   
    return $cart_item_meta;
	
}

add_action( 'template_redirect', 'rp_callback' ); 
function rp_callback() {
  if ( is_cart()) {
    if(WC()->session->get( 'custom_data_')){
        WC()->session->__unset( 'custom_data_' );
    }
  }
}
//add_filter( 'woocommerce_get_item_data', 'get_item_data' , 25, 2 );

function get_item_data ( $item_data, $cart_item ) {

	if ( empty( $cart_item['custom_data_']['group'] ) ) {
		return $item_data;
	}
	$item_data[] = array(
		'key'     => 'GRUPO',
		'value'   => wc_clean( $cart_item['custom_data_']['group'] ),
		'display' => 'GRUPO',
	);

	return $item_data;
	
}
function is_plan_with_hosting($product){

    $categories = explode(',', wc_get_product_category_list(absint( $product)));
    foreach($categories as $category){
        $categories[] = trim(strip_tags($category));
    }
    
    $exclude = array('1. TecnoSocial', '2. TecnoAds Facebook', '3. TecnoAds Google', '3. TecnoOffice');    
    

    if(count($categories)>=1){

            if(in_array('1. Planes', $categories) and empty(array_intersect($categories, $exclude))){
                return true;
            }
                
                
    }
   return false;
}
function is_hosting($product){

    $categories = explode(',', wc_get_product_category_list(absint( $product)));
    foreach($categories as $category){
        $categories[] = trim(strip_tags($category));
    }
    
    if(count($categories)>=1){

            if(in_array('Hosting Estándar', $categories) or in_array('Hosting Avanzado (Revendedores)', $categories)){
                return true;
            }
                
                
    }
   return false;
}
function is_domain($product){

    $categories = explode(',', wc_get_product_category_list(absint( $product)));
    foreach($categories as $category){
        $categories[] = trim(strip_tags($category));
    }
    
    if(count($categories)>=1){

            if(in_array('Dominios', $categories)){
                return true;
            }
                
                
    }
   return false;
}


//add_filter('woocommerce_cart_item_remove_link', 'customized_cart_item_remove_link', 20, 2 );
function customized_cart_item_remove_link( $button_link, $cart_item_key ){

    $addons = WC()->cart->get_cart_item( $cart_item_key );
    $grupo = false;
  
   foreach($addons['addons'] as $item => $values) { 
       if($values['name']=='Grupo')
            $grupo = $values['value'];
}

    foreach(WC()->cart->get_cart() as $item => $values) { 
        foreach($values["addons"] as $item_2 => $value) { 
         
            if($value['name'] == 'Grupo'){
                if($value['value'] == $grupo){
                     if(is_plan_with_hosting($values["product_id"])){
                         if($item!=$cart_item_key)
                            $button_link = '';
                     }
                }
              
            }
        }

    } 
     return $button_link;
}

add_action('woocommerce_check_cart_items', 'validate_all_cart_contents');

function validate_all_cart_contents(){
    
    if (!is_ajax()){
?>
<script>

jQuery(document).ready(function(){
   
    jQuery(document).on("click",".btn-plan-cart",function(e){
    
    var action = '<?php echo site_url();?>'+ jQuery(this).data('action');
    jQuery('#whois_domain_c').val(jQuery(this).data('domain'));
    jQuery('#mode_domain_c').val(jQuery(this).data('mode'));
    jQuery('#options_service_input_c').val(jQuery(this).data('type'));
    jQuery('#id_group_c').val(jQuery(this).data('group'));
    
    jQuery('.contratacion-cart-form').attr('action', action);
    jQuery('.contratacion-cart-form').submit();
    console.log(jQuery(this).data('domain'));
    });
});
</script>
<form class="contratacion-cart-form"  method="post">
    <input type="hidden" name="whois_domain" id="whois_domain_c" value="" />
    <input type="hidden" name="options_service_input" id="options_service_input_c" value="" />
    <input type="hidden" name="mode_domain" id="mode_domain_c" value="" />
    <input type="hidden" name="id_group" id="id_group_c" value="" />
    
</form>    
<?php

    $validation_products = [];
    $option_hosting = '';
    $dominio = '';
      foreach(WC()->cart->get_cart() as $item => $values) { 
            foreach($values["addons"] as $item_2 => $value) { 

             if($value['name'] == 'Selector Dominios')
                $option_hosting = $value['value'];
             if($value['name'] == 'Dominio')
                $dominio = $value['value'];
                
                if($value['name'] == 'Grupo'){
                     if(is_plan_with_hosting($values["product_id"])){
                            $validation_products[$value['value']]['plan']['title'] = $values['data']->get_title();
                            $validation_products[$value['value']]['plan']['mode'] = $option_hosting;
                    }elseif(is_hosting($values["product_id"])){
                            $validation_products[$value['value']]['hosting']['title'] = $values['data']->get_title();
                    }elseif(is_domain($values["product_id"])){
                            $validation_products[$value['value']]['domain']['title'] = $values['data']->get_title();
                    }
                  $validation_products[$value['value']]['count']+= 1;
                }
            }
    
        } 


         foreach($validation_products as $item => $values) { 
            $errors = '';
             if($values['plan']){

                    if(!$values['domain'] and !$values['hosting'] and $values['plan']['mode']!='Utilizar Dominio Registrado'){
                        
                        $action_ = '/contratacion/?category=combo';
                        $errors.= "Es necesario un hosting y al menos un dominio para su plan <strong>".$values['plan']['title']."</strong> en el grupo ".$item.".
                            Haz clic <a class='btn-plan-cart' data-action='".$action_."'  data-mode='c' data-domain='".$dominio."' data-group='".$item."' data-type='dyh' href='#'>AQUÍ</a> para agregarlos. <br>";
                          
          
                 }elseif(!$values['domain'] and $values['plan']['mode']!='Utilizar Dominio Registrado'){
                     $action_ = '/contratacion/?category=dominio';
                        $errors.= "Es necesario un dominio para su plan <strong>".$values['plan']['title']."</strong> en el grupo ".$item.".
                        Haz clic <a class='btn-plan-cart' data-action='".$action_."'  data-mode='1' data-domain='".$dominio."' data-group='".$item."' data-type='d' href='#'>AQUÍ</a> para agregarlo. <br>";
                 }elseif(!$values['hosting']){
                        $action_ = '/contratacion/?category=hosting';
                        $errors.= "Es necesario un hosting para su plan <strong>".$values['plan']['title']."</strong> en el grupo ".$item.".
                        Haz clic <a class='btn-plan-cart' data-action='".$action_."'  data-mode='1' data-domain='".$dominio.".com' data-group='".$item."' data-type='h' href='#'>AQUÍ</a> para agregarlo. <br>";
                 }
                 
             }
             if(!empty($errors)){
            remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
            wc_add_notice( sprintf( $errors ), 'error' ); 
         }
         }
         
            
    }
}
