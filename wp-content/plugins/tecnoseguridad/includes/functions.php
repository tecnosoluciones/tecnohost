<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 15/07/2021
 * Time: 9:42 AM
 */

defined( 'ABSPATH' ) or die( 'Acceso denegado' );

//require_once 'widget.php';

add_shortcode('two_factor_ts', 'mostra_two_factoir');

function mostra_two_factoir(){
    $users            = wp_get_current_user();
    $user_id = get_current_user_id();
    $respuesta_version = validar_version();

    if($respuesta_version >= "7.0.1"){
        wp_enqueue_script( 'two-factor-totp-admin', get_site_url().  '/wp-content/plugins/ithemes-security-pro/core/modules/two-factor/providers/js/totp-admin.js', array( 'jquery' ), null, true );
    }
    else{
        wp_enqueue_script( 'two-factor-totp-admin', get_site_url().  '/wp-content/plugins/ithemes-security-pro/pro/two-factor/providers/js/totp-admin.js', array( 'jquery' ), null, true );
    }

    wp_localize_script('two-factor-totp-admin','twofact',['ajaxurl'=>admin_url('admin-ajax.php')]);

    echo '<form id="your-profile" action="'.esc_url( admin_url('admin-post.php') ).'" method="post" novalidate="novalidate">';
    /*if ( $wp_http_referer ) :
	    echo '<input type="hidden" name="wp_http_referer" value="'.esc_url( $wp_http_referer ).'" />';
	endif;*/
    echo "
        <div id='usuari_two_fac'>";
    $itsec_two_factor = ITSEC_Two_Factor::get_instance();
    $respuesta = $itsec_two_factor->user_two_factor_options($users);


    wp_nonce_field( 'save_account_details', 'save-account-details-nonce' );
    echo '<input type="hidden" name="action" value="guardar_vainas" />';
    echo '<input type="hidden" name="user_id" id="user_id" value="'.$user_id.'">';
    echo '<button type="submit" class="woocommerce-Button button" name="save_account_details" value="';
    esc_attr_e( 'Save changes', 'woocommerce' );
    echo '">';
    esc_html_e( 'Save changes', 'woocommerce' );
    echo '</button>';
    echo "</div></form>";

    echo "
    <script>
        jQuery(window).on( 'load', function() {
            ajaxurl = twofact.ajaxurl;
        } );
    </script>
    
    
    ";
}

add_action('admin_post_guardar_vainas', 'procese_update');
function procese_update(){
    $user_id = get_current_user_id();
    $itsec_two_factor = ITSEC_Two_Factor::get_instance();
    $respuesta = $itsec_two_factor->user_two_factor_options_update($user_id);

    wp_redirect( get_home_url() . '/'. $_POST['_wp_http_referer']); exit;
}

function validar_version(){

    $all_items = apply_filters( 'all_plugins', get_plugins() );
    foreach ($all_items AS $key => $value){
        if($value['Name'] == "iThemes Security Pro"){
            return $value['Version'];
        }
    }
}

function imprimir_plugin($datos){
    echo '<pre>';
    print_r($datos);
    echo '</pre>';
}

/*
 * Part 1. Add Link (Tab) to My Account menu
 */
add_filter ( 'woocommerce_account_menu_items', 'auth_tow_fact_tecno', 40 );
function auth_tow_fact_tecno( $menu_links ){

    $menu_links = array_slice( $menu_links, 0, 5, true )
        + array( 'autenticacion_en_dos_factores' => 'Autenticación en dos factores' )
        + array_slice( $menu_links, 5, NULL, true );

    return $menu_links;

}
/*
 * Part 2. Register Permalink Endpoint
 */
add_action( 'init', 'auth_tow_fact_tecno_add_endpoint' );
function auth_tow_fact_tecno_add_endpoint() {

    // WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
    add_rewrite_endpoint( 'autenticacion_en_dos_factores', EP_PAGES );

}
/*
 * Part 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_autenticacion_en_dos_factores_endpoint', 'auth_tow_fact_tecno_my_account_endpoint_content' );
function auth_tow_fact_tecno_my_account_endpoint_content() {

    // Of course, you can print dynamic content here, one of the most useful functions here is get_current_user_id()
    echo do_shortcode('[two_factor_ts]');

}