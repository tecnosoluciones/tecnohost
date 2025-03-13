<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 2/03/2021
 * Time: 11:50 AM
 */

require(dirname(__FILE__, 4)."/vendor/autoload.php");

use TSV\Component\RegistrarProspectos\RegistrarProspectos;
//Parametros de conexion TecnoCRM
define('Tcrm_URL','https://tcrm.tecnosoluciones.com/webservice.php');
define('Tcrm_USER','tecnonator');
define('Tcrm_PASSWORD','TSV#Robot-01');
define('CRM_TOKEN', '6ybcydAIdiHPLOO');

validar($datos_CRM);

function validar($datos){
    $db_server = 'tcrm.tecnosoluciones.com';
    $db_username = 'tecnosol_tcrm';
    $db_password = '6EC~Mq+z;K;N';
    $db_name = 'tecnosol_tcrm';

    $mysqli = new mysqli("tcrm.tecnosoluciones.com", 'tcrm_admin', '6EC~Mq+z;K;N', 'tcrm_principal');
    $registroProspecto = new RegistrarProspectos(array(
        'TecnoCRM' => array(
            'webservice_url' => Tcrm_URL,
            'username' => Tcrm_USER,
            'access_key' => CRM_TOKEN,
        )
    ));

    $sql = 'SELECT vtiger_leaddetails.leadid,vtiger_leaddetails.converted FROM vtiger_crmentity INNER JOIN vtiger_leaddetails ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid 
    WHERE vtiger_leaddetails.email = "'.$datos['email'].'" AND vtiger_crmentity.deleted = 0';
    $result = $mysqli->query($sql);

    if ($result->num_rows != 0) {
        $respuesta = $result->fetch_assoc();
        if($respuesta['converted'] == 0) {
            //$respuesta_api = $registroProspecto->describir_modulo("Leads");
            //imprimir($respuesta_api);
            $respuesta_update_lead = actualizar_lead($registroProspecto, $respuesta['leadid'], $datos);
            if ($respuesta_update_lead->success == 1) {
                echo "<div style='visibility: hidden'>1</div>";
            } else {
                echo "<div style='visibility: hidden'>666</div>";
            }
            $mysqli->close();
        }
        else{
            /*$respuesta_api = $registroProspecto->describir_modulo("Contacts");
            imprimir($respuesta_api);*/
            $validar_contacto = validar_contacto($datos['email']);

            if($validar_contacto == 0){
                $validar_eliminado = validar_contacto_eliminado($datos['email']);
                $respuesta_reactivar_contacto = reactivar_contacto($validar_eliminado);
                if($respuesta_reactivar_contacto == 1){
                    $respuesta_update_contact = actualizar_contacto($registroProspecto, $validar_eliminado, $datos);
                    if ($respuesta_update_contact->success == 1) {
                        echo "<div style='visibility: hidden'>1</div>";
                    } else {
                        echo "<div style='visibility: hidden'>666</div>";
                    }
                }
            }
            else {
                $respuesta_update_contact = actualizar_contacto($registroProspecto, $validar_contacto, $datos);
                if ($respuesta_update_contact->success == 1) {
                    echo "<div style='visibility: hidden'>1</div>";
                } else {
                    echo "<div style='visibility: hidden'>666</div>";
                }
            }
        }
    } else {
        $respuesta_lead_eliminado = validar_lead_eliminado($datos['email']);
        if($respuesta_lead_eliminado != 0) {
            $respuesta_reactivar_lead = reactivar_lead($respuesta_lead_eliminado);
            if ($respuesta_reactivar_lead == 1) {
                $respuesta_update_lead = actualizar_lead($registroProspecto, $respuesta_lead_eliminado, $datos);
                if ($respuesta_update_lead->success == 1) {
                    echo "<div style='visibility: hidden'>1</div>";
                } else {
                    echo "<div style='visibility: hidden'>666</div>";
                }
            }
        }else{
            $respuesta1 = $registroProspecto->registrarProspectoCRM($datos);
            if ($respuesta1['success'] == 1) {
                echo "<div style='visibility: hidden'>1</div>";
            } else {
                echo "<div style='visibility: hidden'>666</div>";
            }
        }
    }
}

function validar_contacto($email){
    $mysqli = new mysqli("tcrm.tecnosoluciones.com", 'tcrm_admin', '6EC~Mq+z;K;N', 'tcrm_principal');
    $query = 'SELECT vtiger_contactdetails.contactid FROM vtiger_contactdetails
    INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid 
    WHERE vtiger_contactdetails.email = "'.$email.'" AND vtiger_crmentity.deleted = 0';
    $consulta = $mysqli->query($query);
    $respuesta = $consulta->fetch_all();
    $mysqli->close();
    if(count($respuesta) > 0){
        return $respuesta[0][0];
    }
    else{
        return 0;
    }
}

function validar_lead_eliminado($email){
    $mysqli = new mysqli("tcrm.tecnosoluciones.com", 'tcrm_admin', '6EC~Mq+z;K;N', 'tcrm_principal');
    $sql = 'SELECT vtiger_leaddetails.leadid,vtiger_leaddetails.converted FROM vtiger_crmentity INNER JOIN vtiger_leaddetails ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid 
    WHERE vtiger_leaddetails.email = "'.$email.'" AND vtiger_crmentity.deleted = 1 ORDER BY vtiger_crmentity.modifiedtime DESC LIMIT 1';
    $consulta = $mysqli->query($sql);
    if(!empty($consulta)){
        $respuesta = $consulta->fetch_all();
        $mysqli->close();
        if(count($respuesta) > 0){
            return $respuesta[0][0];
        }else{
            return 0;
        }
    }
    else{
        return 0;
    }
}

function validar_contacto_eliminado($email){
    $mysqli = new mysqli("tcrm.tecnosoluciones.com", 'tcrm_admin', '6EC~Mq+z;K;N', 'tcrm_principal');
    $query = 'SELECT vtiger_contactdetails.contactid FROM vtiger_contactdetails
    INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid 
    WHERE vtiger_contactdetails.email = "'.$email.'" AND vtiger_crmentity.deleted = 1 ORDER BY vtiger_crmentity.modifiedtime DESC LIMIT 1';
    $consulta = $mysqli->query($query);
    $respuesta = $consulta->fetch_all();
    $mysqli->close();
    if(count($respuesta) > 0){
        return $respuesta[0][0];
    }
    else{
        return 0;
    }
}

function reactivar_lead($id_lead){
    $mysqli = new mysqli("tcrm.tecnosoluciones.com", 'tcrm_admin', '6EC~Mq+z;K;N', 'tcrm_principal');
    $query = "UPDATE `vtiger_crmentity` SET `deleted`='0' WHERE (`crmid`='".$id_lead."') LIMIT 1";
    $mysqli->query($query);
    $varia_contro = $mysqli->affected_rows;
    $mysqli->close();
    if($varia_contro == 1){
        return $varia_contro;
    }
    else{
        return 0;
    }
}

function reactivar_contacto($id_contacto){
    $mysqli = new mysqli("tcrm.tecnosoluciones.com", 'tcrm_admin', '6EC~Mq+z;K;N', 'tcrm_principal');
    $query = "UPDATE `vtiger_crmentity` SET `deleted`='0' WHERE (`crmid`='".$id_contacto."') LIMIT 1";
    $mysqli->query($query);
    $varia_contro = $mysqli->affected_rows;
    $mysqli->close();
    if($varia_contro == 1){
        return $varia_contro;
    }
    else{
        return 0;
    }
}

function actualizar_lead($registroProspecto, $id_prospecto, $datos){
    $datos_leads = $registroProspecto->get_data("10x" . $id_prospecto);
    $datos_leads = $datos_leads->result;
    $cf_1331 = explode(" |##| ", $datos_leads->cf_1331);
    $control = 0;
    foreach ($cf_1331 AS $key => $value) {
        if ($value == $datos['cf_1331']) {
            $control = 1;
            break;
        }
    }
    if ($control == 0) {
        $cf_1331[] = $datos['cf_1331'];
    }
    $datos['cf_1331'] = $cf_1331;
    $datos['id'] = $datos_leads->id;
    $datos_leads_update = $datos;
    $respuesta_update_lead = $registroProspecto->actua_lead($datos_leads_update);
    return $respuesta_update_lead;
}

function actualizar_contacto($registroProspecto, $validar_contacto, $datos){
    $datos_contact = $registroProspecto->get_data("12x" . $validar_contacto);
    $datos_contact = $datos_contact->result;
    unset($datos['page_name']);
    unset($datos['company']);
    unset($datos['leadstatus']);
    unset($datos['rating']);
    $cf_1341 = explode(" |##| ", $datos_contact->cf_1341);
    $control = 0;
    foreach ($cf_1341 AS $key => $value) {
        if ($value == $datos['cf_1331']) {
            $control = 1;
            break;
        }
    }
    if ($control == 0) {
        $cf_1341[] = $datos['cf_1331'];
    }
    $datos_contact_update = [
        'firstname' => $datos['firstname'],
        'lastname' => $datos['lastname'],
        'email' => $datos['email'],
        'phone' => $datos['phone'],
        'mailingcountry' => $datos['country'],
        'othercountry' => $datos['country'],
        'mailingstate' =>  $datos['state'],
        'otherstate' =>  $datos['state'],
        'leadsource' =>  $datos['leadsource'],
        'cf_868' =>  $datos['cf_872'],
        'mailingstreet' =>  $datos['lane'],
        'otherstreet' =>  $datos['lane'],
        'mailingcity' =>  $datos['city'],
        'othercity' =>  $datos['city'],
        'assigned_user_id' =>  $datos['assigned_user_id'],
        'id' =>  $datos_contact->id,
        'cf_1566' =>  $datos_contact->cf_1566,
        'mailingzip' =>  $datos['code'],
        'otherzip' =>  $datos['code'],
        'cf_1341' =>  $cf_1341
    ];

    $respuesta_update_contact = $registroProspecto->actua_contact($datos_contact_update);
    return $respuesta_update_contact;
}








?>
