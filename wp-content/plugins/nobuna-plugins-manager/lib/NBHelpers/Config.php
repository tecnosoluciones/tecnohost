<?php

namespace NBHelpers;

/**
 *
 */
class Config {
    
    /**
     * @param string $dir
     * @param string $extension
     * @return bool
     */
    public static function RequireConfigFiles($dir, $extension = 'config.php') {
        if(!is_dir($dir)) {
            return FALSE;
        }
        $files = glob(sprintf('%s/*.%s', $dir, $extension));
        reset($files);
        foreach($files as $filepath) {
            require_once $filepath;
        }
        return TRUE;
    }
    
}
