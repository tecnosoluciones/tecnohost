<?php

namespace NBHelpers;

class Str {

    private $string = NULL;

    public static function RemoveAtEnd($str, $to_remove) {
        $s = new Str($str);
        $res = $str;
        if ($s->endsWith($to_remove) || $str == $to_remove) {
            $res = substr($str, 0, strlen($str) - strlen($to_remove));
        }
        return $res;
    }

    public static function CreateSlug($str) {
        $mod = preg_replace('/[\s-]+/i', '_', $str);
        $mod = preg_replace('/[^\da-z_]/i', '', $mod);
        return strtolower($mod);
    }
    
    /**
     * 
     * @param int $min_length
     * @param int $max_length
     * @return string
     */
    public static function GetRandomCharacters($min_length, $max_length) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $rand_string = '';
        $length = rand($min_length, $max_length);
        for ($count = 0; $count < $length; $count++) {
            $rand_string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $rand_string;
    }
    
    /**
     * 
     * @param string $str
     */
    public function __construct($str) {
        $this->string = $str;
    }

    public function iContains($needle) {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        reset($needle);
        foreach ($needle as $item) {
            if ($this->_iContains(strval($item))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 
     * @param string $str
     * @return bool
     */
    private function _iContains($str) {
        if (stripos($this->string, $str) !== FALSE) {
            return TRUE;
        }
        return FALSE;
    }

    public function iTrimIsEqualTo($needle) {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        reset($needle);
        foreach ($needle as $item) {
            if ($this->_startsWith(strval($item))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 
     * @param string $str
     * @return bool
     */
    private function _iTrimIsEqualTo($str) {
        return trim(strtolower($this->string)) === trim(strtolower($str));
    }

    /**
     * @param string|array<string> $needle
     * @return boolean
     */
    public function startsWith($needle) {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        reset($needle);
        foreach ($needle as $item) {
            if ($this->_startsWith(strval($item))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 
     * @param string $needle
     * @return bool
     */
    private function _startsWith($needle) {
        $length = strlen($needle);
        return (substr($this->string, 0, $length) === $needle);
    }

    /**
     * @param string|array<string> $needle
     * @return boolean
     */
    public function endsWith($needle) {
        if (!is_array($needle)) {
            $needle = array($needle);
        }
        reset($needle);
        foreach ($needle as $item) {
            if ($this->_endsWith(strval($item))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    private function _endsWith($needle) {
        $length = strlen($needle);
        return $length === 0 || (substr($this->string, -$length) === $needle);
    }

}
