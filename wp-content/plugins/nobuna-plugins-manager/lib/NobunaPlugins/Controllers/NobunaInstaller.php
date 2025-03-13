<?php

namespace NobunaPlugins\Controllers;

use NobunaPlugins\Model\NobunaProduct;
use NobunaPlugins\Model\NobunaDownload;
use NobunaPlugins\Model\NobunaBackup;

class NobunaInstaller {

    public $current_db_version;
    public $installed_db_version;

    public function __construct() {
        $this->current_db_version = intval(NOBUNA_PLUGINS_DB_VERSION);
        $this->installed_db_version = intval(get_option(NOBUNA_PLUGINS_DB_VERSION_OPTION, '0'));
    }

    public function run() {
        $new_version = $this->current_db_version;
        $current_version = $this->installed_db_version;
        if ($new_version != $current_version) {
            // create/modify/delete tables
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            NobunaProduct::InstallVersion($new_version, $current_version);
            NobunaDownload::InstallVersion($new_version, $current_version);
            NobunaBackup::InstallVersion($new_version, $current_version);
            $this->updateDbVersion();
        }
    }

    private function updateDbVersion() {
        delete_option(NOBUNA_PLUGINS_DB_VERSION_OPTION);
        add_option(NOBUNA_PLUGINS_DB_VERSION_OPTION, $this->current_db_version);
    }

}
