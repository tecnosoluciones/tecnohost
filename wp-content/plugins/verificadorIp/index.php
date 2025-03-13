<?php
/*
Plugin Name: Verificador de IP
Plugin URI: https://tecnosoluciones.com
Description: Verifica las IP'S bloqueadas en los diferentes servidores
Version: 1.0
Author: TecnoSoluciones de Colombia S.A.S
Author URI: https://tecnosoluciones.com
License: Undefined
*/


require_once 'widget.php';

/* Registro de verificador de IP'S */
add_action( 'widgets_init', function(){
    register_widget( 'VerificarIp' );
});



function main_js(){

    wp_register_script('custom-scripts', plugins_url('/js/funciones.js',__FILE__),array('jquery'),1.2,true);
    wp_enqueue_script('custom-scripts');

}

add_action("wp_enqueue_scripts", "main_js");

function styles_verificar_ip() {
    wp_enqueue_style( 'custom-styles',
        plugins_url( '/css/style-ip.css',__FILE__) ,
        array()
    );
}
add_action( 'wp_enqueue_scripts', 'styles_verificar_ip');


//Hook para hacer uso de la API
add_action( 'rest_api_init', function () {
    register_rest_route( 'verificar-ip/v1', '/search', array(
        'methods' => 'POST',
        'callback' => 'verificar_ip',
    ) );
} );



function post_request($url, $data, $referer='') {
 
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
 
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
 
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);
 
    if ($fp){
 
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
 
        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");
 
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
 
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}

function getClientIP() {
    return $_SERVER["REMOTE_ADDR"];
}

//Funci¨®n Callback
function verificar_ip( $data ) {


    header("Content-Type: text/html; charset=UTF-8");
    $ip = trim($data["ip"]);
    if ( empty($ip) ){
        echo '<p class="error-ip">Por favor, ingrese su IP</p>';
    }else if(!filter_var($ip, FILTER_VALIDATE_IP)) {
        echo '<p class="error-ip">Por favor, ingrese una IP v¨¢lida</p>';
    }else{

          //$server1 = post_request("http://admin01.1ahost.com/ips/index.php",array("op"=>"buscar","ip"=>$ip));
          $server2 = post_request("http://admin02.1ahost.com/ips/index.php",array("op"=>"buscar","ip"=>$ip));
          $server3 = post_request("http://admin03.1ahost.com/ips/index.php",array("op"=>"buscar","ip"=>$ip));
          $server4 = post_request("http://admin04.1ahost.com/ips/index.php",array("op"=>"buscar","ip"=>$ip));
          //$server10 = post_request("http://admin10.1ahost.com/ips/index.php",array("op"=>"buscar","ip"=>$ip));

          //$resp1 = preg_match("/no se encuentra bloqueada/",$server1["content"]);
          $resp2 = preg_match("/no se encuentra bloqueada/",$server2["content"]);
          $resp3 = preg_match("/no se encuentra bloqueada/",$server3["content"]);
          $resp4 = preg_match("/no se encuentra bloqueada/",$server4["content"]);
          //$resp10 = preg_match("/no se encuentra bloqueada/",$server10["content"]);

        if($resp2 and $resp3 and $resp4 ){
            echo '<p class="no-ip"> <i class="fa fa-check"></i>&nbsp;IP no bloqueada</p>';
        }else{
            echo "<p class='error-ip'>";

            // if (!$resp1){
            //     echo "<b>EN EL SERVIDOR 1</b>: ".utf8_encode($server1["content"])."<br><br>";
            // }

            if (!$resp2){
                echo "<b>EN EL SERVIDOR 02</b>: ".utf8_encode($server2["content"])."<br><br>";
            }

            if (!$resp3){
                echo "<b>EN EL SERVIDOR 03</b>: ".utf8_encode($server3["content"])."<br><br>";
            }

            if (!$server4){
                echo "<b>EN EL SERVIDOR 04</b>: ".utf8_encode($server4["content"])."<br><br>";
            }

            // if (!$resp10){
            //     echo "<b>EN EL SERVIDOR 10</b>: ".utf8_encode($server10["content"])."<br><br>";
            // }

            echo "</p>";

        }
    }
   exit();
}

