<?php

namespace NobunaPlugins\Controllers;

use ArrayObject;

class NobunaAdminFileSetSet extends ArrayObject {

    /**
     * @param array $filesSets
     * @param string $type
     * @return NobunaAdminFileSetSet
     * @throws Exception
     */
    public static function SetFromGroups(array $filesSets, $type) {
        $result = new NobunaAdminFileSetSet;
        reset($filesSets);
        foreach($filesSets as $fileSet) {
            $adminFileSet = new NobunaAdminFileSet;
            reset($fileSet);
            foreach($fileSet as $fileData) {
                switch ($type) {
                    case 'download':
                        $file = NobunaAdminFile::FromNobunaDownload($fileData);
                        break;
                    case 'backup':
                        $file = NobunaAdminFile::FromNobunaBackup($fileData);
                        break;
                    default:
                        throw new Exception('Invalid argument');
                }
                $adminFileSet->append($file);
            }
            $result->append($adminFileSet);
        }
        return $result;
    }
    
    /**
     * @return int
     */
    public function adminFilesCount() {
        $total = 0;
        foreach($this as $set) {
            /* @var $set NobunaAdminFileSet */
            $total += $set->count();
        }
        return $total;
    }
    
}
