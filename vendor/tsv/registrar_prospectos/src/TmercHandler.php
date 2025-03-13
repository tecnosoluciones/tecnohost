<?php
/*************************************************************************
 * Código Fuente desarrollado y/o mejorado por Tecno-Soluciones de Venezuela C.A.
 *
 *
 * TmercHandler.php creado por: Tecnosoluciones
 * En fecha: 30/06/2016
 **************************************************************************/


namespace TSV\Component\RegistrarProspectos;

/**
 * Class TmercHandler
 * @package TSV\Component\RegistrarProspectos
 *
 * Una clase sencilla para relizar subscripciones a un TecnoMercadeo via CURL.
 * La idea es que no sea necesario poner campos "predeterminados" en el HTML del formulario,
 * sino que estén puestos mediante código en la lógica que ocurre tras la petición AJAX.
 */
class TmercHandler
{
    /**
     * @var string
     */
    protected $errors;

    /**
     * @var string
     */
    protected $response;

    /**
     * @var string url raiz de la plataforma tecnomercadeo
     */
    protected $url;

    /**
     * @var int id de la lista a subscribir
     */
    protected $list_id;

    /**
     * @var string url de la pagina de subscripcion
     */
    protected $list_url;

    /**
     * @var string nombre de la pagina de subscripcion
     */
    protected $page_name;

    /**
     * @var array parametros a ser enviados a la pagina de subscripcion
     */
    protected $queryParams = array();

    /**
     * @var bool
     */
    public $connected = false;

    protected $username;
    protected $accessKey;


    /**
     * TmercHandler constructor.
     * @param string $tmerc_url url raiz de la plataforma tecnomercadeo
     */
    public function __construct($tmerc_url)
    {
        $this->url = $tmerc_url;
        $this->setQueryParams("htmlemail", "1");
        $this->setQueryParams("autoConfirm", "1");
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

    public function setAuthData($username, $accessKey)
    {
        $this->setUsername($username);
        $this->setAccessKey($accessKey);
        return $this;
    }

    /**
     * Ejecuta una llamada a la API bajo el comando y los parámetros proporcionados.
     *
     * @param $cmd
     * @param array $params
     * @return mixed
     */
    public function apiRequest($cmd, array $params)
    {
        $url = $this->url.'/admin/?pi=restapi&page=call';
        $params['cmd'] = $cmd;

        return CurlHelper::request($url, $params, 'POST', true, sys_get_temp_dir() . '/TmercApiCookie');
    }

    /**
     * @param int $retry
     * @return bool
     */
    public function login($retry = 4)
    {
        if ($this->connected === true) {
            return true;
        }

        do {
            $response = json_decode($this->apiRequest("login", array(
                'login' => $this->getUsername(),
                'password' => $this->getAccessKey(),
            )));
            if ($response and $response->status === "success") {
                $this->connected = true;
                return true;
            }
            $retry--;
        } while ($this->connected === false || $retry > 0);
        return false;
    }

    /**
     * Funcion retro compatible para asignar los datos por defecto sin tener la api del tmerc instalada
     * @param $list_id
     * @param int $list_id id de la lista a subscribir
     * @param int $subscription_id id de la pagina de subscripcion
     * @param string $page_name nombre de la pagina de subscripcion
     */
    public function initSinApi($list_id, $subscription_id, $page_name)
    {
        $this->list_id = $list_id;
        $this->list_url = $this->url . "/?p=subscribe&id=$subscription_id";
        $this->page_name = $page_name;

        //Valores por defecto para el objeto
        $this->setQueryParams("list[$list_id]", "signup");
        $this->setQueryParams("listname[$list_id]", $page_name);
        $this->setQueryParams("VerificationCodeX", "");
        $this->setQueryParams("subscribe", " ");
    }

    /**
     * Asignar un valor al arreglo de parametros a enviar
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setQueryParams($key, $value)
    {
        $this->queryParams[$key] = $value;
        return $this;
    }

    /**
     * Obtiene un valor al arreglo de parametros a enviar
     * @return mixed
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Obtiene un valor al arreglo de parametros a enviar
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (isset($this->queryParams[$key])) {
            return $this->queryParams[$key];
        } else {
            return null;
        }
    }

    /**
     * Ayuda a asignar varios atributos a partir de un arreglo simple
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes = array())
    {
        foreach ($attributes as $attribute => $value) {
            $this->setQueryParams($attribute, $value);
        }
        return $this;
    }

    /**
     * Verifica si el envio de la solicitud al TecnoMercadeo fue exitosa o no
     * @return bool
     */
    public function wasSuccessful()
    {
        if ($this->response) {
            $a = !preg_match('/function checkform()/', $this->response);
            if ($a == true) {
                return true;
            } else {
                // http://stackoverflow.com/questions/14675452/find-whole-line-that-contains-word-with-php-regular-expressions
                $pattern = '/^.*\berror missing\b.*$/m';
                $matches = array();
                preg_match($pattern, $this->response, $matches);
                $this->errors = $matches;
                return false;
            }
        } else {
            return "Error: No se ha realizado petición CURL aún.";
        }
    }

    /**
     * Enviar datos al TecnoMercadeo
     * @return mixed
     */
    public function sendData()
    {
        if (!isset($this->queryParams['email']) || !$this->queryParams['email'] || !isset($this->queryParams['email']) || !$this->queryParams['email']) {
            throw new \BadMethodCallException('Debe asignar primero el correo a subscribir usando el metodo setEmail');
        }
        $response = CurlHelper::request($this->list_url, $this->queryParams, 'POST');
        $this->response = $response;
        return $response;
    }

    /**
     * Asignar el email para crear la persona a subscribir
     * @param $value
     * @return TmercHandler
     */
    public function setEmail($value)
    {
        $this->setQueryParams("email", $value);
        $this->setQueryParams("emailconfirm", $value);
        return $this;
    }

    /**
     * Crea un objeto dinamico con la respuesta de la api.
     * 
     * @param $response
     * @return TmercApiResponseHandler
     */
    public function apiResponseHandler($response)
    {
        $responseHandler = new TmercApiResponseHandler();
        if (!$response) {
            return $responseHandler;
        }
        $apiStatus = $response->status === "success" ? true : false;
        $responseHandler->setApiStatus($apiStatus);
        $responseHandler->setApiRaw($response);
        $responseHandler->setApiData($response->data);
        return $responseHandler;
    }

    /**
     * Método ayudante que contiene lógica general para obtener una entidad individual
     * de la API. El plugin de API de phpList tiene un comportamiento anómalo de que en
     * caso de no existir el ID entonces retorna un arreglo vacio en el campo "data", por
     * lo que las condiciones IF trabajan en base a esto.
     *
     * En caso de que la entidad exista, retorna un arreglo con sus valores.
     * En caso de no existir la ID proporcionada, retorna Null.
     * En caso de fallo de conexión, retorna False.
     *
     * @param $cmd
     * @param $key
     * @param $value
     *
     * @return TmercApiResponseHandler|void
     */
    public function findOneBy($cmd, $key, $value)
    {
        $response = json_decode($this->apiRequest($cmd, array($key => $value)));
        return $this->apiResponseHandler($response);
    }


    /**
     * Método ayudante para obtener todas las entidades en base a un comando de la API.
     *
     * En caso de éxito, retorna un arreglo con todos los datos.
     * En caso de error, retorna False.
     *
     * @param $cmd
     *
     * @return TmercApiResponseHandler|void
     */
    public function findAll($cmd)
    {
        $response = json_decode($this->apiRequest($cmd, array()));
        return $this->apiResponseHandler($response);
    }


    /**
     * Método ayudante para crear entidades en la API.
     *
     * En caso de éxito, retorna el ID de la entidad creada (útil para enviarlo a otra plataforma, por ejemplo).
     *
     * En caso de error retorna el objeto de respuesta como un arreglo.
     * En caso de fallo de conexión, retorna False.
     *
     * @param $cmd
     * @param $params
     *
     * @return TmercApiResponseHandler|void
     */
    public function createEntity($cmd, $params)
    {
        $response = json_decode($this->apiRequest($cmd, $params));
        return $this->apiResponseHandler($response);
    }


    /**
     * Método ayudante para eliminar una entidad individual.
     *
     * @param $cmd
     * @param $id
     *
     * @return TmercApiResponseHandler|void
     */
    public function deleteEntity($cmd, $id)
    {
        $response = json_decode($this->apiRequest($cmd, array('id' => $id)));
        return $this->apiResponseHandler($response);
    }

    /**
     * Obtiene las listas del phpList.
     * 
     */
    public function listsGet()
    {
        return $this->findAll("listsGet");
    }


    /**
     * Obtiene una lista individual por ID.
     *
     * @param $id
     *
     * @return TmercApiResponseHandler|void
     */
    public function listGet($id)
    {
        return $this->findOneBy("listGet", 'id', $id);
    }


    /**
     * Agrega una lista nueva al phpList.
     *
     * Retorna un arreglo tipo "{"status":"success","type":"List","data":{"id":"11","name":"Mi nueva lista",
     * "description":"Descripcion","entered":null,"listorder":null,"prefix":null,"rssfeed":null,
     * "modified":"2016-04-08 15:09:06","active":"1","owner":null,"category":""}}"
     *
     * (ya no, retorna solo la id...)
     *
     * @param $name
     * @param string $description
     * @param null $listorder
     * @param int $active
     *
     * @return TmercApiResponseHandler|void
     */
    public function listAdd($name, $description = "", $listorder = null, $active = 1)
    {
        $params = array(
            'name' => $name,
            'description' => $description,
            'active' => $active
        );

        if ($listorder) {
            $params['listorder'] = $listorder;
        }

        return $this->createEntity("listAdd", $params);
    }


    /**
     * Elimina la lista con la ID proporcionada.
     *
     * @param $id
     *
     * @return TmercApiResponseHandler|void
     */
    public function listDelete($id)
    {
        return $this->deleteEntity('listDelete', $id);
    }

    /**
     * Obtiene las listas a las que está subscrita la ID proporcionada.
     *
     * Campos de los sub-arreglos: id, name, description, entered, listorder, prefix,
     * rssfeed, modified, active, owner, category.
     *
     * @param $subscriber_id
     *
     * @return TmercApiResponseHandler|void
     */
    public function listsSubscriber($subscriber_id)
    {
        $params = array(
            'subscriber_id' => $subscriber_id
        );

        $response = json_decode($this->apiRequest("listsSubscriber", $params));
        return $this->apiResponseHandler($response);
    }


    /**
     * Agrega el subscriptor con $subscriber_id a la lista con $list_id
     *
     * En caso de error el retorno response es
     *
     * object(stdClass)#316 (3) {
     * ["status"]=> string(5) "error"
     * ["type"]=> string(5) "Error"
     * ["data"]=> object(stdClass)#59 (2) {
     * ["code"] => string(5) "23000"
     * ["message"] => string(92) "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '49878368-4' for key 1"
     * }
     * }
     *
     * @param $list_id
     * @param $subscriber_id
     *
     * @return TmercApiResponseHandler|void
     */
    public function listSubscriberAdd($list_id, $subscriber_id)
    {
        $params = array(
            'list_id' => $list_id,
            'subscriber_id' => $subscriber_id
        );

        $response = json_decode($this->apiRequest('listSubscriberAdd', $params));
        return $this->apiResponseHandler($response);
    }

    /**
     * Obtiene un subscriptor por su ID.
     *
     * Entre los campos del arreglo retornado en caso de éxito están:
     * id, email, confirmed, blacklisted, optedin, bounceout, entered, modified,
     * htmlemail, subscribepage, passwordchanged, disabled.
     *
     * @param $id
     * @return array|bool|null
     */
    public function subscriberGet($id)
    {
        return $this->findOneBy("subscriberGet", 'id', $id);
    }


    /**
     * Obtiene un subscriptor por su direccion de correo.
     *
     * @param $email
     *
     * @return TmercApiResponseHandler|void
     */
    public function subscriberGetByEmail($email)
    {
        return $this->findOneBy("subscriberGetByEmail", 'email', $email);
    }


    /**
     * Crea un nuevo subscriptor en el phpList. Los tipos de retorno dependen del
     * resultado de la llamada a la API.
     *
     * 1) En caso de éxito, retorna el ID del subscriptor creado.
     *
     * 2) En caso de error, el método retorna un arreglo con la informacion relacionada con
     *    el error ocurrido. El arreglo retornado puede ser, por ejemplo:
     *
     *     array(2) { ["code"]=> string(5) "23000" ["message"]=> string(108)
     *     "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry
     *     'jreyna@tecnosoluciones.com' for key 2" }
     *
     *    en caso de que el email ya exista en el phpList.
     *
     * 3) En caso de fallo de conexión, retorna False.
     *
     * @param $email
     * @param $password
     * @param int $confirmed
     * @param int $htmlemail
     * @param string $foreignkey
     * @param null $subscribepage
     * @param int $disabled
     *
     * @return TmercApiResponseHandler|void
     */
    public function subscriberAdd(
        $email,
        $password,
        $confirmed = 1,
        $htmlemail = 1,
        $foreignkey = "",
        $subscribepage = null,
        $disabled = 0
    ) {

        $params = array(
            'email' => $email,
            'password' => $password,
            'confirmed' => $confirmed,
            'htmlemail' => $htmlemail,
            'foreignkey' => $foreignkey,
            'subscribepage' => $subscribepage,
            'disabled' => $disabled
        );

        return $this->createEntity("subscriberAdd", $params);
    }


    /**
     * Elimina el subscriptor con la ID proporcionada.
     *
     * @param $id
     * @return bool
     */
    public function subscriberDelete($id)
    {
        return $this->deleteEntity("subscriberDelete", $id);
    }
}