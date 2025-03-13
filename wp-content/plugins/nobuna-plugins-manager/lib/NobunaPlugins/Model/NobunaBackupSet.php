<?php

namespace NobunaPlugins\Model;

use ArrayObject;
use NBHelpers\Date;

class NobunaBackupSet extends ArrayObject {

    /**
     * @return \NobunaPlugins\Model\NobunaBackupSet
     */
    public function getExisting() {
        $res = new NobunaBackupSet;
        reset($this);
        foreach($this as $backup) {
            /* @var $backup NobunaBackup */
            if($backup->fileExists()) {
                $res->append($backup);
            }
        }
        return $res;
    }

    public function fillProducts() {
        if(count($this) <= 0) {
            return;
        }
        $ids = array();
        reset($this);
        foreach($this as $backup) {
            /* @var $backup NobunaBackup */
            if($backup->product_id !== NULL) {
                $ids[] = $backup->product_id;
            }
        }
        $products = NobunaProduct::ProductsByIds($ids);
        reset($this);
        foreach($this as $backup) {
            if($backup->product_id === NULL) {
                continue;
            }
            $product = $products->productById($backup->product_id);
            if($product !== NULL) {
                $backup->setProduct($product);
            }
        }
    }

    public function getGroupsByProduct() {
        reset($this);
        $result = array();
        foreach($this as $backup) {
            /* @var $backup NobunaBackup */
            if(!isset($result[$backup->main_name])) {
                $result[$backup->main_name] = new NobunaBackupSet();
            }
            $result[$backup->main_name]->append($backup);
        }
        return $result;
    }
    
    /**
     * @param int $max_count_to_keep
     * @return \NobunaPlugins\Model\NobunaBackupSet
     */
    public function getBackupsToRemove($max_count_to_keep) {
        $count = count($this);
        $result = new NobunaBackupSet();
        if($count <= $max_count_to_keep) {
            return $result;
        }
        $copy = $this->getArrayCopy();
        $items_to_remove = array_slice($copy, $max_count_to_keep);
        $result->exchangeArray($items_to_remove);
        return $result;
    }
    
    public function remove() {
        reset($this);
        foreach($this as $backup) {
            /* @var $backup NobunaBackup */
            $res = $backup->remove();
            if($res !== TRUE) {
                return $res;
            }
        }
        return TRUE;
    }
    
    public function fixProductIdIfNeeded($product_id = NULL) {
        if(count($this) <= 0) {
            return;
        }
        $product_id = $product_id === NULL ? $this->getSingleProductId() : $product_id;
        if($product_id === NULL) {
            return;
        }
        reset($this);
        foreach($this as $backup) {
            /* @var $backup NobunaBackup */
            if($backup->product_id === NULL) {
                $backup->product_id = $product_id;
                $backup->save();
            }
        }
    }
    
    public function getSingleProductId() {
        $ids = array();
        reset($this);
        foreach($this as $backup) {
            /* @var $backup NobunaBackup */
            if($backup->product_id === NULL) {
                continue;
            }
            $ids[$backup->product_id] = $backup->product_id;
        }
        if(count($ids) === 1) {
            $ids_values = array_values($ids);
            return $ids_values[0];
        }
        return NULL;
    }
    
    /**
     * @return NobunaBackupSet
     */
    public function sortByNameAscDownloadDateDesc() {
        if(count($this) > 1) {
            $c = $this->getArrayCopy();
            usort($c, array(get_class($this), 'CmpNameAscAndDateDesc'));
            $this->exchangeArray($c);
        }
        return $this;
    }
    
    public static function CmpNameAscAndDateDesc(NobunaBackup $a, NobunaBackup $b) {
        $a_name = $a->getProductNameOrMainName();
        $b_name = $b->getProductNameOrMainName();
        $name_cmp = strcasecmp($a_name, $b_name);
        if($name_cmp !== 0) {
            return $name_cmp;
        }
        $at = Date::TimestampFromUTCMysqlString($a->created_on);
        $bt = Date::TimestampFromUTCMysqlString($b->created_on);
        if($at > $bt) { return -1; }
        if($at < $bt) { return 1; }
        return 0;
    }
    
    /**
     * @return NobunaBackupSet
     */
    public function sortByBackupDateDesc() {
        if(count($this) > 1) {
            $c = $this->getArrayCopy();
            usort($c, array(get_class($this), 'CmpDateDesc'));
            $this->exchangeArray($c);
        }
        return $this;
    }
    
    public static function CmpDateDesc(NobunaBackup $a, NobunaBackup $b) {
        $at = Date::TimestampFromUTCMysqlString($a->created_on);
        $bt = Date::TimestampFromUTCMysqlString($b->created_on);
        if($at > $bt) { return -1; }
        if($at < $bt) { return 1; }
        return 0;
    }
    
    
}
