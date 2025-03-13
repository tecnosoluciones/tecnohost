<?php
/************************************************************************* *
 * Código Fuente desarrollado y/o mejorado por Tecno-Soluciones de Venezuela C.A.
 *
 *
 * CurlMock.php creado por: Tecnosoluciones
 * En fecha: 01/07/2016
 **************************************************************************/


namespace TSV\Component\RegistrarProspectos\Tests;

/**
 * class to abstract the usage of the cURL functions.
 *
 * Class CurlMock
 * @package TSV\Component\RegistrarProspectos\Tests
 */
class CurlMock
{
    private $handle = null;

    public function __construct($url)
    {
        $this->handle = curl_init($url);
    }

    public function setOption($name, $value)
    {
        curl_setopt($this->handle, $name, $value);
    }

    public function execute()
    {
        return curl_exec($this->handle);
    }

    public function getInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    public function close()
    {
        curl_close($this->handle);
    }
}