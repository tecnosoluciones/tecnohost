<?php

namespace NobunaPlugins\Exceptions;

use WP_Error;
use NBHelpers\HTML;

class NobunaError extends WP_Error {

    const REQUEST_ERROR_CODE_UNKNOWN = 1000;
    
    const REQUEST_ERROR_CODE_SERVER_UNAVAILABLE = 1001;
    
    const REQUEST_ERROR_CODE_FOPEN_CURL = 1002;
    
    const REQUEST_ERROR_CODE_GET_NONCE = 1101;
    const REQUEST_ERROR_CODE_INVALID_NONCE = 1102;
    const REQUEST_ERROR_CODE_INVALID_NONCE_API_ERROR = 1103;
    
    const REQUEST_EC_FAILED_CONNECTION = 1201;
    const REQUEST_EC_FAILED_SERVER = 1202;
    const REQUEST_EC_FAILED_JSON_RESPONSE = 1203;
    const REQUEST_EC_FAILED_DOWNLOAD = 1220;
    
    const REQUEST_ERROR_CODE_UNABLE_TO_COPY = 1220;
    

    const REQUEST_ERROR_CODE_API_ERROR = 1301;
    
    const DOWNLOAD_ERROR_CREATE_FOLDER = 1401;
    
    const DOWNLOAD_ERROR_CODE_UNABLE_VERIFY = 1410;
    

    const NOBUNA_DOWNLOAD_EC_INVALID_ID = 1501;
    const NOBUNA_DOWNLOAD_EC_EMPTY_PATH = 1502;
    const NOBUNA_DOWNLOAD_EC_FILE_NOT_FOUND = 1503;
    const NOBUNA_DOWNLOAD_EC_UNABLE_TO_REMOVE = 1504;
    const NOBUNA_DOWNLOAD_EC_UNABLE_TO_REMOVE_DB = 1505;

    
    const CREATE_BACKUP_EC_NO_DESTINATION_PATH = 1601;
    const CREATE_BACKUP_EC_UNABLE_CREATE_ZIP = 1602;
    
    const NOBUNA_BACKUP_EC_INVALID_ID = 1701;
    const NOBUNA_BACKUP_EC_EMPTY_PATH = 1702;
    const NOBUNA_BACKUP_EC_FILE_NOT_FOUND = 1703;
    const NOBUNA_BACKUP_EC_UNABLE_TO_REMOVE = 1704;
    const NOBUNA_BACKUP_EC_UNABLE_TO_REMOVE_DB = 1705;
    
    
    const FILES_EC_INVALID_ID = 1801;
    
    
    const MAIN_ERROR_GET_PRODUCT_INFO = 10100;
    const MAIN_ERROR_REMOVE_PRODUCT = 10110;
    const MAIN_ERROR_REMOVED_FILE = 10120;
    const MAIN_ERROR_COULD_NOT_INSTALL = 10130;
    const MAIN_ERROR_DOWNLOAD = 10200;
    
    
    public static function IsNobunaError($thing) {
        return ($thing instanceof NobunaError);
    }

    public function render() {
        $lines = array();
        $lines[] = __nb('Nobuna Error Code: %s', $this->get_error_code());
        $lines[] = $this->get_error_message();
        $lines = array_merge($lines, $this->getErrorDataItems());
        $html = HTML::ArrayToList($lines);
        return $html;
    }
    
    private function getErrorDataItems() {
        $data = $this->get_error_data();
        if($data !== NULL && isset($data['errors'])) {
            return $data['errors'];
        }
        return array();
    }
    
}
