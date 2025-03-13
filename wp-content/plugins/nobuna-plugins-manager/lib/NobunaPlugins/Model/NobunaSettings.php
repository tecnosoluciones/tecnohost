<?php

namespace NobunaPlugins\Model;

use BadMethodCallException;

/**
 * @property-read string $key
 * @property-read string $secret
 * @property-read int $downloads_count
 * @property-read int $backups_count
 * @property-read string $requests_protocol
 * @property-read int $items_per_page
 * @property-read boolean $use_admin_menu
 */
class NobunaSettings {
    
    private $_key;
    private $_secret;
    private $_downloads_count;
    private $_backups_count;
    private $_requests_protocol;
    private $_items_per_page;
    private $_use_admin_menu;
    
    private static $_shared = NULL;
    
    /**
     * 
     * @return NobunaSettings
     */
    public static function Shared() {
        if(static::$_shared === NULL) {
            static::$_shared = new NobunaSettings();
        }
        return static::$_shared;
    }
    
    private function __construct() {
        $this->_key = get_option(NOBUNA_KEY_OPTION_KEY, '');
        $this->_secret = get_option(NOBUNA_SECRET_OPTION_KEY, '');
        $this->_downloads_count = get_option(NOBUNA_DOWNLOADS_COUNT_OPTION_KEY, NOBUNA_DOWNLOADS_COUNT_DEFAULT);
        $this->_backups_count = get_option(NOBUNA_BACKUPS_COUNT_OPTION_KEY, NOBUNA_BACKUPS_COUNT_DEFAULT);
        $this->_requests_protocol = get_option(NOBUNA_REQUESTS_PROTOCOL_OPTION_KEY, NOBUNA_REQUESTS_PROTOCOL_DEFAULT);
        $this->_items_per_page = get_option(NOBUNA_ITEMS_PER_PAGE_OPTION_KEY, NOBUNA_ITEMS_PER_PAGE_DEFAULT);
        $this->_use_admin_menu = get_option(NOBUNA_USE_ADMIN_MENU_OPTION_KEY, TRUE);
    }
    
    public function __get($name) {
        if($name === 'use_admin_menu') {
            return $this->_use_admin_menu == 1 ? TRUE : FALSE;
        }
        $var_name = sprintf('_%s', $name);
        if(property_exists(__CLASS__, $var_name)) {
            return $this->$var_name;
        }
        return NULL;
    }
    
    public function __set($name, $value) {
        throw new BadMethodCallException();
    }
    
    public function setKey($newKey) {
        $this->setVar(NOBUNA_KEY_OPTION_KEY, '_key', $newKey);
    }
    
    public function setSecret($newSecret) {
        $this->setVar(NOBUNA_SECRET_OPTION_KEY, '_secret', $newSecret);
    }
    
    public function setDownloadsCount($count) {
        $this->setVar(NOBUNA_DOWNLOADS_COUNT_OPTION_KEY, '_downloads_count', $count);
    }
    
    public function setBackupsCount($count) {
        $this->setVar(NOBUNA_BACKUPS_COUNT_OPTION_KEY, '_backups_count', $count);
    }
    
    public function setRequestsProtocol($protocol) {
        $this->setVar(NOBUNA_REQUESTS_PROTOCOL_OPTION_KEY, '_requests_protocol', $protocol);
    }
    
    public function setItemsPerPage($count) {
        $this->setVar(NOBUNA_ITEMS_PER_PAGE_OPTION_KEY, '_items_per_page', $count);
    }
    
    public function setUseAdminMenu($use) {
        $this->setVar(NOBUNA_USE_ADMIN_MENU_OPTION_KEY, '_use_admin_menu', $use ? 1 : 0);
    }
    
    private function setVar($key, $varname, $newval) {
        $this->$varname = $newval;
        update_option($key, $newval);
    }
    
}

