<?php

namespace WPHelpers;

class Pages {

    public static function IsInAdminPage($page) {
        if (isset($_REQUEST['page']) && $_REQUEST['page'] === $page) {
            return TRUE;
        }
        return FALSE;
    }
    
    public static function IsPluginsPage() {
        $uri = $_SERVER['REQUEST_URI'];
        return basename($uri) === 'plugins.php';
    }

}
