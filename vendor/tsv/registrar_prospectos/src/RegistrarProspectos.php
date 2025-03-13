<?php
/*************************************************************************
 * Código Fuente desarrollado y/o mejorado por Tecno-Soluciones de Venezuela C.A.
 *
 *
 * RegistrarProspectos.php creado por: Tecnosoluciones
 * En fecha: 30/06/2016
 **************************************************************************/


namespace TSV\Component\RegistrarProspectos;

/**
 * Class RegistrarProspectos
 * @package TSV\Component\RegistrarProspectos
 */
class RegistrarProspectos
{
    /**
     * @var array de datos a ser enviados
     */
    public $queryString = array();

    /**
     * @var TcrmHandler
     */
    protected $tcrm;

    /**
     * @var TmercHandler
     */
    protected $tmerc;

    /**
     * Iniciar los objetos abstractos de las api por plataforma.
     *
     * @param array $plataformas
     */
    public function __construct(array $plataformas)
    {
        if (isset($plataformas['TecnoCRM']) && $plataformas['TecnoCRM']) {
            $tcrm = $plataformas['TecnoCRM'];

            $this->tcrm = new TcrmHandler($tcrm['webservice_url']);
            if (isset($tcrm['username']) && $tcrm['username'] && isset($tcrm['access_key']) && $tcrm['access_key']) {
                $this->tcrm->setAuthData($tcrm['username'], $tcrm['access_key']);
            }
        }

        if (isset($plataformas['TecnoMercadeo']) && $plataformas['TecnoMercadeo']) {
            $tmerc = $plataformas['TecnoMercadeo'];
            $this->tmerc = new TmercHandler($tmerc['tmerc_url']);
            if (isset($tmerc['username']) && $tmerc['username'] && isset($tmerc['access_key']) && $tmerc['access_key']) {
                $this->tmerc->setAuthData($tmerc['username'], $tmerc['access_key']);
            }
        }
    }

    /**
     * Verifica si existe el nombre de plataformas y la clase que usada para la conexion
     * @param string $propiedad
     * @param string $clase
     */
    public function verificarPlataforma($propiedad, $clase)
    {
        $plataforma = $this->{$propiedad};
        if (!isset($plataforma) || !$plataforma instanceof $clase) {
            throw new \BadMethodCallException('La propiedad '.$propiedad.' no fue iniciada correctamente, debe primero configurar los parametros de la plataforma al iniciar la clase');
        }
    }

    /**
     * Logica de verificacion en la plataforma a usar. Sin este metodo el throw de @verificarPlataforma no es mostrado.
     *
     * @param $propiedad
     * @param $clase
     */
    protected function tryConfig($propiedad, $clase)
    {
        try {
            $this->verificarPlataforma($propiedad, $clase);
        } catch (\BadMethodCallException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Arma un arreglo de datos a ser enviados
     * @param array $campos
     * @return $this
     */
    public function setQueryString(array $campos)
    {
        $this->queryString[] = $campos;
        return $this;
    }

    /**
     * Asignar nuevos datos de autentificacion al crm
     * @param $username
     * @param $access_key
     * @return $this
     */
    public function setCrmAuthData($username, $access_key)
    {
        $this->tryConfig('tcrm', 'TSV\Component\RegistrarProspectos\TcrmHandler');
        $this->tcrm->setAuthData($username, $access_key);
        return $this;
    }

    /**
     * Registrar un prospecto en el TecnoCRM a través de su api
     *
     * @param array $arguments
     * @return array|bool|string|TcrmHandler
     */
    public function registrarProspectoCRM(array $arguments)
    {
        $this->tryConfig('tcrm', 'TSV\Component\RegistrarProspectos\TcrmHandler');
        $response = $this->tcrm->connect();
        if ($response instanceof TcrmHandler) {
            $response = $response->leadRequest($arguments, array('email' => $arguments['email']));
        }
        return $response;
    }

    public function get_data($id_elemento)
    {
        $this->tryConfig('tcrm', 'TSV\Component\RegistrarProspectos\TcrmHandler');
        $response = $this->tcrm->connect();
        if ($response instanceof TcrmHandler) {
            $response = $response->retrieve($id_elemento);
        }
        return $response;
    }

    public function describir_modulo($modulename)
    {
        $this->tryConfig('tcrm', 'TSV\Component\RegistrarProspectos\TcrmHandler');
        $response = $this->tcrm->connect();
        if ($response instanceof TcrmHandler) {
            $response = $response->describeOperation($modulename);
        }
        return $response;
    }

    /**
     * Actualizar un Prospecto.
     * @param $data
     * @return bool
     */

    public function actua_lead($data) {
        $this->tryConfig('tcrm', 'TSV\Component\RegistrarProspectos\TcrmHandler');
        $response = $this->tcrm->connect();
        if ($response instanceof TcrmHandler) {
            $response = $response->update('Leads', $data);
        }
        return $response;
    }

    /**
     * Actualizar un Contacto.
     * @param $data
     * @return bool
     */
    public function actua_contact($data) {
        $this->tryConfig('tcrm', 'TSV\Component\RegistrarProspectos\TcrmHandler');
        $response = $this->tcrm->connect();
        if ($response instanceof TcrmHandler) {
            $response = $response->update('Contacts', $data);
        }
        return $response;
    }

    /**
     * Registra un correo en el tecnomercadeo a traves del api rest.
     * Esta rutina no permite registrar atributos, en caso de ser necesarios usar el metodo 'registrarProspectoTmercLegacy'
     *
     * @param array $arguments se pueden enviar los indices password, foreignkey y subscribepage para reescribir los valores por defecto.
     * @return null|TmercApiResponseHandler|void
     */
    public function registrarProspectoTmercApi(array $arguments)
    {
        $this->tryConfig('tmerc', 'TSV\Component\RegistrarProspectos\TmercHandler');
        $this->tmerc->login();
        $subscriber = $this->tmerc->subscriberGetByEmail($arguments['email']);

        $user_id = $response = null;
        $defaultData = array(
            'password' => ($arguments['password']) ? $arguments['password'] : bin2hex(openssl_random_pseudo_bytes(14)),
            'autoConfirm' => 1,
            'htmlemail' => 1,
            'foreignkey' => ($arguments['foreignkey']) ? $arguments['foreignkey'] : '',
            'subscribepage' => ($arguments['subscribepage']) ? $arguments['subscribepage'] : null,
            'disabled' => 0
        );
        $registerData = array_merge($arguments, $defaultData);
        if ($subscriber->apiHadResponse() and !$subscriber->apiResponseHasData()) {
            // si la petición de usuario funcionó, pero no hay usuario
            $newSubscriber = $this->tmerc->subscriberAdd($arguments['email'], $registerData);
            if ($newSubscriber->allOk()) {
                // si la petición de agregar funcionó, usar el ID del subscriptor creado
                $user_id = $newSubscriber->getApiData()->id;
                // establecer datos del usuario...
                // TODO: agregar esta opcion al módulo de API si es necesario ya que no lo permite.
            }
        } elseif ($subscriber->allOk()) {
            // si hay un usuario existente en el phpList, utilizar su ID
            $user_id = $subscriber->getApiData()->id;
        }

        $lista = $this->tmerc->listGet($arguments['listId']);
        // si estamos bien y tenemos las variables necesarias, hacer las subscripciones
        if ($lista->allOk() && $user_id) {
            $response = $this->tmerc->listSubscriberAdd($lista->getApiData()->id, $user_id);
        }
        return $response;
    }

    /**
     * @deprecated recomendado usar el metodo 'registrarProspectoTmercApi'
     *
     * Metodo usado antes del plugin REST del tecnomercadeo.
     *
     * @param array $arguments se pueden enviar los indices list_id, subscription_id (id de la pagina de subscripcion) y page_name.
     * @return mixed
     */
    public function registrarProspectoTmercLegacy(array $arguments)
    {
        $this->tryConfig('tmerc', 'TSV\Component\RegistrarProspectos\TmercHandler');
        $this->tmerc->initSinApi($arguments['list_id'], $arguments['subscription_id'], $arguments['page_name']);
        unset($arguments['list_id'], $arguments['subscription_id'], $arguments['page_name']);
        $this->tmerc->setAttributes($arguments);
        $response = $this->tmerc->sendData();
        return $response;
    }

    /**
     * Retorna el handler del TecnoCRM si hace falta usarlo.
     *
     * @return TcrmHandler
     */
    public function getTcrm()
    {
        return $this->tcrm;
    }

    /**
     * Retorna el handler del TecnoMercadeo si hace falta usarlo.
     *
     * @return TmercHandler
     */
    public function getTmerc()
    {
        return $this->tmerc;
    }

}