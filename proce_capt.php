<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 24/10/2018
 * Time: 4:24 PM
 */
// your secret key
require_once "recaptchalib.php";
$secret = "6LeamZ4UAAAAAMSE_fgd-EbN0rZPjOzqCLDSDsuH";

// empty response
$response = null;

// check secret key
$reCaptcha = new ReCaptcha($secret);
if ($_POST["recaptcha_response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["recaptcha_response"]
    );

    echo $response->success;
}

?>