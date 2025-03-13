<?php

namespace NBHelpers;

use DOMDocument;
use DOMElement;

class HTML {

    /**
     * 
     * @param string $str
     * @param string $tagName
     * @return DOMElement or NULL
     */
    public static function CreateElementFromHTML($str, $tagName) {
        $d = new DOMDocument();
        $ie = libxml_use_internal_errors(TRUE);
        $d->loadHTML($str);
        libxml_use_internal_errors($ie);
        $e = $d->getElementsByTagName($tagName);
        return $e->item(0);
    }

    /**
     * @param string $atag
     * @return string
     */
    public static function GetAHref($atag) {
        $res = '';
        $link = static::CreateElementFromHTML($atag, 'a');
        if($link !== NULL) {
            $res = trim($link->getAttribute('href'));
        }
        return $res;
    }
    
    public static function ArrayToList($array) {
        $out_pattern = '<ul>%s</ul>';
        $li_pattern = '<li>%s</li>';
        $li_array = array();
        foreach($array as $item) {
            $li_array[] = sprintf($li_pattern, $item);
        }
        $out = sprintf($out_pattern, implode(PHP_EOL, $li_array));
        return $out;
    }

}
