<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 2/03/2021
 * Time: 11:50 AM
 */

function pushMauticForm($data, $formId, $ip = null)
{
    // Get IP from $_SERVER
    if (!$ip) {
        $ipHolders = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );
        foreach ($ipHolders as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    // Multiple IPs are present so use the last IP which should be the most reliable IP that last connected to the proxy
                    $ips = explode(',', $ip);
                    array_walk($ips, create_function('&$val', '$val = trim($val);'));
                    $ip = end($ips);
                }
                $ip = trim($ip);
                break;
            }
        }
    }

    $data['formId'] = $formId;
    // return has to be part of the form data array
    if (!isset($data['return'])) {
        $data['return'] = 'https://tecnhost.net/';
    }
    $data = array('mauticform' => $data);
    // Change [path-to-mautic] to URL where your Mautic is
    $formUrl =  'https://tmatic.tecnosoluciones.com/form/submit?formId=' . $formId;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_URL, $formUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-For: $ip"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    curl_close($ch);
    return $response;
}