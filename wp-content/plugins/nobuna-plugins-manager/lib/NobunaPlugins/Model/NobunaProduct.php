<?php

namespace NobunaPlugins\Model;

use WPHelpers\Package;
use NobunaPlugins\Model\NobunaDownloadSet;
use Exception;

class NobunaProduct extends BaseModel {

    const TABLE_NAME = 'nobuna_products';
    const ACTIVATION_STATUS_UNKNOWN = 'unknown';
    const ACTIVATION_STATUS_OFF = 'off';
    const ACTIVATION_STATUS_ON = 'on';

    public $id = '';
    public $product_name = '';
    public $last_version = '';
    public $versions_available = array();
    public $file_name = '';
    public $file_size = 0;
    public $allowed_downloads = 0;
    public $used_downloads = 0;
    public $available_downloads = 0;
    public $status = 0;
    public $type = NULL; // Use here NOBUNA_TYPE_* values
    public $removable = FALSE;

    public $purchase = '';
    
    protected static function GetFields() {
        return array(
            'fields' => array(
                'id' => new DBField('id', 'INT', FALSE, NULL),
                'product_name' => new DBField('product_name', 'VARCHAR(255)', FALSE, NULL),
                'last_version' => new DBField('last_version', 'VARCHAR(20)', FALSE, NULL),
                'versions_available' => new DBField('versions_available', 'TEXT', FALSE, NULL),
                'file_name' => new DBField('file_name', 'VARCHAR(255)', FALSE, NULL),
                'file_size' => new DBField('file_size', 'INT', FALSE, '0'),
                'allowed_downloads' => new DBField('allowed_downloads', 'INT', FALSE, '0'),
                'used_downloads' => new DBField('used_downloads', 'INT', FALSE, '0'),
                'available_downloads' => new DBField('available_downloads', 'INT', FALSE, '0'),
                'status' => new DBField('status', 'INT', FALSE, '0'),
                'type' => new DBField('type', 'ENUM(\'plugin\',\'theme\')', TRUE, NULL),
                'removable' => new DBField('removable', 'TINYINT(1)', FALSE, '0'),
            ),
            'primary_key' => array('id'),
            'alias' => array('product_id' => 'id'),
            'json_array' => array('versions_available'),
            'booleans' => array('removable'),
        );
    }

    public static function InstallVersion($new_version, $current_version) {
        if ($current_version < 17) {
            $sql = static::GetCreateStatement();
            dbDelta($sql);
        }
        
        if ($current_version >= 17 && $new_version <= 20) {
            /* @var $field DBField */
            global $wpdb;
            $fields_array = static::GetFields();
            $field = $fields_array['fields']['removable'];
            $create_line = $field->getCreateLine();
            $table = static::TableName();
            $sql = "ALTER TABLE `$table`"
                . " ADD COLUMN $create_line AFTER `type`";
            $wpdb->query($sql);
        }
    }

    public static function ProductByMainName($main_name) {
        $downloads = NobunaDownload::SearchByMainName($main_name);
        $pid = $downloads->getLastProductId();
        if ($pid !== NULL) {
            return new NobunaProduct($pid);
        }
        return NULL;
    }

    /**
     * @param array $ids
     * @return \NobunaPlugins\Model\NobunaProductSet
     */
    public static function ProductsByIds($ids) {
        global $wpdb;
        $table = static::TableName();
        $ids_str = implode('\', \'', $ids);
        $sql_ptr = 'SELECT * FROM `%s` WHERE `id` IN (\'%s\')';
        $sql = sprintf($sql_ptr, $table, $ids_str);
        $results = $wpdb->get_results($sql);
        $set = new NobunaProductSet();
        foreach($results as $result) {
            $set->append(NobunaProduct::FromArray($result));
        }
        return $set;
    }
    
    private $_downloads;

    /**
     * @return NobunaDownloadSet
     * @throws Exception
     */
    public function downloads() {
        if ($this->id === NULL) {
            throw new Exception('id not defined');
        }
        if ($this->_downloads === NULL) {
            $this->_downloads = NobunaDownload::SearchByProductId($this->id);
        }
        return $this->_downloads;
    }

    /**
     * 
     * @return NobunaDownloadSet
     */
    public function valid_downloads() {
        $downloads = $this->downloads();
        $res = $downloads->getExisting();
        return $res;
    }

    public function has_download() {
        $version = $this->getMyVersionDownload();
        return $version !== NULL;
    }

    /**
     * @return NobunaDownload
     */
    public function getMyVersionDownload() {
        $downloads = $this->downloads();
        $versions = $downloads->getByVersion($this->last_version);
        $last_existing = $versions->getLastExisting();
        if ($last_existing !== NULL) {
            return $last_existing;
        }
        return NULL;
    }

    public function isInstalledCurrentVersion() {
        $download = $this->getMyVersionDownload();
        if ($download === NULL) {
            return FALSE;
        }
        return $download->isInstalled();
    }

    public function type() {
        return $this->downloads()->anyDownloadType();
    }

    private $_installedVersion = NULL;

    public function installedVersion() {
        if ($this->_installedVersion === NULL) {
            $type = $this->type();
            if ($type !== NULL) {
                $this->_installedVersion = Package::InstalledVersion($this->downloads()->getLastMainName(), $type);
            }
        }
        return $this->_installedVersion;
    }

    public function lastDownloadedVersion() {
        return $this->downloads()->getLastVersionDownloaded();
    }

    public function isInstalled() {
        $downloads = $this->downloads();
        return $downloads->isAnyInstalled();
    }

    public function isActive() {
        if (!$this->isInstalled()) {
            return static::ACTIVATION_STATUS_UNKNOWN;
        }
        $downloads = $this->downloads();
        if ($downloads->isLastActive()) {
            return static::ACTIVATION_STATUS_ON;
        }
        return static::ACTIVATION_STATUS_OFF;
    }

}
