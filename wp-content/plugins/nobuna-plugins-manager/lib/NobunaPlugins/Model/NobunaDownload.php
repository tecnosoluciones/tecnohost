<?php

namespace NobunaPlugins\Model;

use WP_Error;
use NobunaPlugins\Exceptions\NobunaError;
use WPHelpers\Package;

class NobunaDownload extends BaseModel {
    
    const TABLE_NAME = 'nobuna_downloads';
    
    public $id;
    public $product_id;
    public $download_date;
    public $product_version;
    public $file_path;
    public $file_size;
    public $main_name;
    public $type;
    
    protected $_product;

    protected static function GetFields() {
        return array(
            'fields' => array(
                'id' => new DBField('id', 'INT', FALSE, NULL, TRUE),
                'product_id' => new DBField('product_id', 'INT', FALSE),
                'download_date' => new DBField('download_date', 'DATETIME', FALSE, '0000-00-00 00:00:00'),
                'product_version' => new DBField('product_version', 'VARCHAR(20)', FALSE),
                'file_path' => new DBField('file_path', 'VARCHAR(255)', FALSE),
                'file_size' => new DBField('file_size', 'INT', FALSE),
                'main_name' => new DBField('main_name', 'VARCHAR(150)', FALSE),
                'type' => new DBField('type', 'ENUM(\'plugin\', \'theme\')', FALSE),
            ),
            'primary_key' => array('id'),
            'indexes' => array(
                new DBIndex('product_id', DBIndex::TYPE_INDEX, array('id' => DBIndex::MODE_ASC)),
                new DBIndex('main_name', DBIndex::TYPE_INDEX, array('main_name' => DBIndex::MODE_ASC)),
            ),
        );
    }
    
    public static function InstallVersion($new_version, $current_version) {
        if($current_version < 17) {
            $sql = static::GetCreateStatement();
            dbDelta($sql);
        }
    }
    
    /**
     * @param int $product_id
     * @return \NobunaPlugins\Model\NobunaDownloadSet
     */
    public static function SearchByProductId($product_id) {
        global $wpdb;
        $table = static::TableName();
        $sql = "SELECT * FROM `$table` WHERE `product_id` = '$product_id'";
        $results = $wpdb->get_results($sql);
        $downloads = new NobunaDownloadSet();
        foreach($results as $result) {
            $downloads->append(NobunaDownload::FromArray($result));
        }
        return $downloads;
    }
    
    /**
     * @param string $main_name
     * @return \NobunaPlugins\Model\NobunaDownloadSet
     */
    public static function SearchByMainName($main_name) {
        $results = static::SearchByFields(array('main_name' => $main_name));
        return new NobunaDownloadSet($results);
    }
    
    public function remove() {
        global $wpdb;
        $my_id = intval($this->id);
        if(!is_int($my_id) || $my_id <= 0) {
            return new NobunaError(NobunaError::NOBUNA_DOWNLOAD_EC_INVALID_ID,
                    __nb('Invalid ID'));
        }
        
        $file_path = $this->file_path === NULL ? '' : trim($this->file_path);
        if(empty($file_path)) {
            return new NobunaError(NobunaError::NOBUNA_DOWNLOAD_EC_EMPTY_PATH, 
                    __nb('Path is empty'));
        }
        $path = sprintf('%s/%s', ABSPATH, $file_path);
        if(!is_file($path)) {
            return new NobunaError(NobunaError::NOBUNA_DOWNLOAD_EC_FILE_NOT_FOUND, 
                    __nb('File not found'));
        }
        $remove_result = unlink($path);
        if($remove_result !== TRUE) {
            return new NobunaError(NobunaError::NOBUNA_DOWNLOAD_EC_UNABLE_TO_REMOVE, 
                    __nb('Not possible to remove'));
        }
        
        $table = static::TableName();
        $res = $wpdb->delete($table, array('id' => $this->id));
        if($res === FALSE) {
            return new NobunaError(NobunaError::NOBUNA_DOWNLOAD_EC_UNABLE_TO_REMOVE_DB, 
                    __nb('Not possible to remove from database'));
        }
        return TRUE;
    }
    
    public function setProduct(NobunaProduct $product) {
        $this->_product = $product;
    }
    
    public function product() {
        if($this->_product === NULL) {
            $this->_product = new NobunaProduct($this->product_id);
        }
        if(is_string($this->_product->product_name) && strlen($this->_product->product_name) > 0) {
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
    
    public function isActive() {
        $main_file_path = Package::GetInstalledMainFilePath($this->main_name, $this->type);
        return is_plugin_active(str_replace(trailingslashit(WP_PLUGIN_DIR), '', $main_file_path));
    }
    
    public function isInstalled() {
        $version = Package::InstalledVersion($this->main_name, $this->type);
        return strtolower(trim($this->product_version)) === strtolower(trim($version));
    }
    
    public function fileExists() {
        return file_exists(ABSPATH . $this->file_path);
    }
    
}
