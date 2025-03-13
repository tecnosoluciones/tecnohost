<?php

namespace NobunaPlugins\Controllers;

use NobunaPlugins\Model\NobunaBackup;
use NobunaPlugins\Exceptions\NobunaError;

class NobunaAdminUIBackups extends NobunaAdminUIFiles {

    const SLUG = 'nobuna-plugins-backups';

    public static function ShouldAddCommonItems() {
        return static::IsMyPage();
    }

    protected static function GetTitle() {
        return __nb('Backups');
    }
    
    protected static function GetType() {
        return 'backup';
    }
    
    protected static function _GetFileGroups() {
        /* @var $backups NobunaBackupSet */
        $backups = NobunaBackup::All()->getExisting();
        if (count($backups) <= 0) {
            return array();
        }
        $backups->fillProducts();
        $backups->sortByNameAscDownloadDateDesc();
        $groups = $backups->getGroupsByProduct();
        return $groups;
    }

    public static function RemoveItem($item_id) {
        $backup = new NobunaBackup($item_id);
        $name = $backup->getProductNameOrMainName();
        $remove_result = $backup->remove();
        if (NobunaError::IsNobunaError($remove_result)) {
            static::SetGlobalError($remove_result);
        }
    }

}
