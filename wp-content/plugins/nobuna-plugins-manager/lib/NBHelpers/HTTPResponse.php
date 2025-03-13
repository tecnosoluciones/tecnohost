<?php

namespace NBHelpers;

use Exception;

class HTTPResponse {

    public $url;
    public $code;
    public $headers;
    public $raw_content;

    public static function FromCurlResponse($url, $body, $header) {
        $r = new HTTPResponse;
        $r->url = $url;
        $r->raw_content = $body;
        if (is_string($header)) {
            $header = explode("\n", $header);
        }
        $r->headers = static::ParseHeadersFromArray($header);
        $r->code = static::CodeFromHeadersArray($header);
        return $r;
    }

    /**
     * @param mixed $raw_result string | FALSE
     * @param mixed $request_headers array | NULL
     * @return \NBHelpers\HTTPResponse
     */
    public static function FromFileGetContentsResponse($url, $raw_result, $request_headers) {
        $r = new HTTPResponse;
        if (is_string($raw_result)) {
            $r->raw_content = $raw_result;
        }
        $r->url = $url;
        $r->headers = static::ParseHeadersFromArray($request_headers);
        $r->code = static::CodeFromHeadersArray($request_headers);
        return $r;
    }

    /**
     * @param mixed $headers only works if is_array($headers) === TRUE
     * @return array
     */
    private static function ParseHeadersFromArray($headers) {
        $head = array();
        if (is_array($headers)) {
            foreach ($headers as $k => $v) {
                $t = explode(':', $v, 2);
                if (isset($t[1])) {
                    $head[trim($t[0])] = trim($t[1]);
                } else if (strlen(trim($v)) > 0) {
                    $head[] = trim($v);
                }
            }
        }
        return $head;
    }

    /**
     * @param mixed $headers only works if is_array($headers) === TRUE
     * @return array
     */
    private static function CodeFromHeadersArray($headers) {
        if (is_array($headers)) {
            foreach ($headers as $k => $v) {
                $t = explode(':', $v, 2);
                if (!isset($t[1])) {
                    if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out)) {
                        return intval($out[1]);
                    }
                }
            }
        }
        return NULL;
    }
    
    public function isConnectionError() {
        return $this->code === NULL;
    }
    
    public function isServerError() {
        return $this->code !== 200;
    }
    
    public function isOk() {
        return $this->code === 200;
    }
    
    public function isHttps() {
        return strtolower(substr($this->url, 0, 5)) === 'https';
    }
    
    public function jsonContent($assoc = TRUE) {
        if(!is_string($this->raw_content)) {
            throw new Exception('Raw content is empty');
        }
        $result = @json_decode($this->raw_content, $assoc);
        return $result;
    }
    
    public function stringResults() {
        $out = '';
        if($this->isConnectionError()) {
            $out .= __nb('Connection error') . PHP_EOL;
        } else {
            $out .= __nb('Request to: %s', $this->url) . PHP_EOL;
            $out .= __nb('Response code: %d', $this->code) . PHP_EOL;
            $out .= __nb('Content: %s', $this->raw_content) . PHP_EOL;
            $out .= var_export($this->headers, true) . PHP_EOL;
        }
        return $out;
    }

}
