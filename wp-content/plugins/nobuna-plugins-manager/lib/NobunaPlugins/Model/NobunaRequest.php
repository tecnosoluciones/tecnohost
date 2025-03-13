<?php

namespace NobunaPlugins\Model;

use NobunaPlugins\Exceptions\NobunaError;
use WPHelpers\Package;
use NBHelpers\HTTPRequest;
use NBHelpers\HTTPResponse;

class NobunaRequest {

    const REQUEST_METHOD_FOPEN = 'fopen';
    const REQUEST_METHOD_CURL = 'curl';

    public $url;
    public $common_params;
    public $base_params;
    private $request_method = NULL;

    /**
     * @var NobunaError
     */
    public $error = NULL;
    public $warnings = array();

    /**
     * @var HTTPResponse
     */
    public $response = NULL;
    public $request_result = NULL;
    protected static $plugin_info;

    // <editor-fold defaultstate="collapsed" desc="User settings and url methods">
    private static function GetHost() {
        if (!is_array($_SERVER) || !isset($_SERVER['HTTP_HOST'])) {
            throw new Exception('What host?');
        }
        $res = strtolower(trim($_SERVER['HTTP_HOST']));
        return $res;
    }

    private static function GetProtocol($force_protocol = NULL) {
        if ($force_protocol !== NULL) {
            return $force_protocol;
        }
        return NobunaSettings::Shared()->requests_protocol;
    }

    private static function IsHTTPS() {
        return static::GetProtocol() === NOBUNA_PROTOCOL_HTTPS;
    }

    private static function SetHTTP() {
        NobunaSettings::Shared()->setRequestsProtocol(NOBUNA_PROTOCOL_HTTP);
    }

    private static function GetUserKey() { 
        return NobunaSettings::Shared()->key;
    }

    private static function GetUserSecret() {
        return NobunaSettings::Shared()->secret;
    }

    private static function GetPluginInfo() {
        if (static::$plugin_info === NULL) {
            static::$plugin_info = Package::GetPHPFileInfo(NOBUNA_PLUGINS_MAIN_FILE);
        }
        return static::$plugin_info;
    }

    private static function GetPluginVersion() {
        $plugin_info = static::GetPluginInfo();
        return $plugin_info['Version'];
    }

    private static function CreateParsedUrl($url, $params, $protocol) {
        return sprintf('%s://%s?%s', $protocol, $url, http_build_query($params));
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Constructor and check curl and fopen">
    public function __construct($url, $params = array()) {
        NobunaSettings::Shared();
        $this->url = $url;
        $this->base_params = $params;
        $this->common_params = array(
            'hl' => get_locale(),
            'v' => static::GetPluginVersion(),
            'd' => static::GetHost(),
        );
        static::CheckRequestMethods();
    }

    private function CheckRequestMethods() {
        $this->request_method = static::EnabledMethod();
        if($this->request_method === NULL) {
            $this->setError(new NobunaError(NobunaError::REQUEST_ERROR_CODE_FOPEN_CURL, 
                    __nb('allow_url_fopen is disabled in server, and curl extension is not enabled. Please '
                            . 'install curl extension or set allow_url_fopen=1 in php.ini file.')));
        }
    }

    public static function EnabledMethod() {
        $curl_enabled = HTTPRequest::IsCurlEnabled();
        $fopen_enabled = HTTPRequest::IsFopenEnabled();
        if ($curl_enabled === FALSE && $fopen_enabled === FALSE) {
            return NULL;
        } else {
            return $fopen_enabled ? static::REQUEST_METHOD_FOPEN : static::REQUEST_METHOD_CURL;
        }
    }
    
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Errors and warnings">
    public function isError() {
        return is_wp_error($this->error);
    }

    private function setError(NobunaError $error) {
        $this->error = $error;
        NobunaGlobals::SetGlobalError($error);
    }

    private function setWarning($warning_msg) {
        $this->warnings[] = $warning_msg;
        NobunaGlobals::SetGlobalWarning($warning_msg);
    }

    private function activateHTTP() {
        $this->setWarning(__nb('The server is not able to use HTTPS '
                        . 'protocol. HTTPS requests had been deactivated. You '
                        . 'can check your settings in Nobuna Plugins > Settings'));
        static::SetHTTP();
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Requests">
    /**
     * @param type $url
     * @return NBHelpers\HTTPRequest
     */
    private function getResponse($url) {
        switch ($this->request_method) {
            case static::REQUEST_METHOD_FOPEN:
                return HTTPRequest::GETFileGetContents($url);
            case static::REQUEST_METHOD_CURL:
                return HTTPRequest::GETCurl($url);
        }
    }

    /**
     * @param string $url
     * @param string $to
     * @return boolean | string if error
     */
    private function download($url, $to) {
        switch ($this->request_method) {
            case static::REQUEST_METHOD_FOPEN:
                return HTTPRequest::DownloadFopen($url, $to);
            case static::REQUEST_METHOD_CURL:
                return HTTPRequest::DownloadCURL($url, $to);
        }
    }

    private function processServerResponse(HTTPResponse $response) {
        if ($response->isConnectionError()) {
            $code = NobunaError::REQUEST_EC_FAILED_CONNECTION;
            $message = $this->s('ErrorConnecting');
            $error = new NobunaError($code, $message);
            $this->setError($error);
            return;
        }

        if ($response->isServerError()) {
            $code = NobunaError::REQUEST_EC_FAILED_SERVER;
            $message = sprintf($this->s('ServerError'), $response->code);
            $error = new NobunaError($code, $message);
            $this->setError($error);
            return;
        }


        $jdata = $response->jsonContent();
        if (!is_array($jdata) || !isset($jdata['status'])) {
            $code = NobunaError::REQUEST_EC_FAILED_JSON_RESPONSE;
            $message = sprintf($this->s('ServerErrorJson'), $response->code);
            $error = new NobunaError($code, $message);
            $this->setError($error);
            return;
        }

        if ($jdata['status'] !== 200) {
            $code = NobunaError::REQUEST_ERROR_CODE_API_ERROR;
            $message = sprintf($this->s('ApiError'), $jdata['status']);
            $error = new NobunaError($code, $message, $jdata);
            $this->setError($error);
            return;
        }

        $this->request_result = $jdata;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Download">
    public function downloadTo($path_to_save) {
        if($this->isError()) {
            return;
        }
        try {
            $this->_downloadTo($path_to_save);
        } catch (\Exception $ex) {
            if (is_nobuna_debug() && !$this->isError()) {
                $code = NobunaError::REQUEST_ERROR_CODE_UNKNOWN;
                $message = $this->s('UnknownError');
                $this->setError(new NobunaError($code, $message, $ex));
            }
        }
    }

    private function _downloadTo($path_to_save) {
        set_time_limit(300);
        // Get params
        $params = $this->prepareParams();
        if ($params === NULL) {
            return;
        }

        // check content type
        $parsed_url = static::CreateParsedUrl($this->url, $params, static::GetProtocol());
        $contentType = HTTPRequest::GetContentType($parsed_url);
        if ($contentType !== 'application/octet-stream') {
            $this->execute();
            return;
        }
        
        // Refresh params
        $params = $this->prepareParams();
        if ($params === NULL) {
            return;
        }

        // try to download
        if (static::IsHTTPS()) {
            $https_url = static::CreateParsedUrl($this->url, $params, static::GetProtocol());
            $result = $this->download($https_url, $path_to_save);
            if ($result !== TRUE) {
                // try http
                $http_url = static::CreateParsedUrl($this->url, $params, NOBUNA_PROTOCOL_HTTP);
                $result = $this->download($http_url, $path_to_save);
                if ($result === TRUE) {
                    $this->activateHTTP();
                }
            }
        } else {
            $http_url = static::CreateParsedUrl($this->url, $params, NOBUNA_PROTOCOL_HTTP);
            $result = $this->download($http_url, $path_to_save);
        }
        
        // check errors
        if($result !== TRUE) {
            $code = NobunaError::REQUEST_EC_FAILED_DOWNLOAD;
            $message = $result;
            $error = new NobunaError($code, $message);
            $this->setError($error);
        }
        
    }
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Execute">
    public function execute() {
        if($this->isError()) {
            return;
        }
        try {
            $this->_execute();
        } catch (\Exception $ex) {
            if (is_nobuna_debug() && !$this->isError()) {
                $code = NobunaError::REQUEST_ERROR_CODE_UNKNOWN;
                $message = $this->s('UnknownError');
                $this->setError(new NobunaError($code, $message, $ex));
            }
        }
    }

    private function _execute() {
        $params = $this->prepareParams();
        if ($this->isError()) {
            return;
        }
        if (static::IsHTTPS()) {
            // try https request
            $https_url = static::CreateParsedUrl($this->url, $params, static::GetProtocol());
            $response = static::getResponse($https_url);
            if ($response->isConnectionError()) {
                // try http and if not error modify https => http and set warning
                $http_url = static::CreateParsedUrl($this->url, $params, NOBUNA_PROTOCOL_HTTP);
                $response = static::getResponse($http_url);
                if (!$response->isConnectionError()) {
                    $this->activateHTTP();
                }
            }
        } else {
            // try http request
            $http_url = static::CreateParsedUrl($this->url, $params, NOBUNA_PROTOCOL_HTTP);
            $response = static::getResponse($http_url);
        }

        $this->processServerResponse($response);
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="URL parameters and security parameters">
    private function prepareParams() {
        $security_params = $this->getSecurityParams();
        if ($security_params === NULL) {
            return NULL;
        }
        $params = array_merge($this->common_params, $this->base_params, $security_params);
        return $params;
    }

    private function getSecurityParams() {
        $params = NULL;
        if (static::IsHTTPS()) {
            $params = array(
                'ck' => static::GetUserKey(),
                'cs' => static::GetUserSecret(),
            );
        } else {
            $nonce = $this->getNonceCode();
            if ($nonce !== NULL) {
                $params = array(
                    'ck' => static::GetUserKey(),
                    'nonce' => $nonce,
                );
            }
        }
        return $params;
    }

    private function getNonceCode() {
        $params = array_merge(array('ck' => static::GetUserKey()), $this->common_params);
        $url = static::CreateParsedUrl(NOBUNA_GET_NONCE_URL, $params, NOBUNA_PROTOCOL_HTTP);
        $response = $this->getResponse($url);
        $result = NULL;
        if ($response->isOk()) {
            $jdata = $response->jsonContent();
            if (!is_array($jdata) || !isset($jdata['nonce'])) {
                $this->setError(new NobunaError(NobunaError::REQUEST_ERROR_CODE_GET_NONCE, $this->s('ErrorConnecting'), $jdata));
            } else {
                $result = md5(static::GetUserSecret() . trim($jdata['nonce']));
            }
        } else {
            $this->processServerResponse($response);
        }
        return $result;
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Strings methods">
    private function s($key) {
        $s = $this->getStrings();
        if (!isset($s[$key])) {
            return $key;
        }
        return $s[$key];
    }

    private $_strings;

    private function getStrings() {
        if ($this->_strings === NULL) {
            $this->_strings = array(
                'ErrorConnecting' => __nb('Error Connecting Nobuna Server'),
                'ServerError' => __nb('Server error: %s'),
                'ServerErrorJson' => __nb('Server response error'),
                'ApiError' => __nb('Error code: %s'),
                'UnknownError' => __nb('Unknown error NR'),
                'ErrorFileCopy' => __nb('Was not possible to create the file: %s'),
            );
        }
        return $this->_strings;
    }

    // </editor-fold>
}
