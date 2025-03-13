<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


function stop_heartbeat() {
    wp_deregister_script('heartbeat');
}

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'astra-theme-css' ) );
    }
endif;
// END ENQUEUE PARENT ACTION
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 20 );
add_action("wp_enqueue_scripts", "dcms_insertar_js");
add_action("wp_enqueue_scripts", "list_tab");
add_action("wp_enqueue_scripts", "dcms_js_upgrade");
add_action('wp_enqueue_scripts', 'load_scripts');
add_action('init', 'emails_suscripciones');
add_action('init', 'emails_pedidos');
add_action('init','remove_loop_buttons');
add_action('init', 'stop_heartbeat', 1 );
add_filter('gettext', 'change_sku', 999, 3);
add_filter('gettext', 'camb_reg', 999, 3);
add_filter('gettext', 'cambiar_btn', 999, 3);
add_filter('gettext', 'cambiar_year', 999, 3);
add_filter('gettext', 'cambiar_caracteres', 999, 3);
add_filter('gettext', 'cambiar_tab_1', 999, 3);
add_filter('gettext', 'cambiar_tab_2', 999, 3);
add_filter('woocommerce_calculate_totals', 'cambiar_monto', 10, 2 );
add_filter('woocommerce_before_calculate_totals', 'arreglar_monto_renovacion', 10, 2 );
add_filter('woocommerce_thankyou','despues_pago',10,2);
add_action('woocommerce_cart_coupon', 'themeprefix_back_to_store');
add_action('woocommerce_order_status_completed', 'actualiza_estado_pedidos' );
add_action('woocommerce_order_status_pending_to_processing', 'actualiza_estado_pedidos' );
add_action("woocommerce_before_calculate_totals", "reparar_metas_renovacion");
add_filter('woocommerce_add_to_cart_redirect', 'validar_usuario' );
add_filter('woocommerce_coupon_is_valid', 'disable_coupons_for_subscription_products', 10, 3 );
add_action('woocommerce_save_account_details', 'actualizar_correo', 10, 1 );
add_action('woocommerce_cart_calculate_fees','woo_add_cart_fee', 10 );
add_action('woocommerce_before_calculate_totals', 'validar_vps_prices', 20, 2 );
add_filter('automatewoo/rules/includes', 'validar_stripe' );
add_filter('automatewoo/rules/includes', 'nota_administrador' );
add_filter('automatewoo/rules/includes', 'validar_notas_usuario' );
add_filter('automatewoo/rules/includes', 'my_automatewoo_rules' );
add_filter('automatewoo/rules/includes', 'excluir_suscripcion' );
add_filter('automatewoo/variables','ajustes_mes_deposito');
add_filter('automatewoo/variables','mensajes_ajustes_mes');
add_filter('automatewoo/variables','precio_inicial_mes_vencido');
add_filter('automatewoo/variables','precio_mes_activacion');
add_filter('automatewoo/variables','fecha_mes_deposito');
add_filter('automatewoo/variables', 'item_name_s' );
add_filter('automatewoo/variables', 'item_code' );
add_filter('automatewoo/variables', 'item_domain' );
add_filter('automatewoo/variables', 'item_interval' );
add_filter('automatewoo/variables', 'item_period' );
add_filter('automatewoo/variables', 'item_currency' );
add_filter('automatewoo/variables', 'order_suscription_id' );
add_filter('automatewoo/variables','url_administra_plataformas');
add_filter('automatewoo/variables','usuario_plataformas');
add_filter('automatewoo/variables','clave_plataformas');
add_filter('automatewoo/variables','email_plataformas');
add_filter('automatewoo/variables','hosting_espacio');
add_filter('automatewoo/variables','hosting_ancho_banda');
add_filter('automatewoo/variables','hosting_usuario');
add_filter('automatewoo/variables','hosting_clave');
add_filter('automatewoo/variables','hosting_dns_uno');
add_filter('automatewoo/variables','hosting_dns_dos');
add_filter('automatewoo/variables','hosting_raiz');
add_filter('automatewoo/variables','hosting_email');
add_filter('automatewoo/variables','hosting_ip_dns_uno');
add_filter('automatewoo/variables','hosting_ip_dns_dos');
add_filter('automatewoo/variables','hosting_ip_server');
add_filter('automatewoo/variables','price_product');
add_filter('automatewoo/variables','id_renovacio');
add_filter('automatewoo/variables','date_start');
add_filter('default_checkout_billing_country', 'njengah_default_checkout_country', 10, 1 );
add_filter('gravityflow_step_status_evaluation_approval', 'aprobacion_del_proceso', 10, 3 );
add_filter('gravityflowwoocommerce_new_entry', 'crear_entrada_por_estado_pedido', 10, 3 );
add_action('gform_after_submission', 'post_to_third_party', 10, 2 );
add_action('wp_ajax_nopriv_producto_contratado','producto_contratado_cambio');
add_action('wp_ajax_producto_contratado','producto_contratado_cambio');
add_action('wp_ajax_nopriv_obtener_dominio','obtener_dominio_cambio');
add_action('wp_ajax_obtener_dominio','obtener_dominio_cambio');
add_action('wp_ajax_nopriv_obtener_variation','obtener_variation_cambio');
add_action('wp_ajax_obtener_variation','obtener_variation_cambio');
add_action('wp_ajax_nopriv_clear_carrito','limpiar_carrito');
add_action('wp_ajax_clear_carrito','limpiar_carrito');
add_action('wp_ajax_nopriv_get_emails','consult_emails');
add_action('wp_ajax_get_emails','consult_emails');
add_action('wp_ajax_nopriv_get_emails_contentenido','consultar_contenido');
add_action('wp_ajax_get_emails_contentenido','consultar_contenido');
add_action('wp_ajax_nopriv_con_plan', 'con_plan_sw');
add_action('wp_ajax_con_plan', 'con_plan_sw');
add_action('wp_ajax_nopriv_con_dif_plan', 'dife_plan');
add_action('wp_ajax_con_dif_plan', 'dife_plan');
add_action('wp_ajax_nopriv_desc_id', 'descu_prod');
add_action('wp_ajax_desc_id', 'descu_prod');

function dcms_insertar_js(){

    wp_register_script('miscriptupd', get_site_url(). '/wp-content/themes/tecnohost/js/tecno.js', array('jquery'), '2', true );
    wp_enqueue_script('miscriptupd');

    wp_register_script('custom-users-fields-js', get_site_url(). '/wp-content/themes/tecnohost/js/custom-users-fields.js', array('jquery'), '2', true );
    wp_enqueue_script('custom-users-fields-js');

    wp_localize_script('miscriptupd','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);
}

add_filter( 'xmlrpc_methods', function( $methods ) {
    unset( $methods['pingback.ping'] );
    return $methods;
} );
/*
Replace SKU on WooCommerce for Other word.
*/
function change_sku( $translated_text, $text, $domain  ) {
    if( $text == 'SKU' || $text == 'SKU:' ) return 'Código:';
    return $translated_text;
}

function camb_reg( $translated_text, $text, $domain  ) {
    if( $text == 'Registrarse' || $text == 'Registrarse:' ) return 'Registrar Nuevo Usuario';
    return $translated_text;
}

function cambiar_btn( $translated_text, $text, $domain  ) {
    if( $text == 'Select options' || $text == 'Select options:' ) return 'Comprar Ahora';
    return $translated_text;
}

function cambiar_year( $translated_text, $text, $domain  ) {
    if( $text == 'Invoice') return 'Factura';
    return $translated_text;
}

function cambiar_caracteres( $translated_text, $text, $domain  ) {
    if( $text == 'characters remaining' ) return 'caracteres restantes';
    return $translated_text;
}

function cambiar_tab_1( $translated_text, $text, $domain  ) {
    if( $text == 'Descripción' ) return 'Características Generales';
    return $translated_text;
}

function cambiar_tab_2( $translated_text, $text, $domain  ) {
    if( $text == 'Información adicional' ) return 'Características Especificas';
    return $translated_text;
}
/**Añadir boton de seguir comprando*/
function themeprefix_back_to_store() { ?>
    <a class="button wc-backward" href="/contratacion"><?php _e( 'Seguir comprando', 'woocommerce' ) ?></a>
    <input type="button" class="button" id="vac_cart" name="wcj_empty_cart" value="Vaciar carrito">
    <?php
}

function remove_loop_button(){
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

function replace_add_to_cart() {
    echo do_shortcode('<a  href="/contratacion" class="button product_type_subscription add_to_cart_button">Comprar Ahora</a>');
}

function remove_loop_buttons(){
    remove_action( 'woocommerce_product_add_to_cart_url', 'woocommerce_product_add_to_cart_url', 10);
}

function remove_loop_buttonss($url,$datos){
    echo do_shortcode('<a  href="/contratacion/?category=transferencia" class="button" id="transf">Transferir</a>');
    echo do_shortcode('<a  href="/contratacion/?category=registro" class="button" id="regist">Registrar</a>');
    echo do_shortcode('<a  href="/contratacion/?category=hosting" id="cat" class="button product_type_subscription add_to_cart_button">Comprar Ahora</a>');
}

$url = $_SERVER['QUERY_STRING'];
$patron = '/switch-subscription/';

if (preg_match($patron, $url, $coincidencias)) {
    add_action('woocommerce_after_add_to_cart_quantity','switch_add_carro',10);
}else{
    add_action('init','remove_loop_button');
    add_action('woocommerce_after_shop_loop_item','replace_add_to_cart');
    add_action('woocommerce_product_add_to_cart_url','remove_loop_buttonss',10,2);
}

function switch_add_carro(){
    global $post;
    $val_cate = wc_get_object_terms($post->ID,'product_cat', "term_id");
    if($val_cate[0] != "26"){
        echo '<input type="checkbox" name="" class="selec_plan" value="'.$post->ID.'">';
    }
    echo '
        <style>
            span.price > span.subscription-details:not(span.price > span.price span.subscription-details) {
                display: none;
            }
        </style>';
   //echo '<button style="display:none;" type="submit" class="single_add_to_cart_button button alt camb_bt" name="add-to-cart" value="'.$post->ID.'">Cambio de suscripción</button>';
}

function myplugin_registration_save($user_id, $feed, $entry ) {
    /**
     * Con el ID del usuario usamos la funcion get_user_meta para extraer los datos que necesitamos
     * para crear un arreglo con los datos que van a nuestras distintas plataformas.
    */
    $memberInfo = get_user_meta($user_id);

    $usa_estados = array(
        "AL"=>array("Alabama"),
        "AK"=>array("Alaska"),
        "AZ"=>array("Arizona"),
        "AR"=>array("Arkansas"),
        "CA"=>array("California"),
        "CO"=>array("Colorado"),
        "CT"=>array("Connecticut"),
        "DE"=>array("Delaware"),
        "DC"=>array("District Of Columbia"),
        "FL"=>array("Florida"),
        "GA"=>array("Georgia"),
        "HI"=>array("Hawaii"),
        "ID"=>array("Idaho"),
        "IL"=>array("Illinois"),
        "IN"=>array("Indiana"),
        "IA"=>array("Iowa"),
        "KS"=>array("Kansas"),
        "KY"=>array("Kentucky"),
        "LA"=>array("Louisiana"),
        "ME"=>array("Maine"),
        "MD"=>array("Maryland"),
        "MA"=>array("Massachusetts"),
        "MI"=>array("Michigan"),
        "MN"=>array("Minnesota"),
        "MS"=>array("Mississippi"),
        "MO"=>array("Missouri"),
        "MT"=>array("Montana"),
        "NE"=>array("Nebraska"),
        "NV"=>array("Nevada"),
        "NH"=>array("New Hampshire"),
        "NJ"=>array("New Jersey"),
        "NM"=>array("New Mexico"),
        "NY"=>array("New York"),
        "NC"=>array("North Carolina"),
        "ND"=>array("North Dakota"),
        "OH"=>array("Ohio"),
        "OK"=>array("Oklahoma"),
        "OR"=>array("Oregon"),
        "PA"=>array("Pennsylvania"),
        "RI"=>array("Rhode Island"),
        "SC"=>array("South Carolina"),
        "SD"=>array("South Dakota"),
        "TN"=>array("Tennessee"),
        "TX"=>array("Texas"),
        "UT"=>array("Utah"),
        "VT"=>array("Vermont"),
        "VA"=>array("Virginia"),
        "WA"=>array("Washington"),
        "WV"=>array("West Virginia"),
        "WI"=>array("Wisconsin"),
        "WY"=>array("Wyoming"),
        "AA"=>array("Fuerzas Armadas (AA)"),
        "AE"=>array("Fuerzas Armadas  US"),
        "AP"=>array("Fuerzas Armadas  US")
    );

    $paises = array(
        "AF" => array("Afganistán"),
        "AL" => array("Albania"),
        "DE" => array("Alemania"),
        "DZ" => array("Algeria"),
        "AD" => array("Andorra"),
        "AO" => array("Angola"),
        "AI" => array("Anguilla"),
        "AG" => array("Antigua y Barbuda"),
        "AQ" => array("Antártida"),
        "SA" => array("Arabia Saudita"),
        "AR" => array("Argentina"),
        "AM" => array("Armenia"),
        "AW" => array("Aruba"),
        "AU" => array("Australia"),
        "AT" => array("Austria"),
        "AZ" => array("Azerbaijan"),
        "BS" => array("Bahamas"),
        "BH" => array("Bahrain"),
        "BD" => array("Bangladesh"),
        "BB" => array("Barbados"),
        "PW" => array("Belau"),
        "BZ" => array("Belize"),
        "BJ" => array("Benin"),
        "BM" => array("Bermuda"),
        "BT" => array("Bhutan"),
        "BY" => array("Bielorrusia"),
        "MM" => array("Birmania"),
        "BO" => array("Bolivia"),
        "BQ" => array("Bonaire, San Eustaquio y Saba"),
        "BA" => array("Bosnia y Herzegovina"),
        "BW" => array("Botswana"),
        "BR" => array("Brasil"),
        "BN" => array("Brunéi"),
        "BG" => array("Bulgaria"),
        "BF" => array("Burkina Faso"),
        "BI" => array("Burundi"),
        "BE" => array("Bélgica"),
        "CV" => array("Cabo Verde"),
        "KH" => array("Camboya"),
        "CM" => array("Camerún"),
        "CA" => array("Canadá"),
        "TD" => array("Chad"),
        "CL" => array("Chile"),
        "CN" => array("China"),
        "CY" => array("Chipre"),
        "VA" => array("Ciudad del Vaticano"),
        "CO" => array("Colombia"),
        "KM" => array("Comoras"),
        "CG" => array("Congo (Brazzaville)"),
        "CD" => array("Congo (Kinshasa)"),
        "KP" => array("Corea del Norte"),
        "KR" => array("Corea del Sur"),
        "CR" => array("Costa Rica"),
        "CI" => array("Costa de Marfil"),
        "HR" => array("Croacia"),
        "CU" => array("Cuba"),
        "CW" => array("Curaçao"),
        "DK" => array("Dinamarca"),
        "DJ" => array("Djibouti"),
        "DM" => array("Dominica"),
        "EC" => array("Ecuador"),
        "EG" => array("Egipto"),
        "SV" => array("El Salvador"),
        "AE" => array("Emiratos Árabes Unidos"),
        "ER" => array("Eritrea"),
        "SK" => array("Eslovaquia"),
        "SI" => array("Eslovenia"),
        "ES" => array("España"),
        "US" => array("Estados Unidos (EEUU)"),
        "EE" => array("Estonia"),
        "ET" => array("Etiopía"),
        "PH" => array("Filipinas"),
        "FI" => array("Finlandia"),
        "FJ" => array("Fiyi"),
        "FR" => array("Francia"),
        "GA" => array("Gabón"),
        "GM" => array("Gambia"),
        "GE" => array("Georgia"),
        "GH" => array("Ghana"),
        "GI" => array("Gibraltar"),
        "GD" => array("Granada"),
        "GR" => array("Grecia"),
        "GL" => array("Groenlandia"),
        "GP" => array("Guadalupe"),
        "GU" => array("Guam"),
        "GT" => array("Guatemala"),
        "GF" => array("Guayana Francesa"),
        "GG" => array("Guernsey"),
        "GN" => array("Guinea"),
        "GQ" => array("Guinea Ecuatorial"),
        "GW" => array("Guinea-Bisáu"),
        "GY" => array("Guyana"),
        "HT" => array("Haití"),
        "HN" => array("Honduras"),
        "HK" => array("Hong Kong"),
        "HU" => array("Hungría"),
        "IN" => array("India"),
        "ID" => array("Indonesia"),
        "IQ" => array("Irak"),
        "IE" => array("Irlanda"),
        "IR" => array("Irán"),
        "BV" => array("Isla Bouvet"),
        "NF" => array("Isla Norfolk"),
        "SH" => array("Isla Santa Elena"),
        "IM" => array("Isla de Man"),
        "CX" => array("Isla de Navidad"),
        "IS" => array("Islandia"),
        "AX" => array("Islas Åland"),
        "KY" => array("Islas Caimán"),
        "CC" => array("Islas Cocos"),
        "CK" => array("Islas Cook"),
        "FO" => array("Islas Feroe"),
        "GS" => array("Islas Georgias y Sandwich del Sur"),
        "HM" => array("Islas Heard y McDonald"),
        "FK" => array("Islas Malvinas"),
        "MP" => array("Islas Marianas del Norte"),
        "MH" => array("Islas Marshall"),
        "SB" => array("Islas Salomón"),
        "TC" => array("Islas Turcas y Caicos"),
        "VG" => array("Islas Vírgenes Británicas"),
        "VI" => array("Islas Vírgenes de Estados Unidos (EEUU)"),
        "UM" => array("Islas de ultramar menores de Estados Unidos (EEUU)"),
        "IL" => array("Israel"),
        "IT" => array("Italia"),
        "JM" => array("Jamaica"),
        "JP" => array("Japón"),
        "JE" => array("Jersey"),
        "JO" => array("Jordania"),
        "KZ" => array("Kazajistán"),
        "KE" => array("Kenia"),
        "KG" => array("Kirguistán"),
        "KI" => array("Kiribati"),
        "KW" => array("Kuwait"),
        "LA" => array("Laos"),
        "LS" => array("Lesoto"),
        "LV" => array("Letonia"),
        "LR" => array("Liberia"),
        "LY" => array("Libia"),
        "LI" => array("Liechtenstein"),
        "LT" => array("Lituania"),
        "LU" => array("Luxemburgo"),
        "LB" => array("Líbano"),
        "MO" => array("Macao"),
        "MG" => array("Madagascar"),
        "MY" => array("Malasia"),
        "MW" => array("Malaui"),
        "MV" => array("Maldivas"),
        "MT" => array("Malta"),
        "ML" => array("Malí"),
        "MA" => array("Marruecos"),
        "MQ" => array("Martinica"),
        "MU" => array("Mauricio"),
        "MR" => array("Mauritania"),
        "YT" => array("Mayotte"),
        "FM" => array("Micronesia"),
        "MD" => array("Moldavia"),
        "MN" => array("Mongolia"),
        "ME" => array("Montenegro"),
        "MS" => array("Montserrat"),
        "MZ" => array("Mozambique"),
        "MX" => array("México"),
        "MC" => array("Mónaco"),
        "NA" => array("Namibia"),
        "NR" => array("Nauru"),
        "NP" => array("Nepal"),
        "NI" => array("Nicaragua"),
        "NG" => array("Nigeria"),
        "NU" => array("Niue"),
        "NO" => array("Noruega"),
        "NC" => array("Nueva Caledonia"),
        "NZ" => array("Nueva Zelanda"),
        "NE" => array("Níger"),
        "OM" => array("Omán"),
        "PK" => array("Pakistán"),
        "PA" => array("Panamá"),
        "PG" => array("Papúa Nueva Guinea"),
        "PY" => array("Paraguay"),
        "NL" => array("Países Bajos"),
        "PE" => array("Perú"),
        "PN" => array("Pitcairn"),
        "PF" => array("Polinesia Francesa"),
        "PL" => array("Polonia"),
        "PT" => array("Portugal"),
        "PR" => array("Puerto Rico"),
        "QA" => array("Qatar"),
        "GB" => array("Reino Unido (UK)"),
        "CF" => array("República Centroafricana"),
        "CZ" => array("República Checa"),
        "DO" => array("República Dominicana"),
        "MK" => array("República de Macedonia"),
        "RE" => array("Reunión"),
        "RW" => array("Ruanda"),
        "RO" => array("Rumania"),
        "RU" => array("Rusia"),
        "EH" => array("Sahara Occidental"),
        "WS" => array("Samoa"),
        "AS" => array("Samoa Americana"),
        "BL" => array("San Bartolomé"),
        "KN" => array("San Cristóbal y Nieves"),
        "SM" => array("San Marino"),
        "MF" => array("San Martín (parte de Francia)"),
        "SX" => array("San Martín (parte de Holanda)"),
        "PM" => array("San Pedro y Miquelón"),
        "VC" => array("San Vicente y las Granadinas"),
        "LC" => array("Santa Lucía"),
        "ST" => array("Santo Tomé y Príncipe"),
        "SN" => array("Senegal"),
        "RS" => array("Serbia"),
        "SC" => array("Seychelles"),
        "SL" => array("Sierra Leona"),
        "SG" => array("Singapur"),
        "SY" => array("Siria"),
        "SO" => array("Somalia"),
        "LK" => array("Sri Lanka"),
        "SZ" => array("Suazilandia"),
        "ZA" => array("Sudáfrica"),
        "SD" => array("Sudán"),
        "SS" => array("Sudán del Sur"),
        "SE" => array("Suecia"),
        "CH" => array("Suiza"),
        "SR" => array("Surinam"),
        "SJ" => array("Svalbard y Jan Mayen"),
        "TH" => array("Tailandia"),
        "TW" => array("Taiwán"),
        "TZ" => array("Tanzania"),
        "TJ" => array("Tayikistán"),
        "IO" => array("Territorio Británico del Océano Índico"),
        "PS" => array("Territorios Palestinos"),
        "TF" => array("Territorios australes franceses"),
        "TL" => array("Timor Oriental"),
        "TG" => array("Togo"),
        "TK" => array("Tokelau"),
        "TO" => array("Tonga"),
        "TT" => array("Trinidad y Tobago"),
        "TM" => array("Turkmenistán"),
        "TR" => array("Turquía"),
        "TV" => array("Tuvalu"),
        "TN" => array("Túnez"),
        "UA" => array("Ucrania"),
        "UG" => array("Uganda"),
        "UY" => array("Uruguay"),
        "UZ" => array("Uzbekistán"),
        "VU" => array("Vanuatu"),
        "VE" => array("Venezuela"),
        "VN" => array("Vietnam"),
        "WF" => array("Wallis y Futuna"),
        "YE" => array("Yemen"),
        "ZM" => array("Zambia"),
        "ZW" => array("Zimbabue")
    );

    $pai_final = $paises[$memberInfo['billing_country'][0]][0];

    $est_final = $memberInfo['billing_state'][0];

    $datos = array(
        'cf_1333' => $_SERVER['HTTP_REFERER'], //referidor
        'leadstatus' => 'No Contactado',
        'rating' => 'Iniciando',
        'cf_872' => "Formulario de Registro",
        'leadsource' => "Portal TecnoHost",
        'assigned_user_id' => '20x2',//ModuloIdxUserId
        "page_name" => "Tecno Tecnohost",
        'firstname' => $memberInfo['first_name'][0], //nombre
        'lastname' => $memberInfo['last_name'][0], //apellido
        'cf_1345' => $memberInfo['billing_eu_vat_number'][0], //cedula
        'phone' => $memberInfo['billing_phone'][0], //telefono
        'email' => $memberInfo['billing_email'][0], //correo
        'lane' => $memberInfo['billing_address_1'][0], //direccion
        'city' => $memberInfo['billing_city'][0], //ciudad
        'state' => $est_final, //estado
        'country' => $pai_final, //pais
        'code' => $memberInfo['billing_postcode'][0], //zip
        'company' => $memberInfo['billing_company'][0]//empresa
    );

    /**
     * hacemos el llamado al archivo donde temos nuestras funciones de integración
    */
    require("integrar_registro_crm_mautic.php");
    /**
     * Pasamos los datos a la función al CRM ordenados para integrarlos al CRM
     */
    regis_crm($datos);
    /**
     * Ordenamos los datos para pre-procesarlos en mautic. En este arreglo deben ir los campos creados en el formulario
     * no importa el orden pero si es importante que los campos posean los nombres igual a los declarados en mautic
     */
    $datos_mautic = array(
        'nombre' => $memberInfo['first_name'][0],
        'apellido' => $memberInfo['last_name'][0],
        'empresa' => $memberInfo['billing_company'][0],
        'correo_electronico' => $memberInfo['billing_email'][0],
        'telefono_de_contacto' => $memberInfo['billing_phone'][0],
        'pais' => $pai_final,
        'departamento' => $est_final,
        'ciudad' => $memberInfo['billing_city'][0],
        'direccion' => $memberInfo['billing_address_1'][0],
        'referidor' => $_SERVER['HTTP_REFERER'],
        'nit' => $memberInfo['billing_eu_vat_number'][0]
    );

    pushMauticForm($datos_mautic, '49');
}

add_action( 'rest_api_init', function () {
    register_rest_route( '/moneda_en_uso/v1', '/moneda', array(
        'methods' => 'POST',
        'callback' => 'saber_moneda',
    ) );
} );

function saber_moneda(){
    return get_woocommerce_currency();
}

add_action( 'rest_api_init', function () {
    register_rest_route( '/respuesta_orden/v1', '/orden_epayco', array(
        'methods' => 'POST',
        'callback' => 'datos_orden_epayco',
    ) );
} );

function datos_orden_epayco(){
    global $wpdb;
    $id_orden = $_POST['id_orden'];
    $referencia = $_POST['referencia'];
    $estado = $_POST['estado'];
    $descripción = $_POST['descripción'];
    $total = $_POST['total'];
    $moneda = $_POST['moneda'];

    switch ($_POST['entidad']){
        case "VS":
            $entidad = "VISA";
        break;
        case "AM":
            $entidad = "AMERICAN EXPRESS";
        break;
        case "MT":
            $entidad = "MASTERCARD ";
        break;
    }
    $order = new WC_Order( $id_orden );
    $respuesta = $order;

    $contenido = "";
    $contenido .= '
        <table id="tbl_resepayco">
            <tbody>
                <tr>
                    <th colspan="2">DATOS DE LA COMPRA</th>
                </tr>
                <tr>
                    <td>Estado de la transacción</td>
                    <td>'.$estado.'</td>
                </tr>
                <tr>
                    <td>ID de la transacción</td>
                    <td>'.$referencia.'</td>
                </tr>
                <tr>
                    <td>Valor total</td>
                    <td>'.number_format($total, 2).'</td>
                </tr>
                <tr>
                    <td>Moneda</td>
                    <td>'.$moneda.'</td>
                </tr>
                <tr>
                    <td>Descripción</td>
                    <td>'.$descripción.'</td>
                </tr>
                <tr>
                    <td>Entidad</td>
                    <td>'.$entidad.'</td>
                </tr>
                <tr>
                    <th colspan="2" id="ms_trans"><h1>¡Gracias por tu compra!</h1></th>
                </tr>
            </tbody>
    ';

    $contenido .= '</table>';
    return $contenido;
}

function validar_suscripcion_switch($workflow){

}

function validar_cambio_moneda($workflow){
    $order = $workflow->data_layer()->get_order();
    $moneda_pedido = $order->get_data()['currency'];
    $suscripcion_id = $order->get_meta('_subscription_switch');
    $suscripcion = wc_get_order($suscripcion_id);
    update_post_meta( $suscripcion_id, '_order_currency',  sanitize_text_field($moneda_pedido) );
}

function actualiza_estado_pedidos($order_id){
    global $woocommerce;


    if(current_user_can('manage_options'))
    {
        $order = new WC_Order( $order_id );
        if($order->get_status() == "completed"){
            $args = array(
                'post_parent' => $order_id,
                'post_type' => 'shop_subscription',
                'numberposts' => -1,
                'post_status' => 'open'
            );

            $suscripciones = get_children($args);
            $llaves = array_keys($suscripciones);
            $paymentMethods = array( 'stripe', 'payulatam', 'epayco', 'paypal', 'bacs', 'cheque' );
            foreach($llaves as $key => $value){
                $order_suscripcion =  wc_get_order($value);
                if ( !in_array( get_post_meta($order_id, '_payment_method', true), $paymentMethods ) ) return;
                if ($order_suscripcion->get_status() != "Active"){
                    $order_suscripcion->update_status( 'active' );
                }
            }
        }
    }
    else
    {

    }
}
/**
 * Función para dar formato a las fechas que se imprimen en los correos enviados por el follow-up
**/
function fechas_formateadas($fecha){

    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);

    return $numeroDia." ".$nombreMes.", ".$anio;
}
//Hook para hacer uso de la API
add_action( 'rest_api_init', function () {
    register_rest_route( '/periodisidad/v1', '/dominio', array(
        'methods' => 'POST',
        'callback' => 'periodicidad_dominios',
    ) );
} );

function periodicidad_dominios() {

    $periodo = $_POST['periodo'];
    $dominio_extencion = $_POST['dominio_extencion'];

    $args = array(
        'post_type' => 'product',
        'product_cat' => 'Dominios',
        'posts_per_page' => -1,
        'order_by' => 'title',
        'order' => 'ASC'
    );
    $loop = new WP_Query($args);

    if ($loop->have_posts()) {

        while ($loop->have_posts()) : $loop->the_post();

            $pid = get_the_ID();
            $product = new WC_Product($pid);
            $service_name = get_the_title();
            $extension_extr = extractExtension($service_name);

            if ($dominio_extencion == $extension_extr) {
               $variable = explode("-", $product->sku);

               if(count($variable) > 2){
                   if($variable[2] == $periodo) {
                       $datos = [
                           'id' => $pid,
                           "monto" => $product->price,
                           "precio_format" => number_format($product->price, 2, '.', ',')
                       ];
                   }
               }
               else{
                   $datos = ['id' => $pid, "monto" => $product->price, "precio_format" => number_format($product->price, 2, '.', ',')];
               }
            }

        endwhile;

        return $datos;
    }

}

add_action( 'rest_api_init', function () {
    register_rest_route( '/periodisidad_transf/v1', '/dominio_transf', array(
        'methods' => 'POST',
        'callback' => 'periodicidad_transferencias',
    ) );
} );

function periodicidad_transferencias(){

    $periodo = $_POST['periodo'];
    $dominio_extencion = $_POST['dominio_extencion'];

    $args = array(
        'post_type' => 'product',
        'product_cat' => 'Transferencia',
        'posts_per_page' => -1,
        'order_by' => 'title',
        'order' => 'ASC'
    );
    $loop = new WP_Query($args);

    if ($loop->have_posts()) {

        while ($loop->have_posts()) : $loop->the_post();

            $pid = get_the_ID();
            $product = new WC_Product($pid);
            $service_name = get_the_title();
            $extension_extr = extractExtension($service_name);

            if ($dominio_extencion == $extension_extr) {
                $variable = explode("-", $product->sku);

                if(count($variable) > 2){
                    if($variable[2] == $periodo) {
                        $datos = [
                            'id' => $pid,
                            "monto" => $product->price,
                            "precio_format" => number_format($product->price, 2, '.', ',')
                        ];
                    }
                }
                else{
                    $datos = ['id' => $pid, "monto" => $product->price, "precio_format" => number_format($product->price, 2, '.', ',')];
                }
            }

        endwhile;

        return $datos;
    }
}

function extractExtension($name) {


    $pos = strpos($name, '.');

    if (!$pos)
        return false;

    $length_name_domain = strlen($name);

    $extension = substr($name, $pos + 1, $length_name_domain);

    return $extension;
}
/****Funciones upgrade and downgrade****/
function dcms_js_upgrade(){

    wp_register_script('upgrade', get_site_url(). '/wp-content/themes/tecnohost/js/sub_host.js', array('jquery'), '2', true );
    wp_enqueue_script('upgrade');

    wp_localize_script('upgrade','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);
}

function con_plan_sw(){
    global $wpdb;

    $id_ped = absint($_POST['id_ped']);

    $consuta = 'SELECT th_woocommerce_order_items.order_id, th_woocommerce_order_itemmeta.meta_value, th_woocommerce_order_items.order_item_name
    FROM th_woocommerce_order_items INNER JOIN th_woocommerce_order_itemmeta ON th_woocommerce_order_itemmeta.order_item_id = th_woocommerce_order_items.order_item_id
    WHERE th_woocommerce_order_items.order_id = "'.$id_ped.'" AND th_woocommerce_order_itemmeta.meta_key = "_product_id" ORDER BY th_woocommerce_order_itemmeta.order_item_id DESC limit 1';
    $resultado = $wpdb->get_results($consuta);
    $idporducto = $resultado[0]->meta_value;

    $plan = "Plan Actual";
    $produ_info_actual = get_post($idporducto);

    $mensaje = '
        <div class="cont_diferenci">'.$produ_info_actual->post_excerpt.'</div>';

    $datos = [
        'id_producto' => $idporducto,
        'msn' => $plan,
        'mensaje' => $mensaje,
    ];

    echo json_encode($datos);

    wp_die();
}

function dife_plan(){
    global $wpdb;
    $id_ped = absint($_POST['id_ped']);
    $id_prod_act = absint($_POST['id_a_ped']);
    $moneda = saber_moneda();
    $produ_info = get_post($id_ped);
    $precio = pecio_plan($id_ped);
    $precio = number_format($precio, 2, ",", ".");
    $meses = get_post_meta($id_ped, '_subscription_period', true);
    if($meses == "month"){
        $periodo = "por 1 mes";
    }
    else{
        $periodo = "por 1 año";
    }

    $mensaje = '
    <h3 id="ttl_sel">'.$produ_info->post_title.'</h3>
    <span id="money">
        '.saber_moneda().'$'.$precio.'
        <span id="period">'.$periodo.'</span> 
    </span>
    
    <div class="op">
        '.$produ_info->post_excerpt.'
    </div>        
    ';


    $datos = [
        'mensjae' => $mensaje
    ];

    echo json_encode($datos);
    wp_die();

}

function arreglar_monto_renovacion($cart_object){

    foreach($cart_object->cart_contents as $key => $value){
        if($value['subscription_renewal'] && count($value['subscription_renewal']) != 0){
            $renovacion = 1;
            $precio = $value['data']->get_data()['price'];
            break;
        }
        else{
            $renovacion = 0;
            break;
        }
    }

    if($renovacion == 1){
        foreach ( $cart_object->get_cart() as $key => $value ) {
            if($value['subscription_renewal'] && count($value['subscription_renewal']) != 0) {
                $value['data']->set_price($precio);
                $new_price = $value['data']->get_price();
            }
        }
    }
}

function reparar_metas_renovacion($total){
    $cart_contents = [];
    foreach($total->cart_contents as $key => $value){
        if($value['subscription_renewal']['custom_line_item_meta']){
            $id_prdocuto = wc_get_order_item_meta($value['subscription_renewal']['line_item_id'], '_product_id', true);
            $respuesta = manipular_addons($value['subscription_renewal']['custom_line_item_meta'], $id_prdocuto);
            unset($value['subscription_renewal']['custom_line_item_meta']);
            $prueba[$key] = $value;
            if($respuesta) {
                $prueba[$key]['addons'] = $respuesta;
            }
            $total->cart_contents = $prueba;
            return $total;
        }
        else{
            break;
        }
    }
    return $total;
}

function manipular_addons($metas_conver, $id_prdocuto){
    $cont = 0;
    foreach($metas_conver AS $key => $value){

        if($value) {
            $array[] = [
                "name" => $key,
                "value" => $value,
                "price" => 0,
                "field_name" => $id_prdocuto . "-" . str_replace(" ", "-", strtolower($key)) . "-" . $cont,
                "field_type" => "custom_text",
                "price_type" => "flat_fee",
            ];
        }

        $cont++;
    }

    return $array;
}

function cambiar_monto( $total ) {
    foreach($total->cart_contents as $key => $value){

        if($value['subscription_switch'] && count($value['subscription_switch']) != 0){


            foreach($total->cart_contents as $key => $value){
                $id_nuevo_producto = $value['product_id'];
                $val_cate = wc_get_object_terms($id_nuevo_producto,'product_cat', "term_id");
                break;
            }

            if($val_cate[0] == 26){
                $total = manipular_cambios_dominios($total);
            }
            else if($val_cate[0] == "447" ||$val_cate[0] == "448" || $val_cate[0] == "446" || $val_cate[0] == "449"){
                $total = manipular_cambios_vps($total);
            }
            else{
                $total = manipular_cambios($total);
            }
            return $total;
        }
        else if($value['subscription_renewal'] && count($value['subscription_renewal']) != 0){
            $total = evitar_meta_duplicados($total, "subscription_renewal");
            return $total;
        }
        else if($value['subscription_resubscribe'] && count($value['subscription_resubscribe']) != 0){
            $id_prdocuto = wc_get_order_item_meta($value['subscription_resubscribe']['line_item_id'], '_product_id', true);
            $respuesta = manipular_addons($value['subscription_resubscribe']['custom_line_item_meta'], $id_prdocuto);
            unset($value['subscription_resubscribe']['custom_line_item_meta']);
            $prueba[$key] = $value;
            if($respuesta) {
                $prueba[$key]['addons'] = $respuesta;
            }
            $total->cart_contents = $prueba;
            return $total;
        }
        else if($value['subscription_initial_payment'] && count($value['subscription_initial_payment']) != 0){
            $total = evitar_meta_duplicados($total, "subscription_initial_payment");
            return $total;
        }
        else{
            break;
        }
    }

    return $total;

}

function manipular_cambios($total){
    /**
     * Luego definimos la fecha actual
     */
    date_default_timezone_set("America/Caracas");
    $fecha_actual = new DateTime('now');
    foreach($total->cart_contents as $key => $value){
        /***
         * Obtenemos el ID de la suscripción por el cual vamos a realizar el cambio
         */
        $id_nuevo_producto = $value['product_id'];
        /***
         * Ahora obtenemos de los datos del carrito el ID de la suscripción en el cual se esta realizando el cambio de suscripción.
         * Primero sacamos los datos del plan anterior para conocer los días consumidos, los días que faltan por consumir, el total de la orden y
         * la periodisidad del plan.
         */
        $id_pedido = $value['subscription_switch']['subscription_id'];
        if(saber_moneda() == "USD"){
            $tasa_cambio = 1;
        }
        else{
            $wmc_settings                  = get_option( 'woo_multi_currency_params', array() );
            $tasa_cambio = $rate = $wmc_settings['currency_rate'][1];
            $order_item_id = $value['subscription_switch']['item_id'];
        }
        $order_item_id = $value['subscription_switch']['item_id'];
        $suscri_vieja = sus_vieja($id_pedido, $fecha_actual, $tasa_cambio);
        $suscri_nueva = sus_nueva($id_nuevo_producto, $tasa_cambio);
        $nivel = verificar_periodos($suscri_vieja['periodo'], $suscri_nueva['periodo_pro_nuev'], $suscri_nueva['cost_susc_pro_nuev'], $suscri_vieja['cost_susc']);
        $addons = obtener_adicioonales($id_pedido);
        /**
         * Calculamos el costo lo que falta por disfrutar de nuestro plan
         */
        $prec_f_x_consu_x_d = abs($suscri_vieja['cost_x_d'] * ($suscri_vieja['dias_consumidos'] - $suscri_vieja['ca_dias']));
        /***
         * Calculamos la cantidad de días que se goza dentro de la suscripción
         */
        $ca_dias_new = d_interval($suscri_nueva['periodo_pro_nuev'], $suscri_nueva['intervalo_pro_nuev']);
        /**
         * Calculamos el costo del nuevo plan por día
         */
        $cost_x_d_new = ($suscri_nueva['cost_susc_pro_nuev']) / $ca_dias_new;
        $addon_arra = array();

        if($nivel == "upgrade"){
            if($prec_f_x_consu_x_d < $suscri_nueva['cost_susc_pro_nuev']){
                if($suscri_nueva['periodo_pro_nuev'] == 'year'){
                    if($suscri_vieja['dias_consumidos'] >= $ca_dias_new){
                        $prec_f_x_consu_x_d_new = $suscri_nueva['cost_susc_pro_nuev'];
                        $fecha_inicio_pedido = get_post_meta($id_pedido, '_schedule_next_payment', true);
                    }
                    else{
                        $prec_f_x_consu_x_d_new = ($cost_x_d_new * abs($suscri_vieja['dias_consumidos']- $ca_dias_new)) - $prec_f_x_consu_x_d;
                        $fecha_inicio_pedido = get_post_meta($id_pedido, '_schedule_next_payment', true);
                    }
                }
                else{
                    $prec_f_x_consu_x_d_new = ($cost_x_d_new * abs($suscri_vieja['dias_consumidos']- $ca_dias_new))-$prec_f_x_consu_x_d;
                    if($prec_f_x_consu_x_d_new < 0){
                        $diasn = bcdiv(abs($prec_f_x_consu_x_d_new)/$cost_x_d_new,'1', 0);
                        $fecha_inicio_pedido = $suscri_vieja['fecha_inicio_pedido'];
                        $prec_f_x_consu_x_d_new = 0;
                    }
                }
            }
            else{
                $prueba = $ca_dias_new - $suscri_vieja['dias_consumidos'];
                if($prueba < 0){
                    $prueba2 = ($cost_x_d_new * $ca_dias_new)-$prec_f_x_consu_x_d;
                }
                else{
                    $prueba2 = ($cost_x_d_new * $prueba)-$prec_f_x_consu_x_d;
                }
                if($prueba2 < 0){
                    $control = $prueba2;
                    $diasn = abs(bcdiv($control/$cost_x_d_new, '1', 0));
                    $fecha_inicio_pedido = $suscri_vieja['fecha_inicio_pedido'];
                    $prec_f_x_consu_x_d_new = 0;
                }
            }
            $fecha_inicio_pedido = new DateTime($fecha_inicio_pedido);
            $fecha = $fecha_inicio_pedido->format('Y-m-d H:i:s');
            $fecha_inicio_pedido = $fecha;
        }
        else{
            if($suscri_vieja['dias_consumidos'] > $ca_dias_new){
                if($prec_f_x_consu_x_d > $suscri_nueva['cost_susc_pro_nuev']){
                    $control = bcdiv(abs($prec_f_x_consu_x_d - $suscri_nueva['cost_susc_pro_nuev']), '1', 2);
                    $diasn = bcdiv($control/$cost_x_d_new, '1', 0);
                    $fecha_inicio_pedido = new DateTime($suscri_vieja['fecha_inicio_pedido']);
                    $fecha = $fecha_inicio_pedido->format('Y-m-d H:s:i');
                    $nuevafecha = strtotime ('+'.$diasn.' day', strtotime($fecha));
                    $nuevafecha = date ('Y-m-d H:s:i',$nuevafecha);
                    $fecha_inicio_pedido = $nuevafecha;
                    $fecha_inicio_pedido = new DateTime($suscri_vieja['fecha_inicio_pedido']);
                    if($fecha_inicio_pedido->format('Y') <  date ('Y-m-d')){
                        $fecha_inicio_pedido = $fecha;
                    }
                }
                else{
                    $prec_f_x_consu_x_d_new = $suscri_nueva['cost_susc_pro_nuev'] - $prec_f_x_consu_x_d;
                    $fecha = date('Y-m-d H:i:s');
                    $nuevafecha = strtotime ('+'.$suscri_nueva['intervalo_pro_nuev'].$suscri_nueva['periodo_pro_nuev'], strtotime($fecha));
                    $nuevafecha = date ('Y-m-d',$nuevafecha);
                    $fecha_inicio_pedido = $nuevafecha;
                }
            }
            else{
                $control = bcdiv(abs($prec_f_x_consu_x_d - ($cost_x_d_new * ($ca_dias_new - $suscri_vieja['dias_consumidos']))), '1', 2);
                $diasn = bcdiv($control/$cost_x_d_new, '1', 0);
                $prec_f_x_consu_x_d_new = 0;
                $fecha_inicio_pedido = $suscri_vieja['fecha_fin_pedido'];
            }
            $fecha_inicio_pedido = new DateTime($fecha_inicio_pedido);
            $fecha = $fecha_inicio_pedido->format('Y-m-d H:i:s');
            $fecha_inicio_pedido = $fecha;
        }
        $addon_arra[] = Array (
            'name' => "Nombre del Dominio",
            'value' => $addons,
            'price' => 0,
            'field_name' => $id_nuevo_producto."-".str_replace(" ", "-", "Nombre del Dominio")."-0",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        WC()->cart->set_total($prec_f_x_consu_x_d_new);
        WC()->cart->set_subtotal($prec_f_x_consu_x_d_new);
        $total->cart_contents_total = $prec_f_x_consu_x_d_new;
        $total->subtotal            = $suscri_nueva['cost_susc_pro_nuev'];
        $total->cart_session_data['total'] = $prec_f_x_consu_x_d_new;
        $total->cart_session_data['subtotal'] = $prec_f_x_consu_x_d_new;
        $afavor = bcdiv($suscri_vieja['cost_x_d'] * ($suscri_vieja['ca_dias'] - $suscri_vieja['dias_consumidos']), '1', 2);
        $pago_ajuste = bcdiv($prec_f_x_consu_x_d_new, '1', 2);
        $feck_ado_ren = date_i18n( 'F j, Y',strtotime($fecha_inicio_pedido));
        $feck_ado_ = date_i18n( 'Y_m_d',strtotime($fecha_inicio_pedido));
        $addon_arra[] = Array (
            'name' => "Plan Actual",
            'value' => $suscri_vieja['nam_prod_old'],
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Días Consumidos",
            'value' => $suscri_vieja['dias_consumidos'],
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => 'Días por Consumir',
            'value' => $suscri_vieja['ca_dias'] - $suscri_vieja['dias_consumidos'],
            'price' => 0,
            'field_name' => 'ajuste_plan',
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Saldo a Favor",
            'value' => "$" . number_format($afavor, 2),
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Nuevo Plan",
            'value' => $suscri_nueva['name_prod_new'],
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Pago Normal",
            'value' => "$" . number_format($suscri_nueva['cost_susc_pro_nuev'], 2),
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Pago con Ajuste por Saldo",
            'value' =>  "$". number_format($pago_ajuste, 2),
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Nueva Fecha de Renovación",
            'value' => $feck_ado_ren,
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Grupo",
            'value' => "A",
            'price' => 0,
            'field_name' => "grupo",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );

        $recurrin_key = explode("_",$total->recurring_cart_key);
        $Rkey = $feck_ado_."_".$recurrin_key[3]."_".$recurrin_key[4]."_".$recurrin_key[5]."_".$recurrin_key[6]."_".$recurrin_key[7]."_".$recurrin_key[8]."_".$recurrin_key[9]."_".$recurrin_key[10];
        $total->end_date = $fecha_inicio_pedido;
        $total->next_payment_date = $fecha_inicio_pedido;
        $total->recurring_cart_key = $Rkey;
        $total->cart_contents[$key]['addons'] = $addon_arra;
        wc_setcookie("nivel", $nivel,time()+(60*60*24*30));
        wc_setcookie("fecha_new", $fecha_inicio_pedido,time()+(60*60*24*30));
    }
    return $total;
}

function manipular_cambios_vps($total){
    /**
     * Luego definimos la fecha actual
     */
    date_default_timezone_set("America/Caracas");
    $fecha_actual = new DateTime('now');
    foreach($total->cart_contents as $key => $value){
        /***
         * Obtenemos el ID de la suscripción por el cual vamos a realizar el cambio
         */
        $id_nuevo_producto = $value['product_id'];
        $id_nuevo_variation = $value['variation_id'];
        if($id_nuevo_variation != 0){
            $id_nuevo_producto = $id_nuevo_variation;
        }
        /***
         * Ahora obtenemos de los datos del carrito el ID de la suscripción en el cual se esta realizando el cambio de suscripción.
         * Primero sacamos los datos del plan anterior para conocer los días consumidos, los días que faltan por consumir, el total de la orden y
         * la periodisidad del plan.
         */
        $id_pedido = $value['subscription_switch']['subscription_id'];
        if(saber_moneda() == "USD"){
            $tasa_cambio = 1;
        }
        else{
            $tasa_cambio = get_post_meta($id_pedido,"wmc_order_info", true)[wc_get_order($id_pedido)->get_data()['currency']]['rate'];
            $moneda_pedido = get_post_meta($id_pedido,"wmc_order_info", true)[wc_get_order($id_pedido)->get_data()['currency']]['custom'];
            if($moneda_pedido != saber_moneda()){
                $wmc_settings                  = get_option( 'woo_multi_currency_params', array() );
                $tasa_cambio = $rate = $wmc_settings['currency_rate'][1];
            }
            $order_item_id = $value['subscription_switch']['item_id'];
        }
        $order_item_id = $value['subscription_switch']['item_id'];
        $suscri_vieja = sus_vieja($id_pedido, $fecha_actual, $tasa_cambio);
        $suscri_nueva = sus_nueva($id_nuevo_producto, $tasa_cambio);
        $nivel = verificar_periodos($suscri_vieja['periodo'], $suscri_nueva['periodo_pro_nuev'], $suscri_nueva['cost_susc_pro_nuev'], $suscri_vieja['cost_susc']);
        $addons = obtener_adicioonales_vps($id_pedido);
        $addons_prices = obtener_adicioonales_vps_prices($addons);
        $addons_prices = $suscri_nueva['intervalo_pro_nuev'] * $addons_prices;
        $addons_prices = $addons_prices * $tasa_cambio;

        /**
         * Calculamos el costo lo que falta por disfrutar de nuestro plan
         */
        $prec_f_x_consu_x_d = abs($suscri_vieja['cost_x_d'] * ($suscri_vieja['dias_consumidos'] - $suscri_vieja['ca_dias']));
        /***
         * Calculamos la cantidad de días que se goza dentro de la suscripción
         */
        $ca_dias_new = d_interval($suscri_nueva['periodo_pro_nuev'], $suscri_nueva['intervalo_pro_nuev']);
        /**
         * Calculamos el costo del nuevo plan por día
         */
        $cost_x_d_new = ($suscri_nueva['cost_susc_pro_nuev']) / $ca_dias_new;
        $addon_arra = array();
        if($nivel == "upgrade"){
            if($prec_f_x_consu_x_d < $suscri_nueva['cost_susc_pro_nuev']){
                if($suscri_nueva['periodo_pro_nuev'] == 'year'){
                    if($suscri_vieja['dias_consumidos'] >= $ca_dias_new){
                        $prec_f_x_consu_x_d_new = $suscri_nueva['cost_susc_pro_nuev'];
                        $fecha_inicio_pedido = get_post_meta($id_pedido, '_schedule_next_payment', true);
                    }
                    else{
                        $prec_f_x_consu_x_d_new = ($cost_x_d_new * abs($suscri_vieja['dias_consumidos']- $ca_dias_new)) - $prec_f_x_consu_x_d;
                        $fecha_inicio_pedido = get_post_meta($id_pedido, '_schedule_next_payment', true);
                    }
                }
                else{
                    $prec_f_x_consu_x_d_new = ($cost_x_d_new * abs($suscri_vieja['dias_consumidos']- $ca_dias_new))-$prec_f_x_consu_x_d;
                    if($prec_f_x_consu_x_d_new < 0){
                        $diasn = bcdiv(abs($prec_f_x_consu_x_d_new)/$cost_x_d_new,'1', 0);
                        $fecha_inicio_pedido = $suscri_vieja['fecha_inicio_pedido'];
                        $prec_f_x_consu_x_d_new = 0;
                    }
                }
            }
            else{
                $prueba = $ca_dias_new - $suscri_vieja['dias_consumidos'];
                if($prueba < 0){
                    $prueba2 = ($cost_x_d_new * $ca_dias_new)-$prec_f_x_consu_x_d;
                }
                else{
                    $prueba2 = ($cost_x_d_new * $prueba)-$prec_f_x_consu_x_d;
                }
                if($prueba2 < 0){
                    $control = $prueba2;
                    $diasn = abs(bcdiv($control/$cost_x_d_new, '1', 0));
                    $fecha_inicio_pedido = $suscri_vieja['fecha_inicio_pedido'];
                    $prec_f_x_consu_x_d_new = 0;
                }
            }
            $fecha_inicio_pedido = new DateTime($fecha_inicio_pedido);
            $fecha = $fecha_inicio_pedido->format('Y-m-d');
            $fecha_inicio_pedido = $fecha;
        }
        else{
            if($suscri_vieja['dias_consumidos'] > $ca_dias_new){
                if($prec_f_x_consu_x_d > $suscri_nueva['cost_susc_pro_nuev']){
                    $control = bcdiv(abs($prec_f_x_consu_x_d - $suscri_nueva['cost_susc_pro_nuev']), '1', 2);
                    $diasn = bcdiv($control/$cost_x_d_new, '1', 0);
                    $fecha_inicio_pedido = new DateTime($suscri_vieja['fecha_inicio_pedido']);
                    $fecha = $fecha_inicio_pedido->format('Y-m-d H:s:i');
                    $nuevafecha = strtotime ('+'.$diasn.' day', strtotime($fecha));
                    $nuevafecha = date ('Y-m-d H:s:i',$nuevafecha);
                    $fecha_inicio_pedido = $nuevafecha;
                    $fecha_inicio_pedido = new DateTime($suscri_vieja['fecha_inicio_pedido']);
                    if($fecha_inicio_pedido->format('Y') <  date ('Y-m-d')){
                        $fecha_inicio_pedido = $fecha;
                    }
                }
                else{
                    $prec_f_x_consu_x_d_new = $suscri_nueva['cost_susc_pro_nuev'] - $prec_f_x_consu_x_d;
                    $fecha = date('Y-m-d H:i:s');
                    $nuevafecha = strtotime ('+'.$suscri_nueva['intervalo_pro_nuev'].$suscri_nueva['periodo_pro_nuev'], strtotime($fecha));
                    $nuevafecha = date ('Y-m-d',$nuevafecha);
                    $fecha_inicio_pedido = $nuevafecha;
                }
            }
            else{
                $control = bcdiv(abs($prec_f_x_consu_x_d - ($cost_x_d_new * ($ca_dias_new - $suscri_vieja['dias_consumidos']))), '1', 2);
                $diasn = bcdiv($control/$cost_x_d_new, '1', 0);
                $prec_f_x_consu_x_d_new = 0;

            }
            $fecha_inicio_pedido = new DateTime($fecha_inicio_pedido);
            $fecha = $fecha_inicio_pedido->format('Y-m-d');
            $fecha_inicio_pedido = $fecha;
        }
        $fecha_inicio_pedido = $suscri_vieja['fecha_fin_pedido'];
        $fecha_inicio_pedido = new DateTime($fecha_inicio_pedido);
        $fecha = $fecha_inicio_pedido->format('Y-m-d');
        $fecha_inicio_pedido = $fecha;
        $prec_f_x_consu_x_d_new = $suscri_nueva['cost_susc_pro_nuev'];
        if(count($addons) != 0) {
            $contr = 0;
            foreach ($addons as $kye => $vlue) {
                $addon_arra[] = Array (
                    'name' => $kye,
                    'value' => $vlue,
                    'price' => 0,
                    'field_name' => $id_nuevo_producto."-".str_replace(" ", "-", $kye)."-".$contr,
                    'field_type' => 'text',
                    'price_type' => 'flat_fee'
                );
                $contr++;
            }
        }
        WC()->cart->set_total($prec_f_x_consu_x_d_new + $addons_prices);
        WC()->cart->set_subtotal($prec_f_x_consu_x_d_new + $addons_prices);
        $total->cart_contents_total = $prec_f_x_consu_x_d_new + $addons_prices;
        $total->subtotal            = $suscri_nueva['cost_susc_pro_nuev'];
        $total->cart_session_data['total'] = $prec_f_x_consu_x_d_new + $addons_prices;
        $total->cart_session_data['subtotal'] = $prec_f_x_consu_x_d_new + $addons_prices;
        $afavor = bcdiv($suscri_vieja['cost_x_d'] * ($suscri_vieja['ca_dias'] - $suscri_vieja['dias_consumidos']), '1', 2);
        $pago_ajuste = bcdiv($prec_f_x_consu_x_d_new, '1', 2);
        $fecha_fin_pedido = new DateTime($suscri_vieja['fecha_fin_pedido']);
        $hora_vieja = $fecha_fin_pedido->format('H:i:s');
        $nuevafecha = strtotime('+ '.$suscri_nueva['intervalo_pro_nuev'].$suscri_nueva['periodo_pro_nuev'],strtotime($fecha_fin_pedido->format('Y-m-d')));
        $nuevafecha = date ('Y-m-d',$nuevafecha);
        $fecha_nueva_final = $nuevafecha." ".$hora_vieja;
        $feck_ado_ren = date_i18n( 'F j, Y',strtotime($nuevafecha));
        $fecha_antigua_ren = date_i18n( 'F j, Y',strtotime($fecha_fin_pedido->format('Y-m-d')));
        $feck_ado_ = date_i18n( 'Y_m_d',strtotime($fecha_nueva_final));

        $addon_arra[] = Array (
            'name' => "Plan Actual",
            'value' => $suscri_vieja['nam_prod_old'],
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Días Consumidos",
            'value' => $suscri_vieja['dias_consumidos'],
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => 'Días por Consumir',
            'value' => $suscri_vieja['ca_dias'] - $suscri_vieja['dias_consumidos'],
            'price' => 0,
            'field_name' => 'ajuste_plan',
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        /*$addon_arra[] = Array (
            'name' => "Saldo a Favor",
            'value' => "$" . number_format($afavor, 2),
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );*/
        $addon_arra[] = Array (
            'name' => "Nuevo Plan",
            'value' => $suscri_nueva['name_prod_new'],
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        /*$addon_arra[] = Array (
            'name' => "Pago Normal",
            'value' => "$" . number_format($suscri_nueva['cost_susc_pro_nuev'], 2),
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Pago con Ajuste por Saldo",
            'value' =>  "$". number_format($pago_ajuste, 2),
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );*/
        $addon_arra[] = Array (
            'name' => "Fecha de Renovación Original",
            'value' => $fecha_antigua_ren,
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Nueva Fecha de Renovación",
            'value' => $feck_ado_ren,
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Grupo",
            'value' => "A",
            'price' => 0,
            'field_name' => "grupo",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );

        $recurrin_key = explode("_",$total->recurring_cart_key);
        $Rkey = $feck_ado_."_".$recurrin_key[3]."_".$recurrin_key[4]."_".$recurrin_key[5]."_".$recurrin_key[6]."_".$recurrin_key[7]."_".$recurrin_key[8]."_".$recurrin_key[9]."_".$recurrin_key[10];
        $total->end_date = $fecha_inicio_pedido;
        $total->next_payment_date = $fecha_inicio_pedido;
        $total->recurring_cart_key = $Rkey;
        $total->cart_contents[$key]['addons'] = $addon_arra;
        wc_setcookie("nivel", $nivel,time()+(60*60*24*30));
        wc_setcookie("fecha_new", $fecha_inicio_pedido,time()+(60*60*24*30));
    }
    return $total;
}

function actualizar_suscripciones($workflow){
    $subscription = $workflow->data_layer()->get_subscription();
    $items = $subscription->get_items();
    $moneda_de_suscription = $subscription->get_currency();
    $id_suscripcion = $subscription->get_ID();
    $cambio_moneda = WOOMULTI_CURRENCY_Data::get_ins();
    $cambio_moneda = $cambio_moneda->currencies_list;
    foreach( $items as $item_id => $item ){
        $id = $item->get_variation_id();
        if(empty($id)){
            $id = $item->get_product_id();
        }
        $product_prices = wc_get_product($id)->get_price();
    }

    $addons = obtener_adicioonales_vps($id_suscripcion);
    $addons_prices = obtener_adicioonales_vps_prices($addons);
    $new_prices = ($product_prices + $addons_prices) * $cambio_moneda[$moneda_de_suscription]['rate'];
    foreach( $items as $item_id => $item ){
        $item->set_subtotal( $new_prices );
        $item->set_total( $new_prices );
        $item->calculate_taxes();
        $item->save();
    }
    $subscription->calculate_totals();
}

function manipular_cambios_dominios($total){
    /**
     * Luego definimos la fecha actual
     */
    date_default_timezone_set("America/Caracas");
    $fecha_actual = new DateTime('now');

    foreach($total->cart_contents as $key => $value){
        /***
         * Obtenemos el ID de la variación de la suscripción por el cual vamos a realizar el cambio
         */

        $id_nuevo_producto = $value['variation_id'];
        $taxable = get_post_meta($id_nuevo_producto,"_tax_status",true);

        /***
         * Ahora obtenemos de los datos del carrito el ID de la suscripción en el cual se esta realizando el cambio de suscripción.
         * Primero sacamos los datos del plan anterior para conocer los días consumidos, los días que faltan por consumir, el total de la orden y
         * la periodisidad del plan.
         */
        $id_pedido = $value['subscription_switch']['subscription_id'];
        if(saber_moneda() == "USD"){
            $tasa_cambio = 1;
        }
        else{

            if(get_post_meta($id_pedido,"wmc_order_info", true)[wc_get_order($id_pedido)->get_data()['currency']]['custom'] == "USD"){
                $wmc_settings                  = get_option( 'woo_multi_currency_params', array() );
                $tasa_cambio = $rate = $wmc_settings['currency_rate'][1];
            }
            else {
                $tasa_cambio = get_post_meta($id_pedido, "wmc_order_info", true)[wc_get_order($id_pedido)->get_data()['currency']]['rate'];
            }
        }

        $suscri_vieja = sus_vieja($id_pedido, $fecha_actual, $tasa_cambio, true);
        $suscri_nueva = sus_nueva($id_nuevo_producto, $tasa_cambio);
        $monto_nuevo = $suscri_nueva['cost_susc_pro_nuev'];
        $nivel = verificar_periodos($suscri_vieja['periodo'], $suscri_nueva['periodo_pro_nuev'], $suscri_nueva['cost_susc_pro_nuev'], $suscri_vieja['cost_susc']);
        $addons = obtener_adicioonales($id_pedido);
        $fecha_fin_pedido = new DateTime($suscri_vieja['fecha_fin_pedido']);
        $hora_vieja = $fecha_fin_pedido->format('H:i:s');
        $nuevafecha = strtotime('+ '.$suscri_nueva['intervalo_pro_nuev'].$suscri_nueva['periodo_pro_nuev'],strtotime($fecha_fin_pedido->format('Y-m-d')));
        $nuevafecha = date ('Y-m-d',$nuevafecha);
        $fecha_nueva_final = $nuevafecha." ".$hora_vieja;
        $feck_ado_ren = date_i18n( 'F j, Y',strtotime($nuevafecha));
        $fecha_antigua_ren = date_i18n( 'F j, Y',strtotime($fecha_fin_pedido->format('Y-m-d')));
        $feck_ado_ = date_i18n( 'Y_m_d',strtotime($fecha_nueva_final));

        $plan_actual = str_replace("ano","año",$suscri_vieja['nam_prod_old']);
        $plan_actual = str_replace("anos","años",$plan_actual);
        $plan_nuevo = str_replace("ano","año",$suscri_nueva['name_prod_new']);
        $plan_nuevo = str_replace("anos","años",$plan_nuevo);

        $addon_arra[] = Array (
            'name' => "Nombre del Dominio",
            'value' => $addons,
            'price' => 0,
            'field_name' => $id_nuevo_producto."-".str_replace(" ", "-", "Nombre del Dominio")."-0",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Plan Actual",
            'value' => $plan_actual,
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Nuevo Plan",
            'value' => $plan_nuevo,
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Pago Normal",
            'value' => "$" . number_format($suscri_nueva['cost_susc_pro_nuev'], 2),
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Fecha de Renovación Original",
            'value' => $fecha_antigua_ren,
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Nueva Fecha de Renovación",
            'value' => $feck_ado_ren,
            'price' => 0,
            'field_name' => "ajuste_plan",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $addon_arra[] = Array (
            'name' => "Grupo",
            'value' => "A",
            'price' => 0,
            'field_name' => "grupo",
            'field_type' => 'text',
            'price_type' => 'flat_fee'
        );
        $monto_subtotal = $monto_nuevo;
        if("taxable" == $taxable){
            $impuestos = taxes_cambi_dom($monto_nuevo);
            $monto_nuevo = $impuestos['precio'];
            $total->cart_session_data['subtotal_ex_tax'] = $impuestos['iva'];
            $total->cart_session_data['tax_total'] = $impuestos['iva'];
            $total->cart_contents[$key]['line_tax_data'] = ['subtotal' => [1=>$impuestos['iva']],'total' => [1=>$impuestos['iva']]];
            $total->cart_contents[$key]['line_tax'] = $impuestos['iva'];
            $total->cart_contents[$key]['line_subtotal_tax'] = $impuestos['iva'];
            WC()->cart->set_subtotal_tax($impuestos['iva']);
            WC()->cart->set_total_tax($impuestos['iva']);
            WC()->cart->set_cart_contents_taxes([$impuestos['iva']]);
        }

        $recurrin_key = explode("_",$total->recurring_cart_key);
        $Rkey = $feck_ado_."_".$recurrin_key[3]."_".$recurrin_key[4]."_".$recurrin_key[5]."_".$recurrin_key[6]."_".$recurrin_key[7]."_".$recurrin_key[8]."_".$recurrin_key[9]."_".$recurrin_key[10];
        $total->cart_session_data['total'] = $monto_nuevo;
        $total->cart_session_data['subtotal'] = $monto_nuevo;
        $total->cart_session_data['cart_contents_total'] = $monto_nuevo;
        $total->cart_contents[$key]['addons'] = $addon_arra;
        $total->cart_contents[$key]['line_subtotal'] = $monto_subtotal;
        $total->cart_contents[$key]['line_subtotal'] = $monto_subtotal;
        $total->cart_contents[$key]['line_total'] = $monto_nuevo;
        WC()->cart->set_total($monto_nuevo);
        WC()->cart->set_subtotal($monto_subtotal);
        wc_setcookie("nivel", $nivel,time()+(60*60*24*30));
    }

    if("taxable" == $taxable){
        $total->cart_contents_total->total_tax = $impuestos['iva'];
        $total->removed_cart_contents->total_tax = $impuestos['iva'];
    }
    $total->end_date = $fecha_nueva_final;
    $total->next_payment_date = $fecha_nueva_final;
    $total->recurring_cart_key = $Rkey;
    $total->cart_contents_tota['subtotal'] = $monto_subtotal;
    $total->cart_contents_subtotal = $monto_subtotal;
    $total->cart_contents_subtotal_ex_tax = $monto_subtotal;
    $total->cart_contents_tax_total = $monto_subtotal;
    $total->cart_contents_total = $monto_nuevo;
    $total->removed_cart_contents->subtotal = $monto_subtotal;
    $total->removed_cart_contents_total = $monto_nuevo;
    $total->removed_cart_contents_subtotal_ex_tax = $monto_subtotal;
    $total->removed_cart_contents_tax_total = $monto_subtotal;
    $total->removed_cart_contents = $monto_nuevo;
    return $total;
}

function obtener_adicioonales_vps($order_item_id){
    $order = wc_get_order($order_item_id);
    $metas = [];
    $items = $order->get_items();
    foreach ( $items as $item ) {
        foreach($item->get_meta_data() as $key => $value){
            switch(explode(" (USD",$value->key)[0]){
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

function obtener_adicioonales_vps_prices($addons){
    $precio_addons = 0;
    foreach($addons AS $key => $value){
        $patron = '/\((?:\D*\s*(\d+(?:[\.,]\d+)?)[^\)]*\s*)\)/';
        preg_match_all($patron, $key, $matches);
        $precio = $matches[1][0];
        $precio = str_replace(".","",$precio);

        if(!empty($precio)){
            $precio_addons = $precio_addons + $precio;
        }
    }

    return $precio_addons;
}

function taxes_cambi_dom($precio){
    $rates = WC_Tax::get_rates();
    $location = WC_Geolocation::geolocate_ip();
    $customer = new WC_Customer(get_current_user_id());

    if ( is_user_logged_in() ) {
        $country = WC()->session->get('customer')['shipping_country'];
    }
    else{
        $country = $location['country'];
    }

    foreach ($rates AS $key => $value){
        $base_iva = $value['rate'];
    }
    if($country == "CO") {
        $iva = ($precio * $base_iva) / 100;
        $precio = $precio + $iva;
    }
    else{
        $iva = 0;
        $precio = $precio + $iva;
    }

    return [
        'precio' => $precio,
        'iva' => $iva
    ];
}

function id_product_variation_orden($order_id){
    $order = wc_get_order( $order_id );
    $items = $order->get_items();

    foreach ( $items as $item ) {
        $product_id = $item->get_variation_id();
        break;
    }

    return $product_id;
}

function sus_vieja($id_pedido, $fecha_actual, $tasa_cambio, $dominio = false){

    $id_product_viejo = id_product_orden($id_pedido);
    if($dominio == 1){
        $id_product_viejo = id_product_variation_orden($id_pedido);
    }

    /**
     * Luego consultamos la fecha de inicio del pedido que es la fecha con la que vamos a poder conocer cuantos
     * días han transcurrido.
     * Obtenemos la periodicidad de la suscripción dentro de la orden.
     * Obtenemos el intervalo de la orden.
     */
    /*$fecha_inicio_pedido = get_post_meta($id_pedido, '_schedule_start', true);*/
    $fecha_fin_pedido = get_post_meta($id_pedido, '_schedule_next_payment', true);
    $fecha_fin_pedido_2 = new DateTime($fecha_fin_pedido);
    $periodo = get_post_meta($id_pedido, '_billing_period', true);
    $intervalo = get_post_meta($id_pedido, '_billing_interval', true);
    $hora_vieja = $fecha_fin_pedido_2->format('H:i:s');
    $nuevafecha = strtotime('- '.$intervalo.$periodo,strtotime($fecha_fin_pedido_2->format('Y-m-d')));
    $nuevafecha = date ('Y-m-d',$nuevafecha);
    $fecha_inicio_pedido = $nuevafecha." ".$hora_vieja;
    /***
     * Sacamos la cantidad de días que se ha disfrutdao de la suscripción
     */
    $dias_consumidos = sacar_diferencia_d($fecha_inicio_pedido, $fecha_actual);
    /**
     * Obtenemos el total de la orden.
     */
    //$cost_susc = pecio_plan($id_product_viejo);
    $cost_susc = get_post_meta($id_pedido, '_order_total', true);
    $order_currency = get_post_meta($id_pedido, '_order_currency', true);
    $ped_old = conocer_periodo($periodo);
    if($order_currency == saber_moneda()){
        $cost_susc = $cost_susc;
    }
    else{
        $cost_susc = $cost_susc * $tasa_cambio;
    }
    /*Nombre correspondiente a la suscrpción vieja*/
    $nam_prod_old = get_the_title( $id_product_viejo ). " / ".$ped_old;

    /***
     * Calculamos la cantidad de días que se goza dentro de la suscripción
     */
    $ca_dias = d_interval($periodo, $intervalo);

    /**
     * Calculamos el costo del plan por día
     */
    $cost_x_d = $cost_susc / $ca_dias;

    /**
     * Calculamos el costo consumido de nuestro plan
     */
    $prec_consu_x_d = $cost_x_d * $dias_consumidos;

    $datos = array(
        "id_product_viejo" => $id_product_viejo,
        "fecha_inicio_pedido" => $fecha_inicio_pedido,
        "dias_consumidos" => $dias_consumidos,
        "periodo" => $periodo,
        "intervalo" => $intervalo,
        "nam_prod_old" => $nam_prod_old,
        "cost_susc" => $cost_susc,
        "prec_consu_x_d" => $prec_consu_x_d,
        "fecha_fin_pedido" => $fecha_fin_pedido,
        "cost_x_d" => $cost_x_d,
        "ca_dias" => $ca_dias,
    );

    return $datos;
}

function sus_nueva($id_nuevo_producto, $tasa_cambio){
    /**
     * Obtenemos la periodicidad de la suscripción nueva
     * Obtenemos el intervalo de la suscripción nueva.
     * Obtenemos el total de la suscripción nueva.
     */
    $periodo_pro_nuev = get_post_meta($id_nuevo_producto, '_subscription_period', true);
    $intervalo_pro_nuev = get_post_meta($id_nuevo_producto, '_subscription_period_interval', true);
    $cost_susc_pro_nuev = pecio_plan($id_nuevo_producto);
    $ped = conocer_periodo($periodo_pro_nuev);
    $name_prod_new = get_the_title( $id_nuevo_producto ). " / ".$ped;
    $cost_susc_pro_nuev = $cost_susc_pro_nuev * $tasa_cambio;

    $datos = array(
            "periodo_pro_nuev" => $periodo_pro_nuev,
            "intervalo_pro_nuev" => $intervalo_pro_nuev,
            "cost_susc_pro_nuev" => $cost_susc_pro_nuev,
            "name_prod_new" => $name_prod_new,
    );

    return $datos;
}

function conocer_periodo($periodo){
    switch ($periodo){
        case"month":
            $ped_old = "mensual";
            break;
        case"year":
            $ped_old = "anual";
            break;
    }
    return $ped_old;
}

function evitar_meta_duplicados($total, $key_duplicados){
    foreach($total->cart_contents as $key => $value){
        unset($value[$key_duplicados]['custom_line_item_meta']);
        foreach($value['addons'] AS $key_2 => $value_2){
            if(substr($value_2['name'],0,1) != "_") {
                $addons[$key_2]['value'] = str_replace("-", ".", $value_2['value']);
                $addons[$key_2]['name'] = $value_2['name'];
                $addons[$key_2]['price'] = $value_2['price'];
                $addons[$key_2]['field_name'] = $value_2['field_name'];
                $addons[$key_2]['field_type'] = $value_2['field_type'];
                $addons[$key_2]['price_type'] = $value_2['price_type'];
            }
        }
        $prueba[$key] = $value;
        $prueba[$key]['addons'] = $addons;
    }

    $total->cart_contents = $prueba;

    return $total;
}

function despues_pago($order_id){
    global $wpdb;

    $addons_prices = 0;
    $control = 0;
    $costo = get_post_meta($order_id, '_order_total', true);
    $order = wc_get_order( $order_id );
    if($_COOKIE['nivel']){
        $control = 1;
        $costo = get_post_meta($order_id, '_order_total', true);
        $pais = get_post_meta($order_id, '_billing_country', true);
        $order = wc_get_order( $order_id );
        $addons_prices = 0;
        if($_COOKIE['nivel'] == "downgrade") {
            $control = 0;
            foreach( $order->get_items() as $item_id => $item ){
                $categoria = wc_get_object_terms(wc_get_order_item_meta($item->get_id(),'_product_id',true),'product_cat','term_id');
                if($categoria[0] == 26){
                    if($pais == "CO"){
                        $costo = wc_get_product(wc_get_order_item_meta($item->get_id(),'_variation_id',true))->get_price();
                    }
                    $new_line_item_price = $costo;
                    $item->set_subtotal( $new_line_item_price );
                    $item->set_total( $new_line_item_price );
                    $item->calculate_taxes();
                    $item->save();
                    $control = 1;
                    echo '
                        <div id="loader">
                            <div class="spinner">
                                <div class="loaders l1"></div>
                                <div class="loaders l2"></div>
                            </div>
                        </div>
                        <script>location.reload();</script>';
                    break;
                }
            }
            if($categoria[0] == 26) {
                $order->calculate_totals();
            }
        }
        else if($_COOKIE['nivel'] == "upgrade"){
            $control = 0;
            $addons = obtener_adicioonales_vps($order_id);
            $addons_prices = obtener_adicioonales_vps_prices($addons);
            foreach( $order->get_items() as $item_id => $item ){
                $new_line_item_price = $costo + $addons_prices;
                $item->set_subtotal( $new_line_item_price );
                $item->set_total( $new_line_item_price );
                $item->calculate_taxes();
                $item->save();
            }
            $order->calculate_totals();
            wc_setcookie("nivel", 'cambio_listo',time()+(60*60*24*30));
            echo '
                <div id="loader">
                    <div class="spinner">
                        <div class="loaders l1"></div>
                        <div class="loaders l2"></div>
                    </div>
                </div>
                <script>location.reload();</script>';
        }
        else if($_COOKIE['nivel'] == "cambio_listo"){
            $control = 0;
            return;
        }
        else{
            $addons = obtener_adicioonales_vps($order_id);
            $addons_prices = obtener_adicioonales_vps_prices($addons);
            foreach( $order->get_items() as $item_id => $item ){
                $new_line_item_price = $costo + $addons_prices;
                $item->set_subtotal( $new_line_item_price );
                $item->set_total( $new_line_item_price );
                $item->calculate_taxes();
                $item->save();
            }
            $order->calculate_totals();
            $control = 1;
        }

    }
    else{
        $addons = obtener_adicioonales_vps($order_id);
        $addons_prices = obtener_adicioonales_vps_prices($addons);
        if(empty(get_post_meta($order_id,"_subscription_renewal_early",true))){
            return;
        }
        foreach( $order->get_items() as $item_id => $item ){
            $categoria = wc_get_object_terms(wc_get_order_item_meta($item->get_id(),'_product_id',true),'product_cat','term_id');

            switch($categoria[0]) {
                case 448:
                case 449:
                case 446:
                case 447:
                    break;
                default:
                    return 0;
                    break;
            }

            $product_prices = wc_get_product(wc_get_order_item_meta($item->get_id(),'_variation_id',true))->get_price();
            if($product_prices == 0){
                $product_prices = wc_get_product(wc_get_order_item_meta($item->get_id(),'_product_id',true))->get_price();
            }
            $product_prices = $product_prices + $addons_prices;
            $new_line_item_price = $costo + $addons_prices;
            if($product_prices == $new_line_item_price){
                $control = 0;
            }
            if($product_prices != $new_line_item_price){
                $new_line_item_price = $product_prices;
                $control = 0;
            }
            $item->set_subtotal( $new_line_item_price );
            $item->set_total( $new_line_item_price );
            $item->calculate_taxes();
            $item->save();
        }
        $order->calculate_totals();
    }

    if ($control == 1) {
        echo '
                <div id="loader">
                    <div class="spinner">
                        <div class="loaders l1"></div>
                        <div class="loaders l2"></div>
                    </div>
                </div>
                <script>location.reload();</script>';
    }
}

function d_interval($periodo, $intervalo){
    switch ($periodo){
        case'week':
            $ca_dias = 7 * $intervalo;
            break;
        case'month':
            $ca_dias = 30 * $intervalo;
            break;
        case'year':
            $ca_dias = 365 * $intervalo;
            break;
    }

    return $ca_dias;
}

function verificar_periodos($periodo, $periodo_pro_nuev, $cost_susc_pro_nuev, $cost_susc){

    switch ($periodo){
        case'week':
            $valor = 0;
            break;
        case'month':
            $valor = 1;
            break;
        case'year':
            $valor = 2;
            break;
    }

    switch ($periodo_pro_nuev){
        case'week':
            $valor_new = 0;
            break;
        case'month':
            $valor_new = 1;
            break;
        case'year':
            $valor_new = 2;
            break;
    }

    if($valor < $valor_new){
        if($cost_susc < $cost_susc_pro_nuev){
            $responder = "upgrade";
        }
        else if($cost_susc > $cost_susc_pro_nuev){
            $responder = "downgrade";
        }
    }
    else if($valor > $valor_new){
        if($cost_susc < $cost_susc_pro_nuev){
            $responder = "upgrade";
        }
        else if($cost_susc > $cost_susc_pro_nuev){
            $responder = "downgrade";
        }
    }
    else if($valor == $valor_new){
        if($cost_susc < $cost_susc_pro_nuev){
            $responder = "upgrade";
        }
        else if($cost_susc > $cost_susc_pro_nuev){
            $responder = "downgrade";
        }
    }

    return $responder;
}

function sacar_diferencia_d($fecha_inicio_pedido, $fecha_actual){
    $fecha_inicio_pedido = new DateTime($fecha_inicio_pedido);
    $diferencia = $fecha_inicio_pedido->diff($fecha_actual);

    return $diferencia->days;
}

function lm_car_sess($cart_item_key, $prec_f_x_consu_x_d_new){
    global $wpdb;
    $consulta = "SELECT * FROM `th_woocommerce_sessions` WHERE `session_value` LIKE '%" . $cart_item_key . "%'";

    $resultado_2 = $wpdb->get_results($consulta);
    $manipular = $resultado_2[0]->session_value;
    $id_session = $resultado_2[0]->session_id;
    $princilao = unserialize($manipular);

    $vamosliampiar = unserialize($princilao['cart_totals']);

    foreach ($vamosliampiar AS $key => $value) {
        $vamosliampiar['subtotal'] = $prec_f_x_consu_x_d_new;
        $vamosliampiar['total'] = $prec_f_x_consu_x_d_new;
        $vamosliampiar['cart_contents_total'] = $prec_f_x_consu_x_d_new;
    }

    $princilao['cart_totals'] = serialize($vamosliampiar);

    $carro = unserialize($princilao['cart']);

    foreach ($carro AS $key => $value) {
        $carro[$key]['line_subtotal'] = $prec_f_x_consu_x_d_new;
        $carro[$key]['line_total'] = $prec_f_x_consu_x_d_new;
    }

    $princilao['cart'] = serialize($carro);
    $princilao = serialize($princilao);
    $wpdb->update('th_woocommerce_sessions', array('session_value' => $princilao), array('session_id' => $id_session));
}

function sacar_meses($prec_f_x_consu_x_d, $cost_susc_pro_nuev) {
    $dias = round(($prec_f_x_consu_x_d/$cost_susc_pro_nuev)*30);
    $fecha = date('Y-m-d');
    $nuevafecha = strtotime ('+'.$dias.' day', strtotime($fecha));
    $nuevafecha = date ('Y-m-d',$nuevafecha);

    return $nuevafecha;
}

function id_product_orden($order_id){
    $order = wc_get_order( $order_id );
    $items = $order->get_items();

    foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
    }

    return $product_id;
}

function obtener_adicioonales($order_item_id){

    $order = wc_get_order($order_item_id);
    $dom_dat = "";
    $items = $order->get_items();
    foreach ( $items as $item ) {
        foreach($item->get_meta_data() as $key => $value){
            if($value->key == "Nombre del Dominio" || $value->key == "Dominio"){
                if($value->value != ""){
                    $dom_dat = $value->value;
                    break;
                }
            }
        }
    }

    return $dom_dat;
}

function imprimir($datos){
    echo '<pre>';
    print_r($datos);
    echo '</pre>';
}

function pecio_plan($id_ped){
    $precio = get_post_meta($id_ped, '_price', true);
    return $precio;
}

function validar_usuario( $url ) {

    return wc_get_cart_url();
}
/****Funciones upgrade and downgrade****/
function descu_prod(){
    global $wpdb;
    $moneda = get_woocommerce_currency();

    $sql = "SELECT
                parnt1.ID
            FROM
                th_term_relationships
            INNER JOIN th_posts ON th_posts.ID = th_term_relationships.object_id
            AND th_posts.ID = th_term_relationships.object_id
            INNER JOIN th_posts AS parnt1 ON (
                parnt1.post_parent = th_posts.ID
            )
            WHERE
                th_term_relationships.term_taxonomy_id = '26'
            AND th_posts.post_type = 'product'
            AND parnt1.post_type = 'product_variation'
            AND parnt1.menu_order = '1'
            AND th_posts.post_status = 'publish'
            ORDER BY
                th_posts.menu_order ASC";
    $suscr = $wpdb->get_results($sql, ARRAY_A);

    $html = '
        <div class="storefront-pricing-column highlight" id="th_tbla_dom">
            <h2 class="column-title" id="invicible">Extención de Dominio</h2>
            <ul class="features">
                <li>false</li>
            </ul>
            <p class="product woocommerce add_to_cart_inline " style="border:4px solid #ccc; padding: 12px;">
                <span class="th_registro">Registro</span>
                <span class="th_renovacion">Renovación</span>
                <span class="th_transferencia">Transferencia</span>
            </p>
        </div>
    ';

    foreach($suscr as $key => $value){
        $id_producto = $value['ID'];
        $variation = wc_get_product($id_producto);
        $titulo = $variation->get_title();
        if(strlen($titulo) != 0){
            $stract_dom = str_replace("Dominio ", "", $variation->get_title());
            $stract_dom = str_replace(".", "_", $stract_dom);
            $precio_regular = $variation->get_price();
            $precio_transferencia = get_post_meta($variation->get_parent_id(), "transferencia_costo_".$moneda, true);
            $precio_registro = get_post_meta($variation->get_parent_id(), "registro_costo_".$moneda, true);
            if($precio_transferencia == ""){
                $precio_transferencia = 0;
            }
            if($precio_registro == ""){
                $precio_registro = 0;
            }
            $transferencia = number_format(($precio_regular + $precio_transferencia), "2", ",", ".");
            $renovacion = number_format($precio_regular, "2", ",", ".");
            $registro = number_format($precio_regular - $precio_registro, "2", ",", ".");

            $html .= '
            <div class="storefront-pricing-column highlight">
                <h2 class="column-title" id="'.$stract_dom.'">
                    '.$titulo.'
                </h2>
                <ul class="features">
                    <li>false</li>
                </ul>
                <p class="product woocommerce add_to_cart_inline " style="border:4px solid #ccc; padding: 12px;">
                    <span class="woocommerce-Price-amount amount">
                        '.$moneda.' $'.$registro.'
                        <span class="subscription-details">
                            /año
                        </span>
                    </span>
                    <span class="woocommerce-Price-amount amount">
                        '.$moneda.' $'.$renovacion.'
                        <span class="subscription-details">
                            /año
                        </span>
                    </span>
                    <span class="woocommerce-Price-amount amount">
                        '.$moneda.' $'.$transferencia.' 
                        <span class="subscription-details">
                            /año
                        </span>
                    </span>
                    <span class="woocommerce-Price-amount amount" style="display: none;">
                        <bdi>
                            USD
                            <span class="woocommerce-Price-currencySymbol">
                                $
                            </span>
                            16,50
                        </bdi>
                    </span> 
                    <span class="subscription-details" style="display: none;">
                        /año
                    </span>
                    <a href="/contratacion/?category=transferencia" class="button" id="transf">
                        Transferir
                    </a>
                    <a href="/contratacion/?category=registro" class="button" id="regist">
                        Registrar
                    </a>
                </p>
            </div>
        ';
        }
    }

    $datos = [
        'elementos' => $html
    ];

    echo json_encode($datos);

    wp_die();

}

function disable_coupons_for_subscription_products( $is_valid, $coupon, $discount ){
    // Loop through cart items
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        // Check for subscription products
        if( in_array( $cart_item['data']->get_type(), array('subscription', 'subscription_variation') ) ) {
            $is_valid = false; // Subscription product found: Make coupons "not valid"
            break; // Stop and exit from the loop
        }
    }
    return $is_valid;
}
/**
 * @param array $rules
 * @return array
 */
function my_automatewoo_rules( $rules ) {

    $rules['my_unique_rule_id_one'] = dirname(__FILE__) . '/includes/rule.php';
    return $rules;
}

function validar_stripe($rules){
    $rules['validar_stripe'] = dirname(__FILE__) . '/includes/validar_stripe.php'; // absolute path to rule
    return $rules;
}

function ajustes_mes_deposito($variables){
    $variables['subscription']['ajustes_mes_deposito'] = dirname(__FILE__) . '/includes/ajustes_mes_deposito.php';
    return $variables;
}

function mensajes_ajustes_mes($variables){
    $variables['subscription']['mensajes_ajustes_mes'] = dirname(__FILE__) . '/includes/mensajes_ajustes_mes.php';
    return $variables;
}

function nota_administrador($rules){
    $rules['susb_note_id'] = dirname(__FILE__) . '/includes/suscription_nota_administrador.php'; // absolute path to rule
    return $rules;
}

function validar_notas_usuario( $rules ) {

    $rules['my_unique_rule_id'] = dirname(__FILE__) . '/includes/validar_usuarios_nota.php'; // absolute path to rule
    return $rules;
}

function item_name_s( $variables ) {
    // variable's string form is set here, it will be order.pluralize
    $variables['subscription']['subscription_name'] = dirname(__FILE__) . '/includes/suscription_item_name.php';

    return $variables;
}

function item_code( $variables ) {
    // variable's string form is set here, it will be order.pluralize
    $variables['subscription']['subscription_code'] = dirname(__FILE__) . '/includes/suscription_item_code.php';

    return $variables;
}

function item_domain( $variables ) {
    // variable's string form is set here, it will be order.pluralize
    $variables['subscription']['subscription_dominio'] = dirname(__FILE__) . '/includes/suscription_item_dominio.php';

    return $variables;
}

function item_interval( $variables ) {
    // variable's string form is set here, it will be order.pluralize
    $variables['subscription']['subscription_intervalo'] = dirname(__FILE__) . '/includes/suscription_item_intervalo.php';

    return $variables;
}

function item_period( $variables ) {
    // variable's string form is set here, it will be order.pluralize
    $variables['subscription']['subscription_periodo'] = dirname(__FILE__) . '/includes/suscription_item_periodo.php';

    return $variables;
}

function item_currency( $variables ) {
    // variable's string form is set here, it will be order.pluralize
    $variables['subscription']['subscription_currency'] = dirname(__FILE__) . '/includes/suscription_item_currency.php';

    return $variables;
}

function order_suscription_id( $variables ) {
    // variable's string form is set here, it will be order.pluralize
    $variables['subscription']['subscription_order_id'] = dirname(__FILE__) . '/includes/order_suscription_id.php';

    return $variables;
}

function url_administra_plataformas($variables){
    $variables['subscription']['subscription_url_administra_plataformas'] = dirname(__FILE__) . '/includes/url_administracion_suscription.php';
    return $variables;
}

function usuario_plataformas($variables){
    $variables['subscription']['subscription_usuario_plataformas'] = dirname(__FILE__) . '/includes/usuario_plataforma_suscription.php';
    return $variables;
}

function clave_plataformas($variables){
    $variables['subscription']['subscription_clave_plataformas'] = dirname(__FILE__) . '/includes/clave_plataforma_suscription.php';
    return $variables;
}

function email_plataformas($variables){
    $variables['subscription']['subscription_email_plataformas'] = dirname(__FILE__) . '/includes/email_plataforma_suscription.php';
    return $variables;
}

function hosting_espacio($variables){
    $variables['subscription']['subscription_hosting_espacio'] = dirname(__FILE__) . '/includes/hosting_espacio_suscription.php';
    return $variables;
}

function hosting_ancho_banda($variables){
    $variables['subscription']['subscription_hosting_ancho_banda'] = dirname(__FILE__) . '/includes/hosting_ancho_banda_suscription.php';
    return $variables;
}

function hosting_usuario($variables){
    $variables['subscription']['subscription_hosting_usuario'] = dirname(__FILE__) . '/includes/hosting_usuario_suscription.php';
    return $variables;
}

function hosting_clave($variables){
    $variables['subscription']['subscription_hosting_clave'] = dirname(__FILE__) . '/includes/hosting_clave_suscription.php';
    return $variables;
}

function hosting_dns_uno($variables){
    $variables['subscription']['subscription_hosting_dns_uno'] = dirname(__FILE__) . '/includes/hosting_dns_uno_suscription.php';
    return $variables;
}

function hosting_dns_dos($variables){
    $variables['subscription']['subscription_hosting_dns_dos'] = dirname(__FILE__) . '/includes/hosting_dns_dos_suscription.php';
    return $variables;
}

function hosting_raiz($variables){
    $variables['subscription']['subscription_hosting_raiz'] = dirname(__FILE__) . '/includes/hosting_raiz_suscription.php';
    return $variables;
}

function hosting_email($variables){
    $variables['subscription']['subscription_hosting_email'] = dirname(__FILE__) . '/includes/hosting_email_suscription.php';
    return $variables;
}

function hosting_ip_dns_uno($variables){
    $variables['subscription']['subscription_hosting_ip_dns_uno'] = dirname(__FILE__) . '/includes/hosting_ip_dns_uno_suscription.php';
    return $variables;
}

function hosting_ip_dns_dos($variables){
    $variables['subscription']['subscription_hosting_ip_dns_dos'] = dirname(__FILE__) . '/includes/hosting_ip_dns_dos_suscription.php';
    return $variables;
}

function hosting_ip_server($variables){
    $variables['subscription']['subscription_hosting_ip_server'] = dirname(__FILE__) . '/includes/hosting_ip_server_suscription.php';
    return $variables;
}

function price_product($variables){
    $variables['subscription']['subscription_price'] = dirname(__FILE__) . '/includes/suscription_item_price.php';
    return $variables;
}

function id_renovacio($variables){
    $variables['subscription']['id_renova'] = dirname(__FILE__) . '/includes/suscription_id_renovacio.php';
    return $variables;
}

function date_start($variables){
    $variables['subscription']['date_start'] = dirname(__FILE__) . '/includes/suscription_date_start.php';
    return $variables;
}

function njengah_default_checkout_country( $country ) {
    $country = WC()->session->get('customer')['shipping_country'];
    $location = WC_Geolocation::geolocate_ip();
    $customer = new WC_Customer(get_current_user_id());

    return $country;
}

function load_scripts() {
    global $post;
    if( is_page() ) {
        switch($post->ID)  {
            case '81652':
            case '81702':
            case '81703':
            case '81701':
            case '81709':
            case '81650':
            case '81648':
            case '81646':
            case '81659':
            case '81660':
            case '97012':
            case '98376':
            case '98370':
            case '96999':
                wp_enqueue_style( 'procesos', get_site_url(). '/wp-content/themes/tecnohost/css/procesos.css');
                wp_register_script('process_script', get_site_url(). '/wp-content/themes/tecnohost/js/procesos_compra.js', array('jquery'), '1', true );
                wp_enqueue_script('process_script');
                wp_localize_script('process_script','procesos',['ajaxurl'=>admin_url('admin-ajax.php')]);
            break;
        }
    }
}
/**
 * Setup the entry user (the created_by field of an entry).
 *
 * @param array $new_entry The entry object.
 * @param WC_Order $order WooCommerce order object.
 * @param array $form The Form object.
 *
 * @return array
 *
 * En este paso vamos a asignar las variables que necesitamos extraer de cada suscripción bien sea el caso. Este proceso se ejecuta cuando el estado del pedido corresponde con el disparador del formulario.
 * Para conocer el disparador se debe ir a la opción de woocommerce de cada formulario asociado al pedido
 *
 *
 *  ID de Formularios
 * 8 Crear Hosting
 * 10 Renovar Dominios
 * 9 Crear Dominios
 * 11 Renovar Hosting
 */
function crear_entrada_por_estado_pedido( $new_entry, $order, $form ) {
    $order_id = $new_entry['workflow_woocommerce_order_id'];
    $args = array(
        'post_parent' => $order_id,
        'post_type' => 'shop_subscription',
        'numberposts' => -1,
        'post_status' => 'open'
    );

    $suscripcion_conta = 0;
    $pedidosimple_conta = 0;
    $id_pedido = $new_entry[1];
    $order = wc_get_order($id_pedido);
    foreach ($order->get_items() as $item_id => $item) {
        $prod_cat_type = wc_get_object_terms( $item->get_data()['product_id'], 'product_type');
        foreach($prod_cat_type as $key_planes => $value_planes) {
            if($value_planes->name == "subscription" || $value_planes->name == "Variable Subscription"){
                $suscripcion_conta++;
            }
            else if($value_planes->name == "simple"){
                $pedidosimple_conta++;
            }
        }
    }

    if($suscripcion_conta != 0) {
        if($form['id'] == "10" ){
            $id_suscripcion = get_post_meta($order_id, "_subscription_renewal", true);
            $order_suscripcion = wc_get_order($id_suscripcion);
            foreach ($order_suscripcion->get_items() as $item_id => $item) {
                $product_id = $item->get_data()['product_id'];
                $prod_cat_args = wc_get_object_terms( $item->get_data()['product_id'], 'product_cat', 'term_id' , true);
                if($prod_cat_args['0'] == "26"){
                    $tipo = $item->get_name();
                    $nombre_dominio = wc_get_order_item_meta($item_id, 'Nombre del Dominio', true);
                    $new_entry['8'] = $id_suscripcion;
                    $new_entry['10'] = $tipo;
                    $new_entry['11'] = $nombre_dominio;
                    $new_entry['15'] = $order_suscripcion->get_billing_first_name();
                    $new_entry['16'] = $order_suscripcion->get_billing_last_name();
                    $new_entry['17'] = $order_suscripcion->get_billing_company();
                    $suscripciones_varias[$id_suscripcion] = $id_suscripcion;
                    $crear_n[$id_suscripcion] = $new_entry;
                }
            }
        }
        else if($form['id'] == "11"){
            $id_suscripcion = get_post_meta($order_id, "_subscription_renewal", true);
            $order_suscripcion = wc_get_order($id_suscripcion);
            foreach ($order_suscripcion->get_items() as $item_id => $item) {
                $product_id = $item->get_data()['product_id'];
                $prod_cat_args = wc_get_object_terms( $product_id, 'product_cat', 'term_id' , true);
                if($prod_cat_args['0'] == "25" || $prod_cat_args['0']  == "32"){
                    $grupo_produc_actual = wc_get_order_item_meta($item_id, "Grupo",true);
                    $tipo = $item->get_name();
                    $nombre_dominio = wc_get_order_item_meta($item_id, 'Nombre del Dominio', true);
                    $new_entry['8'] = $id_suscripcion;
                    $new_entry['10'] = $tipo;
                    $new_entry['11'] = $nombre_dominio;
                    $new_entry['15'] = $order_suscripcion->get_billing_first_name();
                    $new_entry['16'] = $order_suscripcion->get_billing_last_name();
                    $new_entry['17'] = $order_suscripcion->get_billing_company();
                    $dat_dom = obtener_grupo_datos_dominio($nombre_dominio, $order_id, $grupo_produc_actual);
                    if($dat_dom != 0){
                        $datos_servidor = obtener_datos_servidor($dat_dom);
                        $new_entry['3'] = $datos_servidor['dns_1'];
                        $new_entry['20'] = $datos_servidor['ip_dns1'];
                        $new_entry['4'] = $datos_servidor['dns_2'];
                        $new_entry['21'] = $datos_servidor['ip_dns2'];
                        $new_entry['5'] = $datos_servidor['ip_del_servidor'];
                        $new_entry['18'] = $datos_servidor['server'];
                    }
                    $suscripciones_varias[$id_suscripcion] = $id_suscripcion;
                }
                $crear_n[$id_suscripcion] = $new_entry;
            }
        }
        else{
            $suscripciones = get_children($args);
            $llaves = array_keys($suscripciones);
            $contorl_master = 0;
            foreach ($llaves as $key => $value) {
                $order_suscripcion = wc_get_order($value);
                foreach ($order_suscripcion->get_items() as $item_id => $item) {
                    $product_id = $item->get_data()['product_id'];
                    if($form['id'] == "8"){
                        $prod_cat_args = wc_get_object_terms( $product_id, 'product_cat', 'term_id' , true);
                        if($prod_cat_args['0'] == "25" || $prod_cat_args['0']  == "32" || $prod_cat_args['0']  == "445"){
                            $grupo_produc_actual = wc_get_order_item_meta($item_id, "Grupo",true);
                            $tipo = $item->get_name();
                            $id_suscripcion = $value;
                            $nombre_dominio = wc_get_order_item_meta($item_id, 'Nombre del Dominio', true);
                            $new_entry['8'] = $id_suscripcion;
                            $new_entry['10'] = $tipo;
                            $new_entry['11'] = $nombre_dominio;
                            $new_entry['15'] = $order_suscripcion->get_billing_first_name();
                            $new_entry['16'] = $order_suscripcion->get_billing_last_name();
                            $new_entry['17'] = $order_suscripcion->get_billing_company();

                            $dat_dom = obtener_grupo_datos_dominio($nombre_dominio, $order_id, $grupo_produc_actual);
                            if($dat_dom != 0){
                                $datos_servidor = obtener_datos_servidor($dat_dom);
                                $new_entry['3'] = $datos_servidor['dns_1'];
                                $new_entry['20'] = $datos_servidor['ip_dns1'];
                                $new_entry['4'] = $datos_servidor['dns_2'];
                                $new_entry['21'] = $datos_servidor['ip_dns2'];
                                $new_entry['5'] = $datos_servidor['ip_del_servidor'];
                                $new_entry['18'] = $datos_servidor['server'];
                            }
                            $suscripciones_varias[$id_suscripcion] = $id_suscripcion;
                        }
                        else if($prod_cat_args['0']  == "516" || $prod_cat_args['0']  == "517" ||
                            $prod_cat_args['0']  == "448" || $prod_cat_args['0']  == "449" ||
                            $prod_cat_args['0']  == "494" || $prod_cat_args['0']  == "447" ){
                            $tipo = $item->get_name();
                            $id_suscripcion = $value;
                            $nombre_dominio = wc_get_order_item_meta($item_id, 'Nombre del Dominio', true);
                            $new_entry['8'] = $id_suscripcion;
                            $new_entry['10'] = $tipo;
                            $new_entry['11'] = $nombre_dominio;
                            $new_entry['15'] = $order_suscripcion->get_billing_first_name();
                            $new_entry['16'] = $order_suscripcion->get_billing_last_name();
                            $new_entry['17'] = $order_suscripcion->get_billing_company();
                            $new_entry['30'] = "Si";
                            foreach($item->get_meta_data() AS $key => $value){
                                switch(explode(" (USD",$value->key)[0]){
                                    case"Hostname":
                                        $new_entry['26'] = $value->value;
                                        break;
                                    case"Dominio":
                                    case"Nombre del Dominio":
                                        $new_entry['11'] = $value->value;
                                        break;
                                    case"Prefijo NS1":
                                        $new_entry['32'] = $value->value;
                                        break;
                                    case"Prefijo NS2":
                                        $new_entry['33'] = $value->value;
                                        break;
                                    case"Sistema operativo":
                                    case"Sistema Operativo (Mensual)":
                                        $new_entry['34'] = $value->value;
                                        break;
                                    case"Panel de Hosting WHM/cPanel":
                                    case"Panel de Hosting WHM/cPanel (Mensual)":
                                        $new_entry['35'] = $value->value;
                                        break;
                                    case"Servidor Web LiteSpeed":
                                    case"Servidor Web LiteSpeed (Mensual)":
                                        $new_entry['36'] = $value->value;
                                        break;
                                    case"Filtro de SpamExperts":
                                        $new_entry['37'] = $value->value;
                                        break;
                                    case"Instalador de Aplicaciones Adicional":
                                        $new_entry['38'] = $value->value;
                                        break;
                                    case"Gestor de Temas Gráficos para cPanel":
                                        $new_entry['39'] = $value->value;
                                        break;
                                    case"Software de Soporte y Facturación":
                                        $new_entry['40'] = $value->value;
                                        break;
                                    case"Microsoft SQL Web":
                                        $new_entry['42.1'] = "Si";
                                        break;
                                    case"Certificado SSL Adicional":
                                    case"Certificado SSL Adicional (Mensual)":
                                        $new_entry['43.1'] = "Si";
                                        break;
                                    case"Monitor de URL":
                                        $new_entry['44.1'] = "Si";
                                        break;
                                    case"Dirección IP Adicional":
                                        $new_entry['45.1'] = "Si";
                                        break;
                                    case"Ancho de Banda Adicional - 1TB":
                                        $new_entry['46.1'] = "Si";
                                        break;
                                    case"Gestión de Servicios":
                                    case"Gestión de Servicios (Mensual)":
                                        $new_entry['47.1'] = "Si";
                                        break;
                                    case"Espacio en Disco (VPS) - 10 GB":
                                        $new_entry['48.1'] = "Si";
                                        break;
                                    case"Respaldo Adicional (Mensual)":
                                        $new_entry['49'] = $value->value;
                                        break;
                                    case"Memoria RAM Adicional (Mensual)":
                                        $new_entry['50'] = $value->value;
                                        break;
                                }
                            }
                        }
                        $crear_n[$id_suscripcion] = $new_entry;
                    }
                    else if($form['id'] == "9"){
                        $prod_cat_args = wc_get_object_terms( $item->get_data()['product_id'], 'product_cat', 'term_id' , true);
                        if($prod_cat_args['0'] == "26"){
                            $grupo_produc_actual = wc_get_order_item_meta($item_id, "Grupo",true);
                            $tipo = $item->get_name();
                            $id_suscripcion = $value;
                            $nombre_dominio = wc_get_order_item_meta($item_id, 'Nombre del Dominio', true);
                            $new_entry['8'] = $id_suscripcion;
                            $new_entry['10'] = $tipo;
                            $new_entry['11'] = $nombre_dominio;
                            $new_entry['15'] = $order_suscripcion->get_billing_first_name();
                            $new_entry['16'] = $order_suscripcion->get_billing_last_name();
                            $new_entry['17'] = $order_suscripcion->get_billing_company();
                            $suscripciones_varias[$id_suscripcion] = $id_suscripcion;
                            $crear_n[$id_suscripcion] = $new_entry;
                        }
                    }
                }
            }
        }

        unset($crear_n[$new_entry['8']]);
        unset($suscripciones_varias[$new_entry['8']]);
    }

    if(count($suscripciones_varias) >= 1){
        crear_entrada_susc($crear_n, $form);
    }

    return $new_entry;
}

function obtener_grupo_datos_dominio($dominio, $order_id, $grupo_produc_actual){
    $args = array(
        'post_parent' => $order_id,
        'post_type' => 'shop_subscription',
        'numberposts' => -1,
        'post_status' => 'open'
    );
    $suscripciones = get_children($args);
    $llaves = array_keys($suscripciones);
    foreach ($llaves as $key => $value) {
        $order_suscripcion = wc_get_order($value);
        foreach ($order_suscripcion->get_items() as $item_id => $item) {
            $product_id = $item->get_data()['product_id'];
            $prod_cat_args = wc_get_object_terms($product_id, 'product_cat', 'term_id', true);
            if ($prod_cat_args['0'] == "26") {
                if (wc_get_order_item_meta($item_id, "Grupo",true) == $grupo_produc_actual) {
                    $DNS1 = wc_get_order_item_meta($item_id, "DNS1",true);
                    $DNS2 = wc_get_order_item_meta($item_id, "DNS2",true);
                    $dominio_dom = wc_get_order_item_meta($item_id, "Nombre del Dominio",true);
                    if($dominio_dom == $dominio){
                        return [
                            "dns1" => $DNS1,
                            "dns2" => $DNS2
                        ];
                    }
                }
            }
        }
    }

    return 0;
}

function obtener_grupo_datos_hosting($order_id,$grupo_produc_actual){
    $args = array(
        'post_parent' => $order_id,
        'post_type' => 'shop_subscription',
        'numberposts' => -1,
        'post_status' => 'open'
    );
    $suscripciones = get_children($args);
    $llaves = array_keys($suscripciones);
    foreach ($llaves as $key => $value) {
        $order_suscripcion = wc_get_order($value);
        foreach ($order_suscripcion->get_items() as $item_id => $item) {
            $product_id = $item->get_data()['product_id'];
            $prod_cat_args = wc_get_object_terms($product_id, 'product_cat', 'term_id', true);
            if ($prod_cat_args['0'] == "25" || $prod_cat_args['0'] == "32") {
                if (wc_get_order_item_meta($item_id, "Grupo",true) == $grupo_produc_actual) {
                    $DNS1 = wc_get_order_item_meta($item_id, "DNS1",true);
                    $DNS2 = wc_get_order_item_meta($item_id, "DNS2",true);
                    $servidor = wc_get_order_item_meta($item_id, "Servidor",true);
                    $dominio = wc_get_order_item_meta($item_id, "Nombre del Dominio",true);
                    return [
                        "dns1" => $DNS1,
                        "dns2" => $DNS2,
                        "dominio" => $dominio,
                        "servidor" => $servidor
                    ];
                }
            }
        }
    }

    return 0;

}
/**
 * Setup the entry user (the created_by field of an entry).
 *
 * @param string $step_status The status of the step.
 * @param $approvers Gravity_Flow_Assignee The array of Gravity_Flow_Assignee objects.
 * @param $step Gravity_Flow_Step The current step object.
 *
 * @return array
 * 8 Crear Hosting
 * 9 Crear Dominios
 *
 * En este paso vamos a asignar las metaetiquetas a cada suscripción de acuerdo al formulario o proceso en el que estemos. Se valida primero la procedencia y luego si fue aprobada. Si no fue aprobada no realiza ningun cambio dentro de la suscripción
 * Para conocer el flujo de trabajo se debe ir a la opción Flujo de trabajo de cada formulario que interactua con woocommerce.
 */
function aprobacion_del_proceso( $step_status, $approvers, $step ) {

    foreach ( $approvers as $approver ) {
        $data_step = $approver->get_step();
        $entry_data = $data_step->get_entry();
        break;
    }

    if($entry_data['form_id'] == 8){
        $step_status = $_POST['gravityflow_approval_new_status_step_9'];
        if($entry_data[18] != "NUEVO"){
            if(strlen($entry_data[3]) == 0 || strlen($entry_data[20]) == 0 || strlen($entry_data[4]) == 0 || strlen($entry_data[21]) == 0 || strlen($entry_data[5]) == 0){
                $datos_servidor = consultar_servidor($entry_data[18], $entry_data['id']);
                $entry_data[3] = $datos_servidor['dns_1'];
                $entry_data[20] = $datos_servidor['ip_dns1'];
                $entry_data[4] = $datos_servidor['dns_2'];
                $entry_data[21] = $datos_servidor['ip_dns2'];
                $entry_data[5] = $datos_servidor['ip_del_servidor'];
            }
        }
        if($step_status == "approved"){
            $suscripcion_id = $entry_data['8'];
            $order_suscripcion = wc_get_order($suscripcion_id);
            $control = [
                "Servidor",
                "DNS1",
                "DNS2",
                "IP",
                "Raíz",
                "IP DNS1",
                "IP DNS2",
                "Email",
                "Usuario",
                "Clave"
            ];
            foreach ($order_suscripcion->get_items() as $item_id => $item) {
                foreach ($item->get_meta_data() AS $key => $value){
                    if($value->get_data()['key'] == "Servidor"){
                        if($entry_data[18] == "NUEVO"){
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[24], "");
                        }
                        else{
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[18], "" );
                        }
                        unset($control['0']);
                    } else if($value->get_data()['key'] == "DNS1"){
                        if($entry_data[18] == "NUEVO"){
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[26], "" );
                        }
                        else{
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[3], "" );
                        }
                        unset($control['1']);
                    }
                    else if($value->get_data()['key'] == "IP DNS1"){
                        if($entry_data[18] == "NUEVO"){
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[27], "" );
                        }
                        else {
                            wc_update_order_item_meta($item_id, $value->get_data()['key'], $entry_data[20], "");
                        }
                        unset($control['5']);
                    }
                    else if($value->get_data()['key'] == "DNS2"){
                        if($entry_data[18] == "NUEVO"){
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[28], "" );
                        }
                        else {
                            wc_update_order_item_meta($item_id, $value->get_data()['key'], $entry_data[4], "");
                        }
                        unset($control['2']);
                    }
                    else if($value->get_data()['key'] == "IP DNS2"){
                        if($entry_data[18] == "NUEVO"){
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[29], "" );
                        }
                        else {
                            wc_update_order_item_meta($item_id, $value->get_data()['key'], $entry_data[21], "");
                        }
                        unset($control['6']);
                    }
                    else if($value->get_data()['key'] == "IP"){
                        if($entry_data[18] == "NUEVO"){
                            wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[25], "" );
                        }
                        else {
                            wc_update_order_item_meta($item_id, $value->get_data()['key'], $entry_data[5], "");
                        }
                        unset($control['3']);
                    }
                    else if($value->get_data()['key'] == "Raíz"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[6], "" );
                        unset($control['4']);
                    }
                    else if($value->get_data()['key'] == "Email"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[22], "" );
                        unset($control['7']);
                    }
                    else if($value->get_data()['key'] == "Usuario"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[23], "" );
                        unset($control['8']);
                    }
                    else if($value->get_data()['key'] == "Clave"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[7], "" );
                        unset($control['9']);
                    }
                }
                $item_id_crear = $item_id;
                break;
            }

            foreach($control AS $key => $value){
                switch ($value){
                    case "Servidor":
                        if($entry_data[18] == "NUEVO"){
                            wc_add_order_item_meta( $item_id_crear, "Servidor", $entry_data[24], false);
                        }
                        else {
                            wc_add_order_item_meta($item_id_crear, "Servidor", $entry_data[18], false);
                        }
                        break;
                    case "DNS1":
                        if($entry_data[18] == "NUEVO"){
                            wc_add_order_item_meta( $item_id_crear, "DNS1", $entry_data[26], false);
                        }
                        else {
                            wc_add_order_item_meta($item_id_crear, "DNS1", $entry_data[3], false);
                        }
                        break;
                    case "IP DNS1":
                        if($entry_data[18] == "NUEVO"){
                            wc_add_order_item_meta( $item_id_crear, "IP DNS1", $entry_data[27], false);
                        }
                        else {
                            wc_add_order_item_meta($item_id_crear, "IP DNS1", $entry_data[20], false);
                        }
                        break;
                    case "DNS2":
                        if($entry_data[18] == "NUEVO"){
                            wc_add_order_item_meta( $item_id_crear, "DNS2", $entry_data[28], false );
                        }
                        else {
                            wc_add_order_item_meta($item_id_crear, "DNS2", $entry_data[4], false);
                        }
                        break;
                    case "IP DNS2":
                        if($entry_data[18] == "NUEVO"){
                            wc_add_order_item_meta($item_id_crear, "IP DNS2", $entry_data[29], false );
                        }
                        else {
                            wc_add_order_item_meta($item_id_crear, "IP DNS2", $entry_data[21], false);
                        }
                        break;
                    case "IP":
                        if($entry_data[18] == "NUEVO"){
                            wc_add_order_item_meta( $item_id_crear, "IP", $entry_data[25], false);
                        }
                        else {
                            wc_add_order_item_meta($item_id_crear, "IP", $entry_data[5], false);
                        }
                        break;
                    case "Raíz":
                        wc_add_order_item_meta( $item_id_crear, "Raíz", $entry_data[6], false);
                        break;
                    case "Email":
                        wc_add_order_item_meta( $item_id_crear, "Email", $entry_data[22], false);
                        break;
                    case "Usuario":
                        wc_add_order_item_meta( $item_id_crear, "Usuario", $entry_data[23], false);
                        break;
                    case "Clave":
                        wc_add_order_item_meta( $item_id_crear, "Clave", $entry_data[7], false);
                        break;
                }
            }

            return $step_status;
        }
    }
    else if($entry_data['form_id'] == 9){
        $step_status = $_POST['gravityflow_approval_new_status_step_15'];
        if($step_status == "approved"){
            $suscripcion_id = $entry_data['8'];
            $pedido_id = $entry_data['1'];

            $order_suscripcion = wc_get_order($suscripcion_id);

            $control = [
                "DNS1",
                "DNS2",
                "DNS3",
                "DNS4",
                "DNS5"
            ];

            foreach ($order_suscripcion->get_items() as $item_id => $item) {

                foreach ($item->get_meta_data() AS $key => $value){

                    if($value->get_data()['key'] == "DNS1"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[3], "" );
                        unset($control['0']);
                    }
                    else if($value->get_data()['key'] == "DNS2"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[4], "" );
                        unset($control['1']);
                    }
                    else if($value->get_data()['key'] == "DNS3"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[2], "" );
                        unset($control['2']);
                    }
                    else if($value->get_data()['key'] == "DNS4"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[5], "" );
                        unset($control['3']);
                    }
                    else if($value->get_data()['key'] == "DNS5"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[6], "" );
                        unset($control['4']);
                    }
                }

                $item_id_crear = $item_id;
                break;
            }

            foreach($control AS $key => $value){
                switch ($value) {
                    case "DNS1":
                        if ($entry_data[3] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS1", $entry_data[3], false);
                        }
                        break;
                    case "DNS2":
                        if ($entry_data[4] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS2", $entry_data[4], false);
                        }
                        break;
                    case "DNS3":
                        if ($entry_data[2] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS3", $entry_data[2], false);
                        }
                        break;
                    case "DNS4":
                        if ($entry_data[5] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS4", $entry_data[5], false);
                        }
                        break;
                    case "DNS5":
                        if ($entry_data[6] != ""){
                            wc_add_order_item_meta($item_id_crear, "DNS5", $entry_data[6], false);
                        }
                        break;
                }
            }
            return $step_status;
        }
    }
    else if($entry_data['form_id'] == 10){
        $step_status = $_POST['gravityflow_approval_new_status_step_20'];
        if($step_status == "approved"){
            $suscripcion_id = $entry_data['8'];
            $pedido_id = $entry_data['1'];

            $order_suscripcion = wc_get_order($suscripcion_id);

            $control = [
                "DNS1",
                "DNS2",
                "DNS3",
                "DNS4",
                "DNS5"
            ];

            foreach ($order_suscripcion->get_items() as $item_id => $item) {

                foreach ($item->get_meta_data() AS $key => $value){

                    if($value->get_data()['key'] == "DNS1"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[3], "" );
                        unset($control['0']);
                    }
                    else if($value->get_data()['key'] == "DNS2"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[4], "" );
                        unset($control['1']);
                    }
                    else if($value->get_data()['key'] == "DNS3"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[2], "" );
                        unset($control['2']);
                    }
                    else if($value->get_data()['key'] == "DNS4"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[5], "" );
                        unset($control['3']);
                    }
                    else if($value->get_data()['key'] == "DNS5"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[6], "" );
                        unset($control['4']);
                    }
                }

                $item_id_crear = $item_id;
                break;
            }

            foreach($control AS $key => $value){
                switch ($value) {
                    case "DNS1":
                        if ($entry_data[3] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS1", $entry_data[3], false);
                        }
                        break;
                    case "DNS2":
                        if ($entry_data[4] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS2", $entry_data[4], false);
                        }
                        break;
                    case "DNS3":
                        if ($entry_data[2] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS3", $entry_data[2], false);
                        }
                        break;
                    case "DNS4":
                        if ($entry_data[5] != "") {
                            wc_add_order_item_meta($item_id_crear, "DNS4", $entry_data[5], false);
                        }
                        break;
                    case "DNS5":
                        if ($entry_data[6] != ""){
                            wc_add_order_item_meta($item_id_crear, "DNS5", $entry_data[6], false);
                        }
                        break;
                }
            }
            return $step_status;
        }
    }
    else if($entry_data['form_id'] == 11){
        $step_status = $_POST['gravityflow_approval_new_status_step_25'];
        if($step_status == "approved"){
            $suscripcion_id = $entry_data['8'];
            $order_suscripcion = wc_get_order($suscripcion_id);

            $control = [
                "Servidor",
                "DNS1",
                "DNS2",
                "IP",
                "Raíz",
                "IP DNS1",
                "IP DNS2",
                "Email",
                "Usuario",
                "Clave"
            ];

            foreach ($order_suscripcion->get_items() as $item_id => $item) {

                foreach ($item->get_meta_data() AS $key => $value){

                    if($value->get_data()['key'] == "Servidor"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[18], "" );
                        unset($control['0']);
                    } else if($value->get_data()['key'] == "DNS1"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[3], "" );
                        unset($control['1']);
                    }
                    else if($value->get_data()['key'] == "IP DNS1"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[20], "" );
                        unset($control['5']);
                    }
                    else if($value->get_data()['key'] == "DNS2"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[4], "" );
                        unset($control['2']);
                    }
                    else if($value->get_data()['key'] == "IP DNS2"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[21], "" );
                        unset($control['6']);
                    }
                    else if($value->get_data()['key'] == "IP"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[5], "" );
                        unset($control['3']);
                    }
                    else if($value->get_data()['key'] == "Raíz"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[6], "" );
                        unset($control['4']);
                    }
                    else if($value->get_data()['key'] == "Email"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[22], "" );
                        unset($control['7']);
                    }
                    else if($value->get_data()['key'] == "Usuario"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[23], "" );
                        unset($control['8']);
                    }
                    else if($value->get_data()['key'] == "Clave"){
                        wc_update_order_item_meta( $item_id, $value->get_data()['key'], $entry_data[7], "" );
                        unset($control['9']);
                    }

                }

                $item_id_crear = $item_id;
                break;
            }

            foreach($control AS $key => $value){
                switch ($value){
                    case "Servidor":
                        wc_add_order_item_meta( $item_id_crear, "Servidor", $entry_data[18], false);
                        break;
                    case "DNS1":
                        wc_add_order_item_meta( $item_id_crear, "DNS1", $entry_data[3], false);
                        break;
                    case "IP DNS1":
                        wc_add_order_item_meta( $item_id_crear, "IP DNS1", $entry_data[20], false);
                        break;
                    case "DNS2":
                        wc_add_order_item_meta( $item_id_crear, "DNS2", $entry_data[4], false);
                        break;
                    case "IP DNS2":
                        wc_add_order_item_meta( $item_id_crear, "IP DNS2", $entry_data[21], false);
                        break;
                    case "IP":
                        wc_add_order_item_meta( $item_id_crear, "IP", $entry_data[5], false);
                        break;
                    case "Raíz":
                        wc_add_order_item_meta( $item_id_crear, "Raíz", $entry_data[6], false);
                        break;
                    case "Email":
                        wc_add_order_item_meta( $item_id_crear, "Email", $entry_data[22], false);
                        break;
                    case "Usuario":
                        wc_add_order_item_meta( $item_id_crear, "Usuario", $entry_data[23], false);
                        break;
                    case "Clave":
                        wc_add_order_item_meta( $item_id_crear, "Clave", $entry_data[7], false);
                        break;
                }
            }

            return $step_status;
        }
    }
    return $_POST['gravityflow_approval_new_status_step_'.$step->get_id()];
}

function crear_entrada_susc($new_entry, $form){
    global $wpdb;
    $order_id = $new_entry['1'];
    $contar = 0;

    foreach ($new_entry AS $key => $value){
        $contar++;
        $value['form_id'] = $form['id'];
        $nueva_entrada = $value;
        $query = 'SELECT
            th_gf_entry_meta.meta_value
        FROM
            th_gf_entry
        INNER JOIN th_gf_entry_meta ON th_gf_entry_meta.entry_id = th_gf_entry.id
        INNER JOIN th_gf_entry_meta AS th_gf_entry_meta2 ON th_gf_entry_meta2.entry_id = th_gf_entry.id
        WHERE
            (
                (
                    th_gf_entry_meta.meta_value = "'.$key.'"
                )
                AND (
                    th_gf_entry_meta2.meta_value = "'.$nueva_entrada[1].'"
                )
                AND (
                    th_gf_entry.`status` = "active"
                    AND th_gf_entry_meta.form_id = "'.$form['id'].'"
                )
            )';

        $resultados = $wpdb->get_results($query);
        if(count($resultados) == 0) {
            if(strlen($nueva_entrada[8]) != 0 || count($nueva_entrada[8]) != 0){
                $entry_id = GFAPI::add_entry( $nueva_entrada );
                if ( is_wp_error( $entry_id ) ) {

                } else {
                    // save entry ID to WC order.
                    add_post_meta( $order_id, '_gravityflow-entry-id', $entry_id );
                }
            }
            else if(strlen($nueva_entrada[10]) != 0 || count($nueva_entrada[10]) != 0){
                $entry_id = GFAPI::add_entry( $nueva_entrada );
                if ( is_wp_error( $entry_id ) ) {

                } else {
                    // save entry ID to WC order.
                    add_post_meta( $order_id, '_gravityflow-entry-id', $entry_id );
                }
            }
        }
    }
}

function obtener_datos_servidor($dat_dom){
    global $wpdb;
    $id_servidor = $wpdb->get_results('SELECT th_postmeta.post_id FROM `th_postmeta` WHERE th_postmeta.meta_value = "'.$dat_dom['dns1'].'"', ARRAY_A);
    $id_servidor = $id_servidor[0]['post_id'];

    $data = [
        "ip_del_servidor" => get_post_meta($id_servidor , "ip_del_servidor", true),
        "dns_1" => get_post_meta($id_servidor , "dns_1", true),
        "ip_dns1" => get_post_meta($id_servidor , "ip_dns1", true),
        "dns_2" => get_post_meta($id_servidor , "dns_2", true),
        "ip_dns2" => get_post_meta($id_servidor , "ip_dns2", true),
        "server" => get_post($id_servidor)->post_title
    ];

    return $data;
}

function post_to_third_party( $entry, $form ) {
    if($entry['form_id'] == 1 || $entry['form_id'] == 4){
        if (filter_var($entry['5'], FILTER_VALIDATE_EMAIL)) {
            $correo = $entry['5'];
        } else {
            $correo = $entry['3'];
        }
        tracking($correo);
        $usa_estados = array(
            "AL" => array("Alabama"),
            "AK" => array("Alaska"),
            "AZ" => array("Arizona"),
            "AR" => array("Arkansas"),
            "CA" => array("California"),
            "CO" => array("Colorado"),
            "CT" => array("Connecticut"),
            "DE" => array("Delaware"),
            "DC" => array("District Of Columbia"),
            "FL" => array("Florida"),
            "GA" => array("Georgia"),
            "HI" => array("Hawaii"),
            "ID" => array("Idaho"),
            "IL" => array("Illinois"),
            "IN" => array("Indiana"),
            "IA" => array("Iowa"),
            "KS" => array("Kansas"),
            "KY" => array("Kentucky"),
            "LA" => array("Louisiana"),
            "ME" => array("Maine"),
            "MD" => array("Maryland"),
            "MA" => array("Massachusetts"),
            "MI" => array("Michigan"),
            "MN" => array("Minnesota"),
            "MS" => array("Mississippi"),
            "MO" => array("Missouri"),
            "MT" => array("Montana"),
            "NE" => array("Nebraska"),
            "NV" => array("Nevada"),
            "NH" => array("New Hampshire"),
            "NJ" => array("New Jersey"),
            "NM" => array("New Mexico"),
            "NY" => array("New York"),
            "NC" => array("North Carolina"),
            "ND" => array("North Dakota"),
            "OH" => array("Ohio"),
            "OK" => array("Oklahoma"),
            "OR" => array("Oregon"),
            "PA" => array("Pennsylvania"),
            "RI" => array("Rhode Island"),
            "SC" => array("South Carolina"),
            "SD" => array("South Dakota"),
            "TN" => array("Tennessee"),
            "TX" => array("Texas"),
            "UT" => array("Utah"),
            "VT" => array("Vermont"),
            "VA" => array("Virginia"),
            "WA" => array("Washington"),
            "WV" => array("West Virginia"),
            "WI" => array("Wisconsin"),
            "WY" => array("Wyoming"),
            "AA" => array("Fuerzas Armadas (AA)"),
            "AE" => array("Fuerzas Armadas  US"),
            "AP" => array("Fuerzas Armadas  US")
        );
        $paises = array(
            "AF" => array("Afganistán"),
            "AL" => array("Albania"),
            "DE" => array("Alemania"),
            "DZ" => array("Algeria"),
            "AD" => array("Andorra"),
            "AO" => array("Angola"),
            "AI" => array("Anguilla"),
            "AG" => array("Antigua y Barbuda"),
            "AQ" => array("Antártida"),
            "SA" => array("Arabia Saudita"),
            "AR" => array("Argentina"),
            "AM" => array("Armenia"),
            "AW" => array("Aruba"),
            "AU" => array("Australia"),
            "AT" => array("Austria"),
            "AZ" => array("Azerbaijan"),
            "BS" => array("Bahamas"),
            "BH" => array("Bahrain"),
            "BD" => array("Bangladesh"),
            "BB" => array("Barbados"),
            "PW" => array("Belau"),
            "BZ" => array("Belize"),
            "BJ" => array("Benin"),
            "BM" => array("Bermuda"),
            "BT" => array("Bhutan"),
            "BY" => array("Bielorrusia"),
            "MM" => array("Birmania"),
            "BO" => array("Bolivia"),
            "BQ" => array("Bonaire, San Eustaquio y Saba"),
            "BA" => array("Bosnia y Herzegovina"),
            "BW" => array("Botswana"),
            "BR" => array("Brasil"),
            "BN" => array("Brunéi"),
            "BG" => array("Bulgaria"),
            "BF" => array("Burkina Faso"),
            "BI" => array("Burundi"),
            "BE" => array("Bélgica"),
            "CV" => array("Cabo Verde"),
            "KH" => array("Camboya"),
            "CM" => array("Camerún"),
            "CA" => array("Canadá"),
            "TD" => array("Chad"),
            "CL" => array("Chile"),
            "CN" => array("China"),
            "CY" => array("Chipre"),
            "VA" => array("Ciudad del Vaticano"),
            "CO" => array("Colombia"),
            "KM" => array("Comoras"),
            "CG" => array("Congo (Brazzaville)"),
            "CD" => array("Congo (Kinshasa)"),
            "KP" => array("Corea del Norte"),
            "KR" => array("Corea del Sur"),
            "CR" => array("Costa Rica"),
            "CI" => array("Costa de Marfil"),
            "HR" => array("Croacia"),
            "CU" => array("Cuba"),
            "CW" => array("Curaçao"),
            "DK" => array("Dinamarca"),
            "DJ" => array("Djibouti"),
            "DM" => array("Dominica"),
            "EC" => array("Ecuador"),
            "EG" => array("Egipto"),
            "SV" => array("El Salvador"),
            "AE" => array("Emiratos Árabes Unidos"),
            "ER" => array("Eritrea"),
            "SK" => array("Eslovaquia"),
            "SI" => array("Eslovenia"),
            "ES" => array("España"),
            "US" => array("Estados Unidos (EEUU)"),
            "EE" => array("Estonia"),
            "ET" => array("Etiopía"),
            "PH" => array("Filipinas"),
            "FI" => array("Finlandia"),
            "FJ" => array("Fiyi"),
            "FR" => array("Francia"),
            "GA" => array("Gabón"),
            "GM" => array("Gambia"),
            "GE" => array("Georgia"),
            "GH" => array("Ghana"),
            "GI" => array("Gibraltar"),
            "GD" => array("Granada"),
            "GR" => array("Grecia"),
            "GL" => array("Groenlandia"),
            "GP" => array("Guadalupe"),
            "GU" => array("Guam"),
            "GT" => array("Guatemala"),
            "GF" => array("Guayana Francesa"),
            "GG" => array("Guernsey"),
            "GN" => array("Guinea"),
            "GQ" => array("Guinea Ecuatorial"),
            "GW" => array("Guinea-Bisáu"),
            "GY" => array("Guyana"),
            "HT" => array("Haití"),
            "HN" => array("Honduras"),
            "HK" => array("Hong Kong"),
            "HU" => array("Hungría"),
            "IN" => array("India"),
            "ID" => array("Indonesia"),
            "IQ" => array("Irak"),
            "IE" => array("Irlanda"),
            "IR" => array("Irán"),
            "BV" => array("Isla Bouvet"),
            "NF" => array("Isla Norfolk"),
            "SH" => array("Isla Santa Elena"),
            "IM" => array("Isla de Man"),
            "CX" => array("Isla de Navidad"),
            "IS" => array("Islandia"),
            "AX" => array("Islas Åland"),
            "KY" => array("Islas Caimán"),
            "CC" => array("Islas Cocos"),
            "CK" => array("Islas Cook"),
            "FO" => array("Islas Feroe"),
            "GS" => array("Islas Georgias y Sandwich del Sur"),
            "HM" => array("Islas Heard y McDonald"),
            "FK" => array("Islas Malvinas"),
            "MP" => array("Islas Marianas del Norte"),
            "MH" => array("Islas Marshall"),
            "SB" => array("Islas Salomón"),
            "TC" => array("Islas Turcas y Caicos"),
            "VG" => array("Islas Vírgenes Británicas"),
            "VI" => array("Islas Vírgenes de Estados Unidos (EEUU)"),
            "UM" => array("Islas de ultramar menores de Estados Unidos (EEUU)"),
            "IL" => array("Israel"),
            "IT" => array("Italia"),
            "JM" => array("Jamaica"),
            "JP" => array("Japón"),
            "JE" => array("Jersey"),
            "JO" => array("Jordania"),
            "KZ" => array("Kazajistán"),
            "KE" => array("Kenia"),
            "KG" => array("Kirguistán"),
            "KI" => array("Kiribati"),
            "KW" => array("Kuwait"),
            "LA" => array("Laos"),
            "LS" => array("Lesoto"),
            "LV" => array("Letonia"),
            "LR" => array("Liberia"),
            "LY" => array("Libia"),
            "LI" => array("Liechtenstein"),
            "LT" => array("Lituania"),
            "LU" => array("Luxemburgo"),
            "LB" => array("Líbano"),
            "MO" => array("Macao"),
            "MG" => array("Madagascar"),
            "MY" => array("Malasia"),
            "MW" => array("Malaui"),
            "MV" => array("Maldivas"),
            "MT" => array("Malta"),
            "ML" => array("Malí"),
            "MA" => array("Marruecos"),
            "MQ" => array("Martinica"),
            "MU" => array("Mauricio"),
            "MR" => array("Mauritania"),
            "YT" => array("Mayotte"),
            "FM" => array("Micronesia"),
            "MD" => array("Moldavia"),
            "MN" => array("Mongolia"),
            "ME" => array("Montenegro"),
            "MS" => array("Montserrat"),
            "MZ" => array("Mozambique"),
            "MX" => array("México"),
            "MC" => array("Mónaco"),
            "NA" => array("Namibia"),
            "NR" => array("Nauru"),
            "NP" => array("Nepal"),
            "NI" => array("Nicaragua"),
            "NG" => array("Nigeria"),
            "NU" => array("Niue"),
            "NO" => array("Noruega"),
            "NC" => array("Nueva Caledonia"),
            "NZ" => array("Nueva Zelanda"),
            "NE" => array("Níger"),
            "OM" => array("Omán"),
            "PK" => array("Pakistán"),
            "PA" => array("Panamá"),
            "PG" => array("Papúa Nueva Guinea"),
            "PY" => array("Paraguay"),
            "NL" => array("Países Bajos"),
            "PE" => array("Perú"),
            "PN" => array("Pitcairn"),
            "PF" => array("Polinesia Francesa"),
            "PL" => array("Polonia"),
            "PT" => array("Portugal"),
            "PR" => array("Puerto Rico"),
            "QA" => array("Qatar"),
            "GB" => array("Reino Unido (UK)"),
            "CF" => array("República Centroafricana"),
            "CZ" => array("República Checa"),
            "DO" => array("República Dominicana"),
            "MK" => array("República de Macedonia"),
            "RE" => array("Reunión"),
            "RW" => array("Ruanda"),
            "RO" => array("Rumania"),
            "RU" => array("Rusia"),
            "EH" => array("Sahara Occidental"),
            "WS" => array("Samoa"),
            "AS" => array("Samoa Americana"),
            "BL" => array("San Bartolomé"),
            "KN" => array("San Cristóbal y Nieves"),
            "SM" => array("San Marino"),
            "MF" => array("San Martín (parte de Francia)"),
            "SX" => array("San Martín (parte de Holanda)"),
            "PM" => array("San Pedro y Miquelón"),
            "VC" => array("San Vicente y las Granadinas"),
            "LC" => array("Santa Lucía"),
            "ST" => array("Santo Tomé y Príncipe"),
            "SN" => array("Senegal"),
            "RS" => array("Serbia"),
            "SC" => array("Seychelles"),
            "SL" => array("Sierra Leona"),
            "SG" => array("Singapur"),
            "SY" => array("Siria"),
            "SO" => array("Somalia"),
            "LK" => array("Sri Lanka"),
            "SZ" => array("Suazilandia"),
            "ZA" => array("Sudáfrica"),
            "SD" => array("Sudán"),
            "SS" => array("Sudán del Sur"),
            "SE" => array("Suecia"),
            "CH" => array("Suiza"),
            "SR" => array("Surinam"),
            "SJ" => array("Svalbard y Jan Mayen"),
            "TH" => array("Tailandia"),
            "TW" => array("Taiwán"),
            "TZ" => array("Tanzania"),
            "TJ" => array("Tayikistán"),
            "IO" => array("Territorio Británico del Océano Índico"),
            "PS" => array("Territorios Palestinos"),
            "TF" => array("Territorios australes franceses"),
            "TL" => array("Timor Oriental"),
            "TG" => array("Togo"),
            "TK" => array("Tokelau"),
            "TO" => array("Tonga"),
            "TT" => array("Trinidad y Tobago"),
            "TM" => array("Turkmenistán"),
            "TR" => array("Turquía"),
            "TV" => array("Tuvalu"),
            "TN" => array("Túnez"),
            "UA" => array("Ucrania"),
            "UG" => array("Uganda"),
            "UY" => array("Uruguay"),
            "UZ" => array("Uzbekistán"),
            "VU" => array("Vanuatu"),
            "VE" => array("Venezuela"),
            "VN" => array("Vietnam"),
            "WF" => array("Wallis y Futuna"),
            "YE" => array("Yemen"),
            "ZM" => array("Zambia"),
            "ZW" => array("Zimbabue")
        );

        if ($entry['form_id'] == 1) {
            $pai_final = $paises[$entry['19']][0];

            $datos_CRM = array(
                'cf_1333' => $_SERVER['HTTP_REFERER'], //referidor
                'leadstatus' => 'No Contactado',
                'rating' => 'Iniciando',
                'cf_872' => "Formulario de Registro",
                'leadsource' => "Portal TecnoHost",
                'assigned_user_id' => '20x2',//ModuloIdxUserId
                "page_name" => "Tecno Tecnohost",
                'firstname' => $entry['1.3'], //nombre good
                'lastname' => $entry['1.6'], //apellido good
                'cf_1345' => $entry['9'], //cedula
                'phone' => $entry['4'], //telefono
                'mobile' => $entry['4'], //telefono
                'email' => $entry['3'], //correo
                'lane' => $entry['16'], //direccion
                'city' => $entry['23'], //ciudad
                'state' => $entry['24'], //estado
                'country' => $pai_final, //pais
                'cf_1331' => "REGISTRO TIENDA TECNOHOST", //Área de interés
                'code' => $entry['22'], //zip
                'company' => $entry['8']//empresa
            );

            $datos_mautic = array(
                'nombre' => $entry['1.3'],
                'apellido' => $entry['1.6'],
                'empresa' => $entry['8'],
                'correo_electronico' => $entry['3'],
                'telefono_de_contacto' => $entry['4'],
                'pais' => $pai_final,
                'departamento' => $entry['23'],
                'ciudad' => $entry['24'],
                'direccion' => $entry['16'],
                'referidor' => $_SERVER['HTTP_REFERER'],
                'nit' => $entry['22']
            );

            $id = 49;
        } else if($entry['form_id'] == 4){
            $correo = $entry['3'];
            actualizar_correos_pedidos($correo, $entry['created_by']);
            $pai_final = $paises[$entry['19']][0];
            $datos_CRM = array(
                'assigned_user_id' => '20x2',//ModuloIdxUserId
                "page_name" => $entry['29'],
                "firstname" => $entry['28'],//NOMBRE
                "lastname" => $entry['27'],//APELLIDO
                "email" => $entry['3'],//EMAIL
                'phone' => $entry['4'],
                'mobile' => $entry['4'],
                'country' => $pai_final,
                'company' => $entry['8'],
                'state' => $entry['24'],
                'leadsource' => $entry['30'],
                'leadstatus' => $entry['31'],
                'rating' => $entry['32'],
                'cf_872' => $entry['33'],
                'code' => $entry['22'], //zip
                'lane' => $entry['16'], //direccion
                'city' => $entry['23'],
                /*'cf_1333' => $entry['34'], corresponde al referidor*/
                'cf_1331' => "REGISTRO TIENDA TECNOHOST", //Área de interés
            );
            $datos_mautic = array(
                'nombre' => $entry['28'],
                'apellido' => $entry['27'],
                'empresa' => $entry['8'],
                'correo_electronico' => $entry['3'],
                'telefono_de_contacto' => $entry['4'],
                'pais' => $pai_final,
                'departamento' => $entry['24'],
                'ciudad' => $entry['23'],
                'direccion' => $entry['16'],
                'referidor' => $_SERVER['HTTP_REFERER'],
                'nit' => $entry['9']
            );
            $id = $entry['29'];
        }

        tracking($correo);
        require("registro_blog_new.php");
        require("integrar_form_mautic_new.php");
        $respuesta = pushMauticForm($datos_mautic, $id);
        tracking($correo);
        if ($entry['28'] == 1) {
            echo '
            <script>
                localStorage.removeItem("refe-ridor");
            </script>
        ';
        } else {
            echo "<div id='grcm' style='visibility: hidden'>" . $respuesta . "</div>
                <script>
                    let rgracias = jQuery(\"#grcm a\").attr(\"href\");
                    jQuery(location).attr('href', rgracias);
                    localStorage.removeItem(\"refe-ridor\");
                </script>
             ";
        }
    }
}

function actualizar_correos_pedidos($correo, $id_usuario){
    global $wpdb;
    $query = 'SELECT th_postmeta.post_id FROM th_postmeta WHERE th_postmeta.meta_key = "_customer_user" AND
    th_postmeta.meta_value = "'.$id_usuario.'";';
    $resultados = $wpdb->get_results($query, ARRAY_A);
    foreach($resultados as $key => $value){
        update_post_meta($value['post_id'],"_billing_email",$correo,"");
    }
}

function actualizar_correo($user_id){
    actualizar_correos_pedidos($_POST['account_email'], $user_id);
}

function tracking ($email){
    setcookie('correo', $email, time()+(60*60*24*30),"/","tecnohost.net");
}

function actualizar_suscripcion_prices($workflow){

}

function actualizar_renewal_order_prices($workflow){

}

function limpiar_cola($workflow){
    global $wpdb;
    $order = $workflow->data_layer()->get_order();
    $order_id = $order->get_id();

    $order = wc_get_order($order_id);

    $suscripcion_id = $order->get_meta('_subscription_renewal');

    $query = 'SELECT th_automatewoo_queue.id FROM th_automatewoo_queue_meta INNER JOIN th_automatewoo_queue ON th_automatewoo_queue_meta.event_id = th_automatewoo_queue.id
    WHERE th_automatewoo_queue_meta.meta_value = "'.$suscripcion_id.'"';

    $resultado = $wpdb->get_results($query,ARRAY_A);

    foreach($resultado as $key => $value){
        $wpdb->delete('th_automatewoo_queue_meta',array('event_id'=> $value['id']));
        $wpdb->delete('th_automatewoo_queue',array('id'=> $value['id']));
    }
}

function limpiar_carrito(){
    global $woocommerce;
    $woocommerce->cart->empty_cart();
    wp_die();
}

add_filter( 'woocommerce_product_export_delimiter', function ( $delimiter ) {

	// set your custom delimiter
	$delimiter = ';';

	return $delimiter;
} );

function list_tab(){
    global $post;
    if( is_page() )
    {
        switch($post->ID) // post_name is the post slug which is more consistent for matching to here
        {
            case '8':
                wp_register_script('tabl_eml', get_site_url(). '/wp-content/themes/tecnohost/js/email_tabla.js', array('jquery'), '2', true );
                wp_enqueue_script('tabl_eml');

                wp_enqueue_style('tabla-cdn-styles', "https://cdn.datatables.net/1.10.2/css/jquery.dataTables.css");

                wp_enqueue_style('tabla-styles', get_stylesheet_directory_uri().'/css/email_tabla.css');

                wp_register_script('tbl_cdns', 'https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js', array('jquery'), '2', true );
                wp_enqueue_script('tbl_cdns');

                wp_localize_script('tabl_eml','emls_tab',['ajaxurl'=>admin_url('admin-ajax.php')]);
                break;
        }
    }
}

function emails_suscripciones(){
    add_shortcode( 'emails_cliente_suscripciones', 'mostrar_emails_suscriptions' );
}

function mostrar_emails_suscriptions(){
    $id_usuer = get_current_user_id();
    $customer_orders = wcs_get_users_subscriptions();
    setlocale(LC_TIME, "spanish");

    if( ! empty( $customer_orders ) ) {

        $output = '
            <table id="tabla" class="tbl_eml">
                <thead id="head_tabl_email">
                    <tr>
                        <th>Servicio</th>
                        <th>Fecha de Creación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
            <tbody>';

        foreach ( $customer_orders as $key => $value ){
            $id = $key;
            $date_created           = $value->get_date_created();
            $timezone   = wp_timezone();
            $date_created_to_db     = wp_date( 'd F, Y g:i a', $date_created->getTimestamp(),$timezone );
            $output .= '
             <tr>
                <td>'.$id.'</td>
                <td>'.$date_created_to_db.'</td>
                <td>
                    <div class="mostr_tab acti_btn" data-tabla="'.$id.'" data-tipo="suscription">
                        <i class="fas fa-eye"></i>
                    </div>
                </td>
            </tr>
            ';
        }

        $output .= ' </tbody>
                </table>
                <div class="girar_emails">
                    <i class="fas fa-redo"></i>
                </div>
                <div class="tabla_emails"></div>
                
                ';

    }

    return $output ?? '<strong>Lo sentimos. No hay entradas que coincidan con sus criterios.!</strong>';
}

function emails_pedidos(){
    add_shortcode( 'emails_cliente', 'mostrar_emails' );
}

function mostrar_emails(){
    $id_usuer = get_current_user_id();

    $customer_orders = get_posts(
        apply_filters(
            'woocommerce_my_account_my_orders_query',
            array(
                'numberposts' => 20,
                'meta_key'    => '_customer_user',
                'meta_value'  => $id_usuer,
                //'post_type'   => "shop_subscription",
                'post_type'   => "shop_order",
                //'post_type'   => wc_get_order_types( 'view-orders' ),
                'post_status' => array_keys( wc_get_order_statuses() ),
            )
        )
    );

    if( ! empty( $customer_orders ) ) {

        $output = '
            <table id="tabla" class="tbl_eml">
                <thead id="head_tabl_email">
                    <tr>
                        <th>Pedido</th>
                        <th>Fecha de Creación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
            <tbody>';

        foreach ( $customer_orders as $key ){

            $id = $key->ID;
            $date_created_to_db = $key->post_date;
            $output .= '
             <tr>
                <td>'.$id.'</td>
                <td>'.$date_created_to_db.'</td>
                <td>
                    <div class="mostr_tab acti_btn" data-tabla="'.$id.'" data-tipo="pedidos">
                        <i class="fas fa-eye"></i>
                    </div>
                </td>
            </tr>
            ';
        }

        $output .= ' </tbody>
                </table>
                <div class="girar_emails">
                    <i class="fas fa-redo"></i>
                </div>
                <div class="tabla_emails"></div>
                
                ';

    }

    return $output ?? '<strong>Lo sentimos. No hay entradas que coincidan con sus criterios.</strong>';
}

function consult_emails(){
    $id_usuer = get_current_user_id();
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];

    if($tipo == "suscription"){
        $key = "id_suscripcin";
    }
    else{
        $key = "id_pedido";
    }

    $args = array(
        'post_type'	=> 'email',
        'meta_query' => array(
            'relation'		=> 'AND',
            array(
                'key'		=> 'id_usuario',
                'value'		=> $id_usuer,
                'compare'	=> '=',
            ),
            array(
                'key'		=> $key,
                'value'		=> $id,
                'compare'	=> '=',
            )
        )
    );

    $custom_posts = get_posts( $args );

    $output = '
        <div class="tabl_emails" data-tabl="'.$id.'">
            <h2>Emails del pedido #'.$id.'</h2>        
            <table id="tabla_emails" class="tbl_eml">
                <thead id="head_tabl_email">
                    <tr>
                        <th>Asunto</th>
                        <th>Fecha de envio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
    ';

    if( ! empty( $custom_posts ) ){
        foreach ( $custom_posts as $p ){
            $asunto = $p->post_title;
            $id_pedido = get_post_meta( $p->ID ,'id_pedido', true);
            $id_suscripcion = get_post_meta( $p->ID ,'id_suscripcin', true);
            $correo = get_post_meta( $p->ID ,'correo', true);
            //$correo = "";
            $date_created_to_db = get_post_meta( $p->ID ,'fecha_de_envio', true);

            $output .= '
             <tr>
                <td class="dat_t">'.$asunto.'</td>
                <td class="dat_f">'.$date_created_to_db.'</td>
                <td class="acct_viewema"><a class="abrir_ema" data-action="'.$p->ID.'"><i class="fas fa-eye"></i></a></td>
            </tr>
            <div class="email_content_my_accoun" data-email="'.$p->ID.'" style="display: none">
                       
            </div>
            ';
        }

    }

    $output .= ' </tbody>
                </table>';

    echo json_encode($output);

    wp_die();

}

function tabla_productos($order_id){
    setlocale(LC_ALL, 'es_ES');
    $order_data = wc_get_order($order_id);
    $subtotal =$order_data->get_subtotal();
    $metodo_pago = $order_data->get_payment_method_title();
    $moneda = get_post_meta($order_id, '_order_currency', true);
    $iva_total = 0;

    $producto_linea = "";
    $iva_descuento_total = 0;
    $descuento_arrehglo = "";
    if($order_data->get_items('fee')){
        $descuento = $order_data->get_items('fee');
        foreach($descuento AS $key => $value){
            $descuento_data = $value->get_data();
            $iva_descuento = number_format($descuento_data['taxes']['total'][1],'2');
            $iva_descuento = (-1)*$iva_descuento;
            $iva_descuento_total = $iva_descuento_total + $iva_descuento;
            $descuento_arrehglo = '
                <tr>
                    <th>
                        '.$descuento_data['name'].':
                    </th>
                     <td colspan="3">
                        '.$moneda.' $'.number_format($descuento_data['total'],2).'
                    </td>
                </tr>
                ';
        }
    }
    foreach($order_data->get_items() AS $item_id => $item ){

        $p_meta = '<ul class="wc-item-meta">';
        foreach($item->get_meta_data() AS $key => $value){
            if($value->get_data()['key'] == "Selector Dominios" || $value->get_data()['key'] == "Dominio"
                || $value->get_data()['key'] == "Grupo" || $value->get_data()['key'] == "Nombre del Dominio"){
                $p_meta .= '<li>

                    '.$value->get_data()['key'].'
                    <p>'.$value->get_data()['value'].'</p>
                </li>';
            }
        }
        $p_meta .= '</ul>';
        if ($item['variation_id'] != 0) {
            $total_pagar = $item->get_data()['subtotal'];
        } else {
            $total_pagar = $item->get_data()['subtotal'];
        }
        $codigo = wc_get_product(wc_get_order_item_meta($item_id, '_product_id', true))->get_sku();
        $nombre_dominio = wc_get_order_item_meta($item_id, 'Nombre del Dominio', true);
        if (empty($nombre_dominio)) {
            $nombre_dominio = wc_get_order_item_meta($item_id, 'Dominio', true);
        }
        if (empty($nombre_dominio)) {
            $nombre_dominio = "No aplica";
        }
        $nombre_servicio = wc_get_product(wc_get_order_item_meta($item_id, '_product_id', true))->get_name();
        $iva = $item->get_data()['total_tax'];
        $iva_total = $iva_total + $iva;
        $producto_linea .= '
            <tr>
                <td><strong>'.$nombre_servicio.'</strong> '.$p_meta.'</td>
                <td>'.$moneda . ' $' . number_format($total_pagar, 2).'</td>
                <td>'.$moneda . ' $' . number_format( $iva, 2).'</td>
                <td>'.$moneda . ' $' . number_format($total_pagar + $iva, 2).'</td>
            </tr>
        ';
    }
    $iva_htm = "";
    if($iva_total > 0){
        $iva_htm =
            '<tr>
                <th>
                    IVA:
                </th>
                 <td colspan="3">
                    ' . $moneda . ' $' . number_format($iva_total - $iva_descuento_total, 2) . '
                </td>
            </tr>';
    }
    $fecha = wp_kses_post( '' . sprintf( __( '[Order #%s]', 'woocommerce' ) . '' . ' (<time datetime="%s">%s</time>)', $order_data->get_order_number(), $order_data->get_date_created()->format( 'c' ), wc_format_datetime( $order_data->get_date_created() ) ) );
    $tabla_productos = '
    <div>
        <h2 class="fech_pedid">'.$fecha.'</h2>
        <table class="simul_email">
            <thead>
                <tr>
                    <th class="woocommerce-table__product-name product-name">Producto</th>
                    <th class="woocommerce-table__product-table product-Price">Cantidad</th>
                    <th class="woocommerce-table__product-table product-iva">IVA</th>
                    <th class="woocommerce-table__product-table product-total">Total</th>
                </tr>
            </thead>
            <tbody>
                '.$producto_linea.'
            </tbody>
            <tfoot>
                <tr>
                    <th>Subtotal:</th>
                    <td colspan="3">'.$moneda . ' $' . number_format($subtotal, 2).'</td>
                </tr>
                '.$descuento_arrehglo.'
                '.$iva_htm.'
                <tr>
                    <th>Método de pago:</th>
                    <td colspan="3">'.$metodo_pago.'</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td colspan="3">'.$moneda . ' $' . number_format($iva_total + $iva_descuento_total + $subtotal, 2).'</td>
                </tr>
            </tfoot>
        </table>
        
        <h2 class="tit_susc_simule_email">Información de la suscripción</h2>
    
        <table class="tabl_sus_email_simu" cellspacing=0 cellpadding=6 border=1>
            <tbody>
                <tr>
                    <th scope=col>
                        Suscripción
                    </th>    
                    <th scope=col>
                        Fecha Inicial
                    </th>  
                    <th scope=col>
                        Fecha Final
                    </th>  
                    <th scope=col>
                        Precio
                    </th>    
                </tr>
                ';

    $contad = 1;
    $args = array(
        'post_parent' => $order_id,
        'post_type' => 'shop_subscription',
        'numberposts' => -1,
        'post_status' => 'open'
    );
    $suscripciones_pse = get_children($args);
    foreach ($suscripciones_pse as $key => $value) {
        $id_orden_suscripcion = $key;
        foreach (wc_get_order($id_orden_suscripcion)->get_items() AS $kkey => $vvalue) {
            $name = $vvalue->get_data()['name'];
        }
        $order_sub = wc_get_order($id_orden_suscripcion);
        if (get_post_meta($id_orden_suscripcion, '_schedule_start', true)) {
            $dat_inicio = wc_format_datetime($order_sub->get_data()['schedule_start']);
        } else {
            $dat_inicio = date('d F, Y');
        }

        if (get_post_meta($id_orden_suscripcion, '_schedule_next_payment', true)) {
            $dat_fin = wc_format_datetime($order_sub->get_data()['schedule_end']);
        } else {
            $dat_fin = "Cuando se cancele";
        }
        $tabla_productos .=
            '
                            <tr>
                                <td>
                                    <a href="https://tecnohost.net/mi-cuenta/view-subscription/' . $id_orden_suscripcion . '">#' . $id_orden_suscripcion . ' ' . $name . '</a>
                                </td>
                                <td>
                                    ' . $dat_inicio . '
                                </td>
                                <td>
                                    ' . $dat_fin . '
                                </td>
                                <td>
                                    ' . $moneda . ' $' . number_format(get_post_meta($id_orden_suscripcion, '_order_total', true), 2) . '
                                </td>
                            </tr>
                    ';
        $contad++;
    }
    $tabla_productos .=
        '
            </tbody>
        </table>
    </div>';

    return $tabla_productos;
}

function consultar_contenido(){

    $id = $_POST['id'];

    $corrreo = get_post_meta($id, "correo", true);


    $contenido = ' <div class="close" data-close="'.$id.'"><i class="fas fa-times"></i></div>
                        <div class="container_email">
                            <div class="cont_email_cuer">
                                <div>
                                    <img src="https://tecnohost.net/wp-content/uploads/2021/02/Email-marketing_Plantilla_Tope.jpg" alt="" width="650" height="110" />
                                </div>
                                <div class="cont_email_content">
                                    '.$corrreo.'
                                    </div>
                                <div class="tecnoenlac">
                                    <a style="color: #000000; font-weight: normal; text-decoration: underline; padding: 20px;" href="https://tecnosoluciones.com">TecnoSoluciones.com</a>
                                </div>
                                <div class="redes_scemail">
                                    <img id="siguenos" src="https://tecnohost.nettecnohost.net/wp-content/uploads/2019/03/Email-marketing_Plantilla_Redes.jpg" alt="facebook" /> 
                                    <img src="https://tecnohost.nettecnohost.net/wp-content/uploads/2019/03/E-mail-Marketing_PLANTILLA_TS-2018_13.jpg" alt="facebook" /> 
                                    <img src="https://tecnohost.nettecnohost.net/wp-content/uploads/2019/03/E-mail-Marketing_PLANTILLA_TS-2018_15.jpg" alt="twitter" /> 
                                    <img src="https://tecnohost.nettecnohost.net/wp-content/uploads/2019/03/E-mail-Marketing_PLANTILLA_TS-2018_17.jpg" alt="instagram" /> 
                                    <img src="https://tecnohost.nettecnohost.net/wp-content/uploads/2019/03/E-mail-Marketing_PLANTILLA_TS-2018_19.jpg" alt="linkedin" /> 
                                    <img src="https://tecnohost.nettecnohost.net/wp-content/uploads/2019/03/E-mail-Marketing_PLANTILLA_TS-2018_21.jpg" alt="youtube" />
                                </div>
                                <div>
                                    <img src="https://tecnohost.net/wp-content/uploads/2021/02/Email-marketing_Plantilla_Footer.jpg" alt="footer" />
                                </div>
                            </div>
                        </div>';

    echo json_encode($contenido);

    wp_die();
}

function Almacenar_emailsnuevos($workflow, $asunto, $contenido){

    $control = $workflow->get_trigger()->get_supplied_data_items();

    foreach($control AS $key => $value ){
        if($value == 'order'){
            $data = $workflow->data_layer()->get_order();
            break;
        }
        else if($value == 'subscription'){
            $data = $workflow->data_layer()->get_subscription();
            break;
        }
    }

    $datos = $data->get_data();
    $id_usuer = $datos['customer_id'];

    $id = $datos['id'];
    $parent_id = $datos['parent_id'];
    if($parent_id == 0){
        $id_suscripcion = 0;
        $id_pedido = $id;
    }
    else {
        $id_suscripcion = $id;
        $id_pedido = $parent_id;
    }

    $post_data = array(
        'post_title'    => wp_strip_all_tags( $asunto ),
        'post_status'   => 'publish',
        'post_type'     => 'email',
        'post_author'   => '1',
        'post_category' => '',
        'meta_input'   => array(
            'correo' => $contenido,
            'id_usuario' => $id_usuer,
            'id_pedido' => $id_pedido,
            'fecha_de_envio' => date('Y-m-d H:i:s'),
            'id_suscripcin' => $id_suscripcion
        ),
        'page_template' => NULL
    );

    wp_insert_post( $post_data );
}

function actualizar_renewal_order_prices_ajuste($workflow){

}

function producto_contratado_cambio(){
    $id_producto = $_POST['id_producto'];
    $preparar_var = explode("=",$_SERVER['HTTP_REFERER']);
    $suscription_id = explode("&",$preparar_var[1])[0];
    $order = wc_get_order($suscription_id);
    $dom_dat = "";
    $items = $order->get_items();
    foreach ( $items as $item ) {
        foreach($item->get_meta_data() as $key => $value){
            if($value->key == "Nombre del Dominio" || $value->key == "Dominio"){
                if($value->value != ""){
                    $dom_dat = $value->value;
                    break;
                }
            }
        }
    }
    $product = wc_get_product($id_producto);
    $title = $product->get_title();
    $prices = $product->get_price_html();
    $atributos = maybe_unserialize(get_post_meta($id_producto,"_product_attributes",true));
    $image = wp_get_attachment_image_src( (int) get_post_meta( $id_producto, '_thumbnail_id', true ), 'single-post-thumbnail' );
    $image = "<img src='".$image['0']."' class='img-fluid' alt='".$title."'>";
    $atributos_html = '
        <table>
            <tbody>
    ';

    foreach($atributos AS $key => $value){
        switch($key){
            case"espacio-mb":
            case"trafico-mes-gb":
            case"subdominios":
            case"dominios":
            case"cuentas-de-emails":
            case"listas-de-correos":
            case"base-de-datos-mysql":
            case"cuentas-ftp":
            case"panel-reseller-whm":
                $atributos_html .= '
                    <tr>
                        <td>'.$value['name'].'</td>
                        <td>'.$value['value'].'</td>
                    </tr>    
                ';
                break;
        }
    }

    $titulo = '<h2 class="titulo">Plan actual</h2><input type="hidden" value="'.$dom_dat.'" id="dominio">';

    if($_POST['tipo'] == "plan_nuevo"){
        $titulo = '<h2 class="titulo">Plan Nuevo</h2>';
    }
    $atributos_html .= '
            </tbody>
        </table>';

    $contenedor = '
        '.$titulo.'
        <div class="imagen">'.$image.'</div>
        <div class="titulo">'.$title.'</div>
        <div class="prices_cambio">'.$prices.'</div>
        <div class="atributos">'.$atributos_html.'</div>
        
    ';
    echo $contenedor;
    wp_die();
}

function obtener_dominio_cambio(){
    $id_suscription = $_POST['id_subscription'];
    $order_suscripcion =  wc_get_order($id_suscription);
    foreach ( $order_suscripcion->get_items() as $item ) {
        foreach($item->get_meta_data() as $key => $value){
            if($value->key == "Nombre del Dominio" || $value->key == "Dominio"){
                $dominio = $value->value;
                break;
            }
        }
    }
    echo $dominio;
    wp_die();
}

function obtener_variation_cambio(){
    $id_suscription = $_POST['id_subscription'];
    $order_suscripcion =  wc_get_order($id_suscription);
    foreach ( $order_suscripcion->get_items() as $item ) {
        $id_variation = $item->get_data()['variation_id'];
        break;
    }
    echo $id_variation;
    wp_die();
}

function consultar_servidor($servidor, $entry_id){
    $args = array(
        'post_type'             => 'datos_de_servidores',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => 1,
        'posts_per_page'        => '-1'
    );

    $wp_query = new WP_Query($args);
    if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();
            if($servidor == get_the_title()){
                $pid = get_the_ID();
                $datos = [
                    "ip_dns2" => get_post_meta($pid, "ip_dns2", true),
                    "dns_2" => get_post_meta($pid, "dns_2", true),
                    "ip_dns1" => get_post_meta($pid, "ip_dns1", true),
                    "dns_1" => get_post_meta($pid, "dns_1", true),
                    "ip_del_servidor" => get_post_meta($pid, "ip_del_servidor", true)
                ];
                actualizar_entry($datos, $entry_id);
                break;
            }
        endwhile;
    endif;
    return $datos;
}

function actualizar_entry($datos, $entry_id){
    gform_update_meta( $entry_id, '3', $datos['dns_1']);
    gform_update_meta( $entry_id, '20', $datos['ip_dns1']);
    gform_update_meta( $entry_id, '4', $datos['dns_2']);
    gform_update_meta( $entry_id, '21', $datos['ip_dns2']);
    gform_update_meta( $entry_id, '5', $datos['ip_del_servidor']);
}

function woo_add_cart_fee(){
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
        return;
    }

    global $wpdb;
    $renovacion = 0;
    $addons_prices = 0;
    $moneda = get_woocommerce_currency();
    $cambio_moneda = WOOMULTI_CURRENCY_Data::get_ins();
    $cambio_moneda = $cambio_moneda->currencies_list;
    foreach(WC()->cart->cart_contents as $key => $value) {
        if($value['subscription_renewal'] && count($value['subscription_renewal']) != 0){
            $id_suscripcion = $value['subscription_renewal']['subscription_id'];
            $id_orden_renovacion = $value['subscription_renewal']['renewal_order_id'];
            $renovacion = 1;
            $precio = $value['data']->get_data()['price'];
            $taxable = $value['data']->get_data()['tax_status'];
            $product_id = $value['product_id'];
            $variation_id = $value['variation_id'];
            $val_cate = wc_get_object_terms($product_id, 'product_cat', "term_id");
            switch($val_cate[0]){
                case 26:
                    $renovacion = 0;
                    break;
            }

            $addons = obtener_adicioonales_vps($id_suscripcion);
            $addons_prices = obtener_adicioonales_vps_prices($addons);
            break;
        }
    }

    if($renovacion == 1) {
        $mes_deposito_activo = 0;
        $order_suscripcion = wc_get_order($id_suscripcion);
        foreach ($order_suscripcion->get_items() as $item_id => $item) {
            if (wc_get_order_item_meta("$item_id", "¿Mes de depósito activo?", true) == "Si") {
                $mes_deposito_activo = 1;
            }
        }

        if ($mes_deposito_activo == 1 && $renovacion == 1) {

            add_filter('woocommerce_subscriptions_is_recurring_fee', '__return_true');

            if ($variation_id != 0) {
                $product_id = $variation_id;
            }

            $taxable = false;
            if ($taxable == "taxable") {
                $taxable = true;
            }

            $name = 'Cobro mes de depósito';
            $amount = $order_suscripcion->get_total();
            WC()->cart->add_fee($name, $amount, $taxable);
        }
    }
}

function precio_inicial_mes_vencido($variables){
    $variables['subscription']['precio_inicial_mes_vencido'] = dirname(__FILE__) . '/includes/precio_inicial_mes_vencido.php';
    return $variables;
}

function fecha_mes_deposito($variables){
    $variables['subscription']['fecha_mes_deposito'] = dirname(__FILE__) . '/includes/fecha_mes_deposito.php';
    return $variables;
}

function precio_mes_activacion($variables){
    $variables['subscription']['precio_mes_activacion'] = dirname(__FILE__) . '/includes/precio_mes_activacion.php';
    return $variables;
}

function validar_vps_prices($cart_object){
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
        return;
    }

    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if($value['subscription_renewal'] && count($value['subscription_renewal']) != 0){
                $suscripcion_id = $value['subscription_renewal']['renewal_order_id'];
                $periodo = get_post_meta($suscripcion_id, '_billing_period', true);
                if($periodo == "year"){
                    return 0;
                }
                $id_pedido_padre = wp_get_post_parent_id( $suscripcion_id );
                if($id_pedido_padre != 0){
                    $order =  wc_get_order($suscripcion_id);
                    $items = $order->get_items();
                    foreach ( $items as $item ) {
                        $id_ = $item->get_product_id();
                        break;
                    }
                    $product = wc_get_product($id_);
                    $id_product_categ = $product->get_category_ids();
                    foreach ($id_product_categ AS $key_cat => $value_cat){
                        switch($value_cat){
                            case 447:
                            case 448:
                            case 446:
                            case 449:
                                $suscripcion = wc_get_order($suscripcion_id);
                                $prices = $suscripcion->get_total();
                                foreach ( $cart_object->cart_contents as $key => $value ) {
                                    $value['data']->set_price($prices);
                                }
                                break;
                        }
                    }
                }
            }
        }
    }
}

function excluir_suscripcion($rules){
    $rules['excluir_sus'] = dirname(__FILE__) . '/includes/excluir_sus.php'; // absolute path to rule
    return $rules;
}


add_filter('automatewoo/rules/includes', 'susbcriptions_email_automatewoo_rules' );

/**
 * @param array $rules
 * @return array
 */
function susbcriptions_email_automatewoo_rules( $rules ) {
	$rules['subscription_suspend'] = dirname(__FILE__) . '/includes/subscription_suspend.php'; // absolute path to rule
	return $rules;
}

// Hook para validar la moneda de las renovaciones de suscripción antes de cargar el checkout
add_action('woocommerce_before_checkout_form', 'validar_moneda_renovacion_suscripcion');

function validar_moneda_renovacion_suscripcion() {
     // Verificar si hay productos de suscripción en el carrito
        // Recorrer los productos en el carrito
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
             if($cart_item['subscription_renewal'] && count($cart_item['subscription_renewal']) != 0){
                        $current_currency = get_woocommerce_currency();

                    // Verificar si el producto es una renovación de suscripción
                        // Obtener la moneda del producto de suscripción
                        $id_suscripcion = $cart_item['subscription_renewal']['subscription_id'];
                        $order_currency = get_post_meta($id_suscripcion, '_order_currency', true);

                        // Comparar la moneda del producto de suscripción con la moneda actual
                        if ($order_currency !== $current_currency) {

                            ?>
                            <script>
                                    var moneda_orden_ = '<?php echo $order_currency; ?>';

                                    jQuery('.wmc-select-currency-js').val(moneda_orden_);
                                    wmcSwitchCurrency(jQuery('.wmc-select-currency-js'));
                                    window.location.reload()

                            </script>
                            <?php
                        }
             }


        }
}

?>