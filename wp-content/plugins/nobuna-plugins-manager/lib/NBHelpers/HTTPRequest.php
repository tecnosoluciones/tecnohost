<?php

namespace NBHelpers;

use Exception;

class HTTPRequest {

    /**
     * @return boolean
     */
    public static function IsCurlEnabled() {
        return in_array('curl', get_loaded_extensions());
    }

    /**
     * @return boolean
     */
    public static function IsFopenEnabled() {
        return ini_get('allow_url_fopen') === '1';
    }

    public static function IsFopenOrCURL() {
        return static::IsCurlEnabled() || static::IsFopenEnabled();
    }
    
    /**
     * @param string $url
     * @return HTTPResponse
     */
    public static function GetContentBestMethod($url) {
        if(static::IsFopenEnabled()) {
            return static::GETFileGetContents($url);
        }
        if(static::IsCurlEnabled()) {
            return static::GETCurl($url);
        }
    }
    
    public static function GetContentType($url) {
        if (!static::IsCurlEnabled() && !static::IsFopenEnabled()) {
            throw new Exception('CURL disabled and allow_url_fopen disabled');
        }
        if (static::IsFopenEnabled()) {
            return static::GetFopenContentType($url);
        }
        return static::GetCURLContentType($url);
    }

    private static function GetCURLContentType($url, $failover = TRUE) {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => TRUE,
            CURLOPT_NOBODY => TRUE,
        ));
        curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        if(curl_errno($ch)) {
            if(static::UseFailover($failover, 'fopen')) {
                return static::GetFopenContentType($url, FALSE);
            }
        }
        curl_close($ch);
        return $contentType;
    }

    private static function GetFopenContentType($url, $failover = TRUE) {
        $headers = static::GetFopenHeaders($url);
        reset($headers);
        foreach ($headers as $key => $value) {
            if (strtolower(trim(strval($key))) === 'content-type') {
                return $value;
            }
        }
        if(static::UseFailover($failover, 'curl')) {
            return static::GetCURLContentType($url, FALSE);
        }
        return FALSE;
    }

    private static function GetFopenHeaders($url) {
        $headers = @get_headers($url, 1);
        return $headers;
    }

    public static function DownloadFopen($url, $path_to_save, $failover = TRUE) {
        $handle = @fopen($url, 'rb');
        if ($handle === FALSE) {
            if(static::UseFailover($failover, 'curl')) {
                return static::DownloadCURL($url, $path_to_save, FALSE);
            }
            return sprintf('fopen - Error opening %s', $path_to_save);
        }
        $copy_result = @file_put_contents($path_to_save, $handle);
        @fclose($handle);
        if ($copy_result === FALSE) {
            if(static::UseFailover($failover, 'curl')) {
                return static::DownloadCURL($url, $path_to_save, TRUE);
            }
            return sprintf('fopen - Error writing %s', $path_to_save);
        }
        return TRUE;
    }

    /**
     * 
     * @param string $url
     * @param string $path_to_save
     * @return boolean | string if error
     */
    public static function DownloadCURL($url, $path_to_save, $failover = TRUE) {
        $fp = @fopen($path_to_save, 'wb');
        if ($fp === FALSE) {
            if(static::UseFailover($failover, 'fopen')) {
                return static::DownloadFopen($url, $path_to_save, FALSE);
            }
            return sprintf('CURL - Error opening %s', $path_to_save);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_exec($ch);

        if (curl_errno($ch)) {
            if(static::UseFailover($failover, 'fopen')) {
                return static::DownloadFopen($url, $path_to_save, FALSE);
            }
            $curl_error = curl_error($ch);
            curl_close($ch);
            return 'CURL ERROR: ' . $curl_error;
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if($statusCode != 200) {
            if(static::UseFailover($failover, 'fopen')) {
                return static::DownloadFopen($url, $path_to_save, FALSE);
            }
            return 'HTTP ERROR CODE: ' . $statusCode;
        }
        
        return TRUE;
    }

    /**
     * @param string $url
     * @return HTTPResponse
     */
    public static function GETFileGetContents($url, $failover = TRUE) {
        if (!static::IsFopenEnabled()) {
            throw new Exception('allow_url_fopen is disabled in php.ini');
        }
        $context = stream_context_create(array('http' => array('method' => 'GET', 'ignore_errors' => TRUE)));
        $raw_result = @file_get_contents($url, FALSE, $context);
        $response = HTTPResponse::FromFileGetContentsResponse($url, $raw_result, $http_response_header);
        if(!$response->isOk() && static::UseFailover($failover, 'curl')) {
            return static::GETCurl($url, FALSE);
        }
        return $response;
    }

    /**
     * @param string $url
     * @return HTTPResponse
     * @throws Exception
     */
    public static function GETCurl($url, $failover = TRUE) {
        if (!static::IsCurlEnabled()) {
            throw new Exception('curl extension is not enabled');
        }
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HEADER => 1,
        ));
        $response_curl = curl_exec($ch);
        $header_size = $response_curl ? curl_getinfo($ch, CURLINFO_HEADER_SIZE) : NULL;
        $header = $response_curl ? substr($response_curl, 0, $header_size) : NULL;
        $body = $response_curl ? substr($response_curl, $header_size) : NULL;
        curl_close($ch);
        $response = HTTPResponse::FromCurlResponse($url, $body, $header);
        if(!$response->isOk() && static::UseFailover($failover, 'fopen')) {
            return static::GETFileGetContents($url, FALSE);
        }
        return $response;
    }

    private static function UseFailover($failover, $type) {
        $use_type = $type === 'fopen' ? static::IsFopenEnabled() : static::IsCurlEnabled();
        return $failover === TRUE && $use_type === TRUE;
    }
    
}
