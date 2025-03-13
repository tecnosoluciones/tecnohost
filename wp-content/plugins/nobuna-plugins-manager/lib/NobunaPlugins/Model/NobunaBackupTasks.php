<?php

namespace NobunaPlugins\Model;

class NobunaBackupTasks extends NobunaTasks {

    public static function CheckFilesCount(NobunaSettings $settings, NobunaBackup $nobunaBackup) {
        /* @var $backups NobunaBackupSet */
        $backups = NobunaBackup::GetRelatedBackups($nobunaBackup);

        if (count($backups) === 0) {
            return TRUE;
        }
        
        $max_backups = $settings->backups_count;
        
        $backups->sortByBackupDateDesc();
        $backupsToRemove = $backups->getBackupsToRemove($max_backups);
        $res = $backupsToRemove->remove();
        
        return $res;
    }
    
}
