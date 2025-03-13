<?php
/*************************************************************************
 * Código Fuente desarrollado y/o mejorado por Tecno-Soluciones de Venezuela C.A.
 *
 *
 * TmercApiResponseHandler.php creado por: Tecnosoluciones
 * En fecha: 07/07/2016
 **************************************************************************/


namespace TSV\Component\RegistrarProspectos;

/**
 * Class TmercApiResponseHandler
 * @package TSV\Component\RegistrarProspectos
 */
class TmercApiResponseHandler
{
    protected $apiStatus = false;
    protected $apiData;
    protected $apiRaw;

    /**
     * @return mixed
     */
    public function getApiStatus()
    {
        return $this->apiStatus;
    }

    /**
     * @param mixed $apiStatus
     * @return $this
     */
    public function setApiStatus($apiStatus)
    {
        $this->apiStatus = $apiStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiData()
    {
        return $this->apiData;
    }

    /**
     * @param mixed $apiData
     * @return $this
     */
    public function setApiData($apiData)
    {
        $this->apiData = $apiData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiRaw()
    {
        return $this->apiRaw;
    }

    /**
     * @param mixed $apiRaw
     * @return $this
     */
    public function setApiRaw($apiRaw)
    {
        $this->apiRaw = $apiRaw;
        return $this;
    }

    /**
     * Verifica el estado de la respuesta de la API.
     * @return bool
     */
    public function apiHadResponse()
    {
        return $this->getApiStatus();
    }


    /**
     * Verifica el contendo de data. La API acostumbra devolver un arreglo vacío cuando no consigue nada.
     * Por el contrario, entrega un objeto stdClass cuando es único, o un arreglo cuando son varios objetos.
     *
     * @return bool
     */
    public function apiResponseHasData()
    {
        return is_object($this->getApiData()) or count($this->getApiData()) > 0 ? true : false;
    }


    /**
     * La respuesta está bien.
     * @return bool
     */
    public function allOk()
    {
        return $this->apiHadResponse() and $this->apiResponseHasData() ? true : false;
    }
}