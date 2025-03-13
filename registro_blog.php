<?php
/**
 * Created by PhpStorm.
 * User: tecnosoluciones
 * Date: 16/01/2018
 * Time: 01:36 PM
 * Leyenda de listas y paginas de uscripcion
 * Pagina de subscripcción información: de valor sobre Seguridad Industrial y Residencial
 * Id Pag: 2
 * List: 3
 * Pagina de subscripcción: interesados en el producto
 * ID Pag: 3
 * List: 4
 */

require(__DIR__."/vendor/autoload.php");
use TSV\Component\RegistrarProspectos\RegistrarProspectos;
//Parametros de conexion TecnoCRM
define('Tcrm_URL','https://tcrm.tecnosoluciones.com/webservice.php');
define('Tcrm_USER','tecnonator');
define('Tcrm_PASSWORD','TSV#Robot-01');
define('CRM_TOKEN', '6ybcydAIdiHPLOO');

$nombre = (empty($_POST['nombre'])) ? "" : $_POST['nombre'];
$apellido = (empty($_POST['apellido'])) ? "" : $_POST['apellido'];
$telefono = (empty($_POST['telefono'])) ? "" : $_POST['telefono'];
$correo = (empty($_POST['correo'])) ? "" : $_POST['correo'];
$empresa = (empty($_POST['empresa'])) ? "" : $_POST['empresa'];
$pais = (empty($_POST['pais'])) ? "" : $_POST['pais'];
$ciudad = (empty($_POST['ciudad'])) ? "" : $_POST['ciudad'];
$departamento = (empty($_POST['departamento'])) ? "" : $_POST['departamento'];
$caso = (empty($_POST['caso'])) ? "" : $_POST['caso'];
$referidor= (empty($_POST['referidor'])) ? "tecnosoluciones.com" : $_POST['referidor'];
$servicio = (empty($_POST['servicio'])) ? "" : $_POST['servicio'];
$correo_usuario = $_POST['correo_usuario'];

$url = $_POST['url'];

if($correo == ""){die;}
if($nombre == ""){die;}
if($apellido == ""){die;}
if($ciudad == "" && $_POST['tecnosoluciones'] != 2){die;}
switch($servicio){
    case"/consultoria-de-negocios-con-transformacion-digital/":
        $servicio = "CONSULTORIA TRANSFORMACION DIGITAL";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
    case"/estrategias-de-marketing-digital/":
        $servicio = "ESTRATEGIAS DE MARKETING DIGITAL";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
    case"/plataformas-de-automatizacion-de-ventas-y-gestion-de-clientes/":
        $servicio = "CRM ECOMMERCE CSC";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
    case"/presencia-basica-y-avanzada-en-internet/":
        $servicio = "PRESENCIA EN INTERNET";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
    case"/sistemas-de-gestion-online/":
        $servicio = "SISTEMAS DE GESTION ONLINE";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
    case"/capacitacion-profesional/":
        $servicio = "CAPACITACION PROFESIONAL";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
    case"/contacto/":
        $servicio = "CONTACTO";
        $origen = "Portal TecnoHost";
        $via = "Formulario de Contacto";
        break;
    case"/quienes-somos/":
        $servicio = "QUIENES SOMOS";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
    default:
        $servicio = "";
        $origen = "Portal TecnoHost";
        $via = "Formulario del Portal";
        break;
}

$registroProspecto = new RegistrarProspectos(array(
    'TecnoCRM' => array(
        'webservice_url' => Tcrm_URL,
        'username' => Tcrm_USER,
        'access_key' => CRM_TOKEN,
    )
));

$datos_CRM = array(
    'assigned_user_id' => '20x2',//ModuloIdxUserId
    "page_name" => "Tecno Host",
    "firstname" => $nombre,//NOMBRE
    "lastname" => $apellido,//APELLIDO
    "email" => $correo,//EMAIL
    'phone' => $telefono,
    'mobile' => $telefono,
    'country' => $pais,
    'company' => $empresa,
    'state' => $departamento,
    'description' => $caso,
    'leadsource' => $origen,
    'leadstatus' => 'No Contactado',
    'rating' => 'Iniciando',
    'cf_872' => $via,
    'city' => $ciudad,
    'cf_1333' => $referidor,
    'cf_1331' => $servicio,
);

$respuesta_crm = 0;

if($_POST['tecnosoluciones']){
    if($respuesta_crm <= 0){
        if($correo_usuario == ""){
            $respuesta1 = $registroProspecto->registrarProspectoCRM($datos_CRM);
            if($respuesta1->success == 1){
                echo 1;
            }
            else{
                validar($datos_CRM);
            }
        }
        else{
            echo 452;
        }
    }
}
else{
    //header('Location: https://tecnosoluciones.com/modules.php?name=Contact');
}

function validar($datos){
    $db_server = 'tcrm.tecnosoluciones.com';
    $db_username = 'tecnosol_tcrm';
    $db_password = '6EC~Mq+z;K;N';
    $db_name = 'tecnosol_tcrm';

    $mysqli = new mysqli("209.217.224.98", 'tecnosol_tcrm', '6EC~Mq+z;K;N', 'tecnosol_tcrm');

    $sql = 'SELECT vtiger_leadscf.cf_1331, vtiger_leaddetails.leadid
              FROM vtiger_leaddetails INNER JOIN vtiger_leadscf ON vtiger_leadscf.leadid = vtiger_leaddetails.leadid
              INNER JOIN vtiger_crmentity ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid
              WHERE vtiger_crmentity.deleted = 0 AND vtiger_leaddetails.email = "'.$datos['email'].'"';
    $result = $mysqli->query($sql);
    $respuesta = $result->fetch_assoc();
    $Values = explode(" |##| ", $respuesta['cf_1331']);
    $cont = 0;
    $i = 0;

    for($i =0;$i<count($Values);$i++){
        if($Values[$i] == $datos['cf_1331']){
            $cont++;
        }
    }

    if($cont == 0){
        if($datos['cf_1331'] == ""){
            $cf_1331 = $respuesta['cf_1331'];
        }else{
            $cf_1331 = $respuesta['cf_1331'].' |##| '.$datos['cf_1331'].'';
        }
    }
    else{
        $cf_1331 = $respuesta['cf_1331'];
    }

    $update_detail = 'UPDATE `vtiger_leaddetails` SET `firstname`="'.utf8_decode($datos['firstname']).'", lastname ="'.utf8_decode($datos['lastname']).'",
                company = "'.utf8_decode($datos['company']).'" WHERE `leadid`="'.$respuesta['leadid'].'";';
    $update_leadaddress = 'UPDATE `vtiger_leadaddress` SET `city`="'.utf8_decode($datos['city']).'", state ="'.utf8_decode($datos['state']).'",
                country = "'.utf8_decode($datos['country']).'", phone = "'.$datos['phone'].'", phone = "'.$datos['mobile'].'" 
    WHERE `leadaddressid`="'.$respuesta['leadid'].'";';
    $update_leadscf = 'UPDATE `vtiger_leadscf` SET `cf_1331`="'.$cf_1331.'", cf_1333 = "'.$datos['cf_1333'].'" WHERE `leadid`="'.$respuesta['leadid'].'";';
    $update_crmentity = 'UPDATE `vtiger_crmentity` SET `description`= "'.utf8_decode($datos['description']).'", modifiedtime = "'.date('Y-m-d H:i:s').'" WHERE `crmid`="'.$respuesta['leadid'].'";';

    $mysqli->query($update_detail);
    $mysqli->query($update_leadaddress);
    $mysqli->query($update_leadscf);
    $mysqli->query($update_crmentity);
    echo 1;
    $mysqli->close();
}
?>