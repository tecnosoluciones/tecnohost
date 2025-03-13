<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 15/07/2021
 * Time: 9:44 AM
 */
function prefijo_menu_administrador()
{
    add_menu_page(TECNO_SEGURIDAD_NOMBRE,TECNO_SEGURIDAD_NOMBRE,'manage_options',TECNO_SEGURIDAD_RUTA . '/admin/configuracion.php');
    add_submenu_page(TECNO_SEGURIDAD_RUTA . '/admin/configuracion.php','Documentación','Documentación','manage_options',TECNO_SEGURIDAD_RUTA . '/admin/documentacion.php');

}
add_action( 'admin_menu', 'prefijo_menu_administrador' );