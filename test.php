<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 18/08/2020
 * Time: 12:25 PM
 */

$variable = 'a:2:{s:8:"subs_key";i:80527;s:5:"notes";a:2:{i:0;a:2:{s:4:"date";s:19:"2020-08-12 10:38:23";s:7:"message";s:72:"El Email " Vence Mañana " se ha agregado a la cola (Id. De cola #6448 )";}i:1;a:2:{s:4:"date";s:19:"2020-08-12 10:38:23";s:7:"message";s:65:"El Email está programado para ser enviado en 2021-11-14 22:53:10";}}}';

imprimir($variable);

function imprimir($datos){
    echo '<pre>';
    print_r($datos);
    echo '</pre>';
}