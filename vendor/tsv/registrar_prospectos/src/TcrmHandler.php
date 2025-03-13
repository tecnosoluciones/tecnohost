<?php
/*************************************************************************
 * Código Fuente desarrollado y/o mejorado por Tecno-Soluciones de Venezuela C.A.
 *
 *
 * TcrmHandler.php creado por: Tecnosoluciones
 * En fecha: 30/06/2016
 **************************************************************************/


namespace TSV\Component\RegistrarProspectos;

/**
 * Class TcrmHandler
 * @package TSV\Component\RegistrarProspectos
 *
 * Clase para comunicación con la API REST del TecnoCRM.
 * Documentación oficial: http://community.vtiger.com/help/vtigercrm/developers/third-party-app-integration.html
 *
 */
class TcrmHandler
{
    // atributos generales de la clase
    private static $_instance;
    public $logged = false;
    public $webService;
    public $username;
    public $accessKey;

    // atributos de la petición challenge antes del login
    public $challengeToken = null;
    public $challengeServerTime;
    public $challengeExpireTime;
    private $challenge_ttl;

    // atributos de la sesión, si el login tiene éxito
    public $sessionId = null;
    public $userId = null;

    /**
     * Define los parámetros fundamentales de la conexión con el TecnoCRM.
     *
     * $webservice_url debe ser una URL válida y terminar en webservice.php
     * Por ejemplo:  http://midominio.com/tcrm/webservice.php
     *
     * El valor de $access_key se consigue en la interfaz del CRM, bajo la opción de "Mis Preferencias",
     * en la sección "Opciones Avanzadas de Usuario" y "Clave de Acceso".
     *
     * Nota: la clase es privada para forzar el uso del metodo construct
     *
     * @param $webservice_url
     */
    public function __construct($webservice_url)
    {
        $this->webService = $webservice_url;
    }

    /**
     * Asignar el usuario y el access key para la conexion
     * @param $username
     * @param $access_key
     * @return $this
     */
    public function setAuthData($username, $access_key)
    {
        $this->setUsername($username);
        $this->setAccessKey($access_key);
        return $this;
    }

    /**
     * Ejecuta una llamada via cURL al CRM.
     * @param array $params
     * @param string $method
     * @return resource
     */
    public function doCurlRequest(array $params, $method = 'GET')
    {
        return CurlHelper::request($this->webService, $params, $method);
    }

    /**
     * Ejecuta el challenge de la API REST y establece la token en la instancia.
     * Es el primer paso que debe hacerse antes de cualquier otro.
     *
     * @return bool
     */
    public function challenge()
    {
        $params = array(
            'operation' => 'getchallenge',
            'username' => $this->username,
        );

        $raw_response = $this->doCurlRequest($params);
        $response = json_decode($raw_response);

        if ($response->success) {
            $this->challengeToken = $response->result->token;
            $this->challengeExpireTime = $response->result->expireTime;
            $this->challengeServerTime = $response->result->serverTime;
            /*
             * TODO verificar la diferencia de tiempo y comparar el limite local al tiempo de expiracion
             * La idea es obtener la diferencia horaria entre el servidor y este script,
             * con este tiempo de diferencia mas el intervalo de la sesion podemos saber cuando localmente expira el token
             */
            /*
            $localDateTime = new \DateTime();
            $serverDateTime = new \DateTime();
            $serverDateTime->setTimestamp($this->challenge_server_time);
            $diff = $localDateTime->diff($serverDateTime);
            $this->challenge_ttl = time();
            */

            return true;
        } else {
            return $response;
        }
    }

    /**
     * Intenta hacer login con los datos adquiridos con el método challenge.
     * Es el segundo paso antes de poder acceder a la API del TecnoCRM.
     *
     * @return bool
     */
    public function login()
    {
        if (!$this->challengeToken) {
            throw new \BadMethodCallException("No se ha establecido el challenge_token.");
        }

        // TODO: Revisar si el tiempo de expiración se ha cumplido antes de intentar la petición.

        $params = array(
            'operation' => 'login',
            'username' => $this->username,
            'accessKey' => md5($this->challengeToken . $this->accessKey)
        );

        $raw_response = $this->doCurlRequest($params, 'POST');
        $response = json_decode($raw_response);

        if ($response->success) {
            $this->sessionId = $response->result->sessionName;
            $this->userId = $response->result->userId;
            $this->logged = true;
            return true;
        } else {
            return $response;
        }
    }

    public function logout()
    {
        if (!$this->sessionId) {
            return false;
        }

        $params = array(
            'operation' => 'logout',
            'sessionName' => $this->sessionId,
        );
        $raw_response = $this->doCurlRequest($params, 'POST');
        $response = json_decode($raw_response);
        if ($response->success) {
            $this->sessionId = '';
            $this->userId = '';
            $this->logged = false;
            return true;
        } else {
            return $response;
        }
    }

    /**
     * Método genérico para la creación de entidades.
     *
     * @param $type
     * @param array $data
     * @return bool
     */
    public function create($type, array $data)
    {
        $params = array(
            'operation' => 'create',
            'sessionName' => $this->sessionId,
            'elementType' => $type,
            'element' => json_encode($data),
        );

        $raw_request = $this->doCurlRequest($params, 'POST');
        $request = json_decode($raw_request);

        if ($request->success) {
            return true;
        } else {
            return $request;
        }
    }

    /**
     * Método para la creación de entidades casos especiales.
     *
     * @param $type
     * @param array $data
     * @return bool
     */
    public function create_especial($type, array $data)
    {
        $params = array(
            'operation' => 'create',
            'sessionName' => $this->sessionId,
            'elementType' => $type,
            'element' => json_encode($data),
        );

        $raw_request = $this->doCurlRequest($params, 'POST');
        $request = json_decode($raw_request);
        $veamos = strrpos( $raw_request , '"success":true,' );
        if(isset($veamos) && $veamos > 0){
            return true;
        }else{
            return $request;
        }
    }

    /**
     * Crear un prospecto (lead).
     * @param array $data
     * @return bool
     */
    public function createLead(array $data)
    {
        return $this->create('Leads', $data);
    }

    /**
     * Crear un prospecto (lead).
     * @param array $data
     * @return bool
     */
    public function createLead_especial(array $data)
    {
        return $this->create_especial('Leads', $data);
    }


    /**
     * Genera una lista de las operaciones permitidas para el usuario con el que se creó la instancia de la API.
     *
     * @return mixed
     */
    public function listTypesOperation()
    {
        $params = array(
            'operation' => 'listtypes',
            'sessionName' => $this->sessionId,
        );

        $raw_request = $this->doCurlRequest($params);
        $request = json_decode($raw_request);

        return $request;
    }


    /**
     * Describe el tipo de entidad proporcionada. Las entidades que la instancia puede acceder se obtienen a través
     * del método listTypesOperation()
     *
     * @param $type
     * @return mixed
     */
    public function describeOperation($type)
    {
        $params = array(
            'operation' => 'describe',
            'sessionName' => $this->sessionId,
            'elementType' => $type,
        );

        $raw_request = $this->doCurlRequest($params);
        $request = json_decode(substr($raw_request, strpos ($raw_request, '{')));

        return $request;
    }

    /**
     * Método genérico para enviar el SQL limitado que permite la API de vTiger.
     *
     * El webservice puede ser bloqueado por el mod_security por lo que hay que crear una regla para evitar este bloqueo
     * Normalmente basta con incluir la excepcion al archivo de configuración del virtual host apache,
     * ubicada para el cpanel normalmente en '/usr/local/apache/conf/userdata/{USER}/{DOMAIN}/{SOMETHING}.conf'
     *
     * La regla seria algo como:
     * <LocationMatch /tcrm/webservice.php>
     *  <IfModule mod_security2.c>
     *      SecRuleRemoveById 950001
     *  </IfModule>
     * </LocationMatch>
     *
     * para mas informacion consultar los siguientes articulos
     * http://www.inmotionhosting.com/support/website/modsecurity/find-and-disable-specific-modsecurity-rules
     * http://wiki.atomicorp.com/wiki/index.php/Mod_security#Disable_a_rule_for_a_single_domain
     * http://linuxsysadminblog.com/2008/11/cpanel-adding-custom-configuration-to-httpdconf/
     *
     * @param $sql
     * @return resource
     */
    public function query($sql)
    {
        $params = array(
            'operation' => 'query',
            'sessionName' => $this->sessionId,
            'query' => $sql
        );

        return $this->doCurlRequest($params);
    }

    /**
     * Revisa si el email o teléfono móvil existen en el CRM.
     * @param array $criteria
     * @return resource
     */
    public function findLeadBy(array $criteria)
    {
        $sql = "SELECT email FROM  Leads WHERE 1=1";
        foreach ($criteria as $campo => $valor) {
            $sql .= " AND " . $campo . "= '" . $valor . "'";
        }
        $sql .= ";";//Debe ir el punto y coma al final
        $response = $this->query($sql);

        $payload = json_decode($response);

        if ($payload->success) {
            if (!empty($payload->result)) {
                return true;
            } else {
                return false;
            }
        } else {
            return $payload;
        }
    }

    public function retrieve($id_elemento){
        $params = array(
            'operation'   => 'retrieve',
            'sessionName' => $this->sessionId,
            'id' => $id_elemento,
        );

        $raw_request = $this->doCurlRequest($params, "GET");
        $request = json_decode(substr($raw_request, strpos ($raw_request, '{')));

        return $request;
    }

    /**
     * Asume que recibe un arreglo con elementos.
     * @param array $data
     * @param array $criteria
     * @return array|bool
     */
    public function leadRequest(array $data, array $criteria)
    {
        if ($this->logged) {
            $response = $this->createLead($data);

            if ($response === true) {
                return array('success' => true);
            } else {
                return array(
                    'success' => false,
                    'error' => 'LEAD_CREATION_FAILED',
                    'data' => $response
                );
            }

        } else {
            return array(
                'success' => false,
                'error' => 'NOT_LOGGED_IN'
            );
        }
    }

    /**
     * Asume que recibe un arreglo con elementos. Crear prospectos ya eliminados
     * @param array $data
     * @param array $criteria
     * @return array|bool
     */
    public function leadRequest_eliminado(array $data, array $criteria)
    {
        if ($this->logged) {

            $response = $this->createLead_especial($data);

            if ($response === true) {
                return array('success' => true);
            } else {
                return array(
                    'success' => false,
                    'error' => 'LEAD_CREATION_FAILED',
                    'data' => $response
                );
            }

        } else {
            return array(
                'success' => false,
                'error' => 'NOT_LOGGED_IN'
            );
        }
    }

    public function delete($id_elemento){
        $params = array(
            'operation'   => 'delete',
            'sessionName' => $this->sessionId,
            'id' => $id_elemento,
        );

        $raw_request = $this->doCurlRequest($params, "POST");
        $request = json_decode($raw_request);

        return $request;
    }

    /**
     * Mótodo genérico para la actualización de entidades.
     *
     * @param $type
     * @param $data
     * @return bool
     */
    public function update($type, $data) {
        $params = array(
            'operation'   => 'update',
            'sessionName' => $this->sessionId,
            'elementType' => $type,
            'element'     => json_encode($data),
        );

        $raw_request = $this->doCurlRequest($params, 'POST');
        $request = json_decode(substr($raw_request, strpos ($raw_request, '{')));

        if($request->success == 1) {
            return $request;
        } else {
            $respuesta = array(
                'success' => false,
                'error' => 'LEAD_UPDATE_FAILED',
                'data' => $request
            );

            return $respuesta;
        }
    }

    /**
     * Nos permite iniciar la logica de conexion inicial al CRM
     * @return string|TcrmHandler
     */
    public function connect()
    {
        $challenge = $this->challenge();

        if ($challenge === true) {
            $login = $this->login();
            if ($login === true) {
                return $this;
            } else {
                return json_encode($login);
            }
        } else {
            return json_encode($challenge);
        }
    }

    /**
     * @return boolean
     */
    public function isLogged()
    {
        return $this->logged;
    }

    /**
     * @return mixed
     */
    public function getWebService()
    {
        return $this->webService;
    }

    /**
     * @param $webService
     * @return mixed
     */
    public function setWebService($webService)
    {
        $this->webService = $webService;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     * @return mixed
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * @param $accessKey
     * @return mixed
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
        return $this;
    }
}