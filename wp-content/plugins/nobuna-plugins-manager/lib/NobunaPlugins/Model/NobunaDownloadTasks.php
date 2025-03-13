<?php

namespace NobunaPlugins\Model;

class NobunaDownloadTasks extends NobunaTasks {
    
    public static function CheckFilesCount(NobunaSettings $settings, NobunaDownload $nobunaDownload) {
        /* @var $downloads NobunaDownloadSet */
        $downloads = NobunaDownload::SearchByProductId($nobunaDownload->product_id);

        if (count($downloads) === 0) {
            return TRUE;
        }
        
        $max_downloads = $settings->downloads_count;
        
        $downloads->sortByDownloadDateDesc();
        $downloadsToRemove = $downloads->getDownloadsToRemove($max_downloads);
        $res = $downloadsToRemove->remove();
        
        return $res;
    }
    
}
