<?php

namespace NobunaPlugins\Controllers;

use NobunaPlugins\Model\NobunaDownload;
use NobunaPlugins\Exceptions\NobunaError;

class NobunaAdminUIDownloads extends NobunaAdminUIFiles {

    const SLUG = 'nobuna-plugins-downloads';

    public static function ShouldAddCommonItems() {
        return static::IsMyPage();
    }

    protected static function GetTitle() {
        return __nb('Downloads');
    }
    
    protected static function GetType() {
        return 'download';
    }
    
    protected static function _GetFileGroups() {
        $downloads = NobunaDownload::All()->getExisting();
        if (count($downloads) <= 0) {
            return array();
        }
        $downloads->fillProducts();
        $downloads->sortByNameAscDownloadDateDesc();
        $groups = $downloads->getGroupsByProduct();
        return $groups;
    }
    
    public static function RemoveItem($item_id) {
        $download = new NobunaDownload($item_id);
        $name = $download->getProductNameOrMainName();
        $remove_result = $download->remove();
        if(NobunaError::IsNobunaError($remove_result)) {
            static::SetGlobalError($remove_result);
        }
    }

}
