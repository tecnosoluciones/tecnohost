<?php
/*
Plugin Name: Tecno Seguridad
Plugin URI: https://tecnosoluciones.com
Description: Permite la integración de la verificación de dos factores de Ithemes Security Pro con Woocommerce
Version: 1.0
Author: TecnoSoluciones de Colombia S.A.S
Author URI: https://tecnosoluciones.com
License: Undefined
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define('TECNO_SEGURIDAD_RUTA',plugin_dir_path(__FILE__));
define('TECNO_SEGURIDAD_NOMBRE', 'Tecno Seguridad');

add_action("wp_enqueue_scripts", "js_seguridad");
add_action( 'wp_enqueue_scripts', 'styles_seguridad');

function styles_seguridad() {
    wp_enqueue_style( 'tecno-seguridad-styles',plugins_url( '/css/styles.css',__FILE__) ,array());
}

function js_seguridad(){
    wp_register_script('tecno-seguridad-scripts', plugins_url('/js/tecno_seg.js',__FILE__),array('jquery'),1.0,true);
    wp_enqueue_script('tecno-seguridad-scripts');
}

include(TECNO_SEGURIDAD_RUTA.'/includes/opciones.php');
include(TECNO_SEGURIDAD_RUTA.'/includes/functions.php');