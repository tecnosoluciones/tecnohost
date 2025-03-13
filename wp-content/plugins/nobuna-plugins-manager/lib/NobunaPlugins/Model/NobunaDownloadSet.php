<?php

namespace NobunaPlugins\Model;

use ArrayObject;
use NBHelpers\Date;

class NobunaDownloadSet extends ArrayObject {
    
    public function getByVersion($version) {
        $result = new NobunaDownloadSet;
        $this->sortByDownloadDateDesc();
        reset($this);
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            if(strtolower(trim($version)) === strtolower(trim($download->product_version))) {
                $result->append($download);
            }
        }
        return $result;
    }
    
    public function getLastProductId() {
        if(count($this) <= 0) {
            return NULL;
        }
        $this->sortByDownloadDateDesc();
        return $this[0]->product_id;
    }
    
    /**
     * @return \NobunaPlugins\Model\NobunaDownloadSet
     */
    public function getExisting() {
        $res = new NobunaDownloadSet;
        reset($this);
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            if($download->fileExists()) {
                $res->append($download);
            }
        }
        return $res;
    }
    
    public function anyDownloadType() {
        reset($this);
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            return $download->type;
        }
        return NULL;
    }
    
    /**
     * @return bool
     */
    public function isAnyInstalled() {
        reset($this);
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            if($download->isInstalled()) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * @return bool
     */
    public function isLastActive() {
        $this->sortByDownloadDateDesc();
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            if($download->isActive()) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function getLastExisting() {
        $this->sortByDownloadDateDesc();
        reset($this);
        foreach($this as $item) {
            if($item->fileExists()) {
                return $item;
            }
        }
        return NULL;
    }
    
    public function getLastMainName() {
        if(count($this) > 0) {
            $this->sortByDownloadDateDesc();
            return $this[0]->main_name;
        }
        return NULL;
    }

    /**
     * @param int $max_count_to_keep
     * @return \NobunaPlugins\Model\NobunaDownloadSet
     */
    public function getDownloadsToRemove($max_count_to_keep) {
        $count = count($this);
        $result = new NobunaDownloadSet();
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
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            $res = $download->remove();
            if($res !== TRUE) {
                return $res;
            }
        }
        return TRUE;
    }
    
    public function getLastVersionDownloaded() {
        $download = $this->getLastExisting();
        if($download !== NULL) {
            /* @var $download NobunaDownload */
            return $download->product_version;
        }
        return NULL;
    }
    
    /**
     * @return NobunaDownloadSet
     */
    public function sortByDownloadDateDesc() {
        if(count($this) > 1) {
            $c = $this->getArrayCopy();
            usort($c, array(get_class($this), 'SortByDownloadDate'));
            $this->exchangeArray($c);
        }
        return $this;
    }
    
    /**
     * @return NobunaDownloadSet
     */
    public function sortByNameAscDownloadDateDesc() {
        if(count($this) > 1) {
            $c = $this->getArrayCopy();
            usort($c, array(get_class($this), 'CmpNameAscAndDateDesc'));
            $this->exchangeArray($c);
        }
        return $this;
    }
    
    public function fillProducts() {
        if(count($this) <= 0) {
            return;
        }
        $ids = array();
        reset($this);
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            $ids[] = $download->product_id;
        }
        $products = NobunaProduct::ProductsByIds($ids);
        reset($this);
        foreach($this as $download) {
            $product = $products->productById($download->product_id);
            if($product !== NULL) {
                $download->setProduct($product);
            }
        }
    }
    
    public function getGroupsByProduct() {
        reset($this);
        $result = array();
        foreach($this as $download) {
            /* @var $download NobunaDownload */
            if(!isset($result[$download->main_name])) {
                $result[$download->main_name] = array();
            }
            $result[$download->main_name][] = $download;
        }
        return $result;
    }
    
    public static function SortByDownloadDate(NobunaDownload $a, NobunaDownload $b) {
        $at = Date::TimestampFromUTCMysqlString($a->download_date);
        $bt = Date::TimestampFromUTCMysqlString($b->download_date);
        if($at > $bt) { return -1; }
        if($at < $bt) { return 1; }
        return 0;
    }
    
    public static function CmpNameAscAndDateDesc(NobunaDownload $a, NobunaDownload $b) {
        $a_name = $a->getProductNameOrMainName();
        $b_name = $b->getProductNameOrMainName();
        $name_cmp = strcasecmp($a_name, $b_name);
        if($name_cmp !== 0) {
            return $name_cmp;
        }
        $at = Date::TimestampFromUTCMysqlString($a->download_date);
        $bt = Date::TimestampFromUTCMysqlString($b->download_date);
        if($at > $bt) { return -1; }
        if($at < $bt) { return 1; }
        return 0;
    }
    
}
