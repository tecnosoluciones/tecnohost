<?php

namespace WPHelpers;

class WarningRenderer {
    
    /**
     * 
     * @param string $message
     * @param bool $is_dismissible
     * @return string
     */
    public static function GetErrorMessage($message, $is_dismissible = FALSE) {
        return static::GetMessage($message, 'notice-error', $is_dismissible);
    }
    
    /**
     * 
     * @param string $message
     * @param bool $is_dismissible
     * @return string
     */
    public static function GetWarningMessage($message, $is_dismissible = FALSE) {
        return static::GetMessage($message, 'notice-warning', $is_dismissible);
    }
    
    /**
     * 
     * @param string $message
     * @param bool $is_dismissible
     * @return string
     */
    public static function GetInfoMessage($message, $is_dismissible = FALSE) {
        return static::GetMessage($message, 'notice-info', $is_dismissible);
    }
    
    /**
     * 
     * @param string $message
     * @param bool $is_dismissible
     * @return string
     */
    public static function GetSuccessMessage($message, $is_dismissible = FALSE) {
        return static::GetMessage($message, 'notice-success', $is_dismissible);
    }
    
    /**
     * 
     * @param string $message
     * @param string $class
     * @param bool $is_dismissible
     * @return string
     */
    private static function GetMessage($message, $class, $is_dismissible = FALSE) {
        $pattern = '<div class="notice nobuna-notice %s%s"><p>%s</p></div>';
        $result = sprintf($pattern, $class, $is_dismissible === TRUE ? ' is-dismissible' : '', $message);
        return $result;
    }
    
}
