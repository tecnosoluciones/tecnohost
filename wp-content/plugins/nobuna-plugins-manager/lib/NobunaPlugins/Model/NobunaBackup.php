<?php

namespace NobunaPlugins\Model;

use PclZip;

use WP_Error;
use NBHelpers\Str;
use NBHelpers\System;
use NBHelpers\File;
use NBHelpers\Date;
use WPHelpers\Package;
use NobunaPlugins\Controllers\NobunaPluginsApp;
use NobunaPlugins\Model\NobunaProduct;
use NobunaPlugins\Model\NobunaBackup;
use NobunaPlugins\Exceptions\NobunaError;
use NobunaPlugins\Model\HTMLResult;

class NobunaBackup extends BaseModel {
    
    const TABLE_NAME = 'nobuna_backups';
    
    const TYPE_PLUGIN = 'plugin';
    const TYPE_THEME = 'theme';
    
    public $id;
    public $product_id;
    public $created_on;
    public $path;
    public $type;
    public $name;
    public $version;
    public $main_name;

    protected $_product = NULL;
    
    protected static function GetFields() {
        return array(
            'fields' => array(
                'id' => new DBField('id', 'INT', FALSE, NULL, TRUE),
                'product_id' => new DBField('product_id', 'INT', TRUE),
                'created_on' => new DBField('created_on', 'DATETIME', FALSE, '0000-00-00 00:00:00'),
                'path' => new DBField('path', 'VARCHAR(255)', FALSE),
                'type' => new DBField('type', 'ENUM(\'theme\', \'plugin\')', FALSE),
                'name' => new DBField('name', 'VARCHAR(255)', FALSE),
                'version' => new DBField('version', 'VARCHAR(50)', FALSE),
                'main_name' => new DBField('main_name', 'VARCHAR(150)', FALSE),
            ),
            'primary_key' => array('id'),
            'indexes' => array(
                new DBIndex('product_id', DBIndex::TYPE_INDEX, array('id' => DBIndex::MODE_ASC)),
                new DBIndex('main_name', DBIndex::TYPE_INDEX, array('main_name' => DBIndex::MODE_ASC)),
            ),
        );
    }

    private static function FeedbackToSkin($msg, $skin = NULL) {
        if($skin !== NULL) {
            $skin->feedback($msg);
        }
    }
    
    private static function AddGlobalWarning($msg) {
        if (HTMLResult::UseGlobal()) {
            HTMLResult::GlobalResult()->addWarning($msg);
        }
    }
    
    public static function CreateBackup($directory, $nobunaProduct = NULL, $skin = NULL, $is_theme = FALSE) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $type = $is_theme ? static::TYPE_THEME : static::TYPE_PLUGIN;
        $_type = $is_theme ? __nb('theme') : __nb('plugin');
        
        $main_name = str_replace('.php', '', basename($directory));
        if($nobunaProduct === NULL) {
            $nobunaProduct = NobunaProduct::ProductByMainName($main_name);
        }

        $backup_dir = NobunaPluginsApp::GetBackupsFolderPath(TRUE);

        $zip_path = sprintf('%s/%s', $backup_dir['path'], $main_name);
        File::CreateDirectory($zip_path);

        if (!is_dir($zip_path)) {
            return new NobunaError(NobunaError::CREATE_BACKUP_EC_NO_DESTINATION_PATH, 
                    __nb('A plugin backup can not be created since a destination path for the backup file could not be found.'));
        }

        if(!$is_theme) {
            $data = Package::GetPluginInfo($directory);
        } else {
            $data = Package::GetThemeInfo("$directory/style.css");
        }

        for($i = 0; $i < 50; $i++) {
            $rand_string = Str::GetRandomCharacters(10, 20);
            $zip_file = $main_name . "-{$data['Version']}-$rand_string.zip";
            if(!file_exists(sprintf('%s/%s', $zip_path, $zip_file))) {
                break;
            }
        }

        // Reduce the chance that a timeout will occur while creating the zip file.
        @set_time_limit(600);

        // Attempt to increase memory limits.
        System::SetMinimumMemoryLimit('256M');

        $zip_path .= "/$zip_file";

        require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );

        $archive = new PclZip($zip_path);

        $zip_result = $archive->create($directory, PCLZIP_OPT_REMOVE_PATH, dirname($directory));

        if (0 === $zip_result) {
            /* translators: 1: zip error details */
            return new NobunaError(NobunaError::CREATE_BACKUP_EC_UNABLE_CREATE_ZIP,
                    __nb('A %s backup can not be created as creation of the zip file failed with the following error: %1$s',
                            $_type, $archive->errorInfo(true)));
        }

        $nbBackup = new NobunaBackup();
        $nbBackup->created_on = Date::UtcNowMysqlFormatted();
        $nbBackup->main_name = $main_name;
        $nbBackup->name = $data['Name'];
        $nbBackup->version = $data['Version'];
        $nbBackup->path = str_replace(ABSPATH, '', $zip_path);
        $nbBackup->type = $type;
        $nbBackup->product_id = $nobunaProduct !== NULL ? $nobunaProduct->id : NULL;
        $nbBackup->save();

        $msg = __nb('Backup created in: <a href="/%s">/%s</a>', $nbBackup->path, $nbBackup->path);
        static::FeedbackToSkin($msg, $skin);

        try {
            NobunaBackupTasks::CheckFilesCount(NobunaSettings::Shared(), $nbBackup);
        } catch (\Exception $e) {
            $msg = __nb('There was a problem trying to clean old backups');
            static::AddGlobalWarning($msg);
            static::FeedbackToSkin($msg, $skin);
        }
        
        return $nbBackup;
        
    }
    
    public static function InstallVersion($new_version, $current_version) {
        if($current_version < 17) {
            $sql = static::GetCreateStatement();
            dbDelta($sql);
        }
    }

    public static function GetRelatedBackups(NobunaBackup $backup) {
        global $wpdb;
        $table = static::TableName();
        $sql_pattern = "SELECT * FROM `$table` WHERE `main_name` LIKE '%s'";
        if($backup->product_id === NULL) {
            $sql = $wpdb->prepare($sql_pattern, $backup->main_name);
            $results = $wpdb->get_results($sql);
        } else {
            $sql_pattern .= ' OR `product_id` = \'%d\'';
            $sql = $wpdb->prepare($sql_pattern, $backup->main_name, $backup->product_id);
            $results = $wpdb->get_results($sql);
        }
        $set = new NobunaBackupSet();
        foreach($results as $result) {
            $set->append(NobunaBackup::FromArray($result));
        }
        $set->fixProductIdIfNeeded();
        return $set;
    }
    
    public static function FixByMainName($main_name, $product_id) {
        global $wpdb;
        $table = static::TableName();
        $sql_pattern = "SELECT * FROM `$table` WHERE `main_name` LIKE '%s'";
        $sql = $wpdb->prepare($sql_pattern, $main_name);
        $results = $wpdb->get_results($sql);
        $set = new NobunaBackupSet();
        foreach($results as $result) {
            $set->append(NobunaBackup::FromArray($result));
        }
        $set->fixProductIdIfNeeded($product_id);
        return $set;
    }
    
    public function fileExists() {
        return file_exists(ABSPATH . $this->path);
    }
    
    public function setProduct(NobunaProduct $product) {
        $this->_product = $product;
    }
    
    public function product() {
        if($this->_product === NULL && $this->product_id !== NULL) {
            $this->_product = new NobunaProduct($this->product_id);
        }
        if($this->_product !== NULL && is_string($this->_product->product_name) && strlen($this->_product->product_name) > 0) {
            return $this->_product;
        }
        return NULL;
    }
    
    public function getProductNameOrMainName() {
        $product = $this->product();
        if($product !== NULL) {
            return $product->product_name;
        }
        return $this->main_name;
    }
    
    public function size() {
        $path = ABSPATH . '/' . $this->path;
        if(file_exists($path)) {
            return filesize($path);
        }
        return 0;
    }

    public function remove() {
        global $wpdb;
        $my_id = intval($this->id);
        if(!is_int($my_id) || $my_id <= 0) {
            return new NobunaError(NobunaError::NOBUNA_BACKUP_EC_INVALID_ID,
                    __nb('Invalid ID'));
        }
        
        $file_path = $this->path === NULL ? '' : trim($this->path);
        if(empty($file_path)) {
            return new NobunaError(NobunaError::NOBUNA_BACKUP_EC_EMPTY_PATH,
                    __nb('Empty path'));
        }
        $path = sprintf('%s/%s', ABSPATH, $file_path);
        if(!is_file($path)) {
            return new NobunaError(NobunaError::NOBUNA_BACKUP_EC_FILE_NOT_FOUND, 
                    __nb('File not found'));
        }
        $remove_result = unlink($path);
        if($remove_result !== TRUE) {
            return new NobunaError(NobunaError::NOBUNA_BACKUP_EC_UNABLE_TO_REMOVE, 
                    __nb('Not possible to remove'));
        }
        
        $table = static::TableName();
        $res = $wpdb->delete($table, array('id' => $this->id));
        if($res === FALSE) {
            return new NobunaError(NobunaError::NOBUNA_BACKUP_EC_UNABLE_TO_REMOVE_DB, 
                    __nb('Not possible to remove from database'));
        }
        return TRUE;
    }
    
}
