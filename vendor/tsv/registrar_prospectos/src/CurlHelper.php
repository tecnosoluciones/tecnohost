<?php
/*************************************************************************
 * Código Fuente desarrollado y/o mejorado por Tecno-Soluciones de Venezuela C.A.
 *
 *
 * CurlHelper.php creado por: Tecnosoluciones
 * En fecha: 30/06/2016
 **************************************************************************/

namespace TSV\Component\RegistrarProspectos;


class CurlHelper
{
    /**
     * Ejecuta la logica para una llamada via cURL.
     * @param $url
     * @param array $params
     * @param string $method
     * @param bool $followlocation
     * @param null|string $handleCookie
     * @param string $agent
     * @return mixed
     */
    public static function request(
        $url,
        array $params = array(),
        $method = 'GET',
        $followlocation = false,
        $handleCookie = null,
        $agent = 'Mozilla/4.0 (compatible; TSVRobot_RegistrarProspectos/1.0; +http://www.tecnosoluciones.com)'
    ) {
        $ch = curl_init();
        $paramString = http_build_query($params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followlocation); // evita redirects

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramString);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Keep-Alive', 'Keep-Alive: 60'));
            if ($handleCookie) {
                curl_setopt($ch, CURLOPT_COOKIEFILE, $handleCookie);
                curl_setopt($ch, CURLOPT_COOKIEJAR, $handleCookie);
            }
        } else {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $url . ($paramString ? '?' . $paramString : ''));
        }

        $response = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);

        curl_close($ch);
        return $response;
    }
}