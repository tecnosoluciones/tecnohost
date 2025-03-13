<?php
/*************************************************************************
 * Código Fuente desarrollado y/o mejorado por Tecno-Soluciones de Venezuela C.A.
 *
 *
 * TecnoCrmApiTest.php creado por: Tecnosoluciones
 * En fecha: 30/06/2016
 **************************************************************************/


namespace TSV\Component\RegistrarProspectos\Tests;


use TSV\Component\RegistrarProspectos\TcrmHandler;

class TecnoCrmApiTest extends \PHPUnit_Framework_TestCase
{
    protected $gallengeResponse;

    /**
     * @return string
     */
    protected static function jsonCallangeData()
    {
        $currentTime = time();
        $jsonData = array(
            'success' => true,
            'result' => array(
                'token' => '5776a6f8329c2',
                'serverTime' => $currentTime,
                'expireTime' => $currentTime + 3600,
            ),
        );
        return json_encode($jsonData);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function tecnoCrmApiMock()
    {
        $mock = $this->getMockBuilder('TSV\Component\RegistrarProspectos\TecnoCrmApi')
            ->setConstructorArgs(array('url'))
            ->setMethods(array('doCurlRequest'))
            ->getMock();
        return $mock;
    }

    protected function setChallengeResponse()
    {
        $currentTime = time();
        $gallengeData = array(
            'success' => true,
            'result' => array(
                'token' => uniqid(),
                'serverTime' => $currentTime,
                'expireTime' => $currentTime + 3600,
            ),
        );
        return json_encode($gallengeData);
    }

    public function testConstruct()
    {
        $obj = new TcrmHandler('url');
        $this->assertInstanceOf('TSV\Component\RegistrarProspectos\TecnoCrmApi', $obj);
        $this->assertEquals('url', $obj->getWebService());
    }

    public function testSetAuthData()
    {
        $obj = new TcrmHandler('url');
        $obj->setAuthData('username', 'accessKey');
        $this->assertEquals('accessKey', $obj->getAccessKey());
        $this->assertEquals('username', $obj->getUsername());
    }

    /**
     * Login sin autentificacion (perdir el token previamente), debe emitir una excepcion.
     * 
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage No se ha establecido el challenge_token.
     */
    public function testExceptionLogin()
    {
        $crmApi = new TcrmHandler('url');
        $response = $crmApi->login();
    }

    /**
     * Obtener el challenge token
     */
    public function testChallenge()
    {
        $mock = $this->tecnoCrmApiMock();
        $mock->method('doCurlRequest')->willReturn(self::jsonCallangeData());
        $response = $mock->challenge();
        $this->assertTrue($response);

    }

    /**
     * Probar un login que debe ser valido
     */
    public function testLoginSuccess()
    {
        $jsonData = array(
            'success' => true,
            'result' => array(
                'sessionId' => '123',
                'userId' => '1',
                'version' => '1',
                'vtigerVersion' => '1.0.0.0',
            ),
        );

        $mock = $this->tecnoCrmApiMock();
        $mock->method('doCurlRequest')->will($this->onConsecutiveCalls(self::jsonCallangeData(),
            json_encode($jsonData)));
        $mock->challenge();
        $response = $mock->login();
        $this->assertTrue($response);
    }
}
