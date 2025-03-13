<?php

namespace NobunaPlugins\Controllers;

class NobunaAdminUIThemesBackup extends NobunaAdminUIBase {

    public static function ShouldAddCommonItems() {
        return static::IsMyPage();
    }
    
    public static function Init() {
        ;
    }
    
    public static function Index() {
        ;
    }
    
}
