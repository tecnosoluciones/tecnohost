<?php

namespace NBHelpers;

class Debug {
    public static function HDD($data) {
        static::HD($data);
        die();
    }
    
    public static function HD($data) {
        echo '<pre>'.PHP_EOL;
        var_export($data);
        echo '</pre>';
    }
    
}
