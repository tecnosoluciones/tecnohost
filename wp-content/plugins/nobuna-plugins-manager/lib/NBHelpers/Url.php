<?php

namespace NBHelpers;

class Url {
    
    /**
     * 
     * @param string $url
     * @return string
     */
    public static function GetSlug($url) {
        $slug = trim(parse_url($url, PHP_URL_PATH), '/');
        return $slug;
    }
    
    public static function GetURIParameters($uri) {
        $result = array();
        $query = parse_url($uri, PHP_URL_QUERY);
        if($query !== FALSE && is_string($query)) {
            $parts = explode('&', $query);
            foreach($parts as $part) {
                $keyVal = explode('=', $part);
                $key = $keyVal[0];
                $value = isset($keyVal[1]) ? urldecode($keyVal[1]) : NULL;
                if(isset($result[$key]) && !is_array($result[$key])) {
                    $result[$key] = array($result[$key]);
                }
                if(isset($result[$key]) && is_array($result[$key])) {
                    $result[$key][] = $value;
                } else {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }
    
    public static function GetURIParameter($uri, $parameter) {
        $parameters = static::GetURIParameters($uri);
        $result = isset($parameters[$parameter]) ? $parameters[$parameter] : NULL;
        return $result;
    }
    
}
