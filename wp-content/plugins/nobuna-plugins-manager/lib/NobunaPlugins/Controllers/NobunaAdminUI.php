<?php

namespace NobunaPlugins\Controllers;

use NobunaPlugins\Model\NobunaSettings;

class NobunaAdminUI {

    public static function Init() {
        add_action('admin_menu', array(__CLASS__, 'AddMenuGroupAndSubgroups'));
        if(NobunaSettings::Shared()->use_admin_menu) {
            add_action('wp_before_admin_bar_render', array(__CLASS__, 'AddAdminBarMenu'));
        }
        NobunaAdminUIBase::AddStyle('global.css');
        NobunaAdminUIWPPluginsPage::Run();
        NobunaAdminUIMain::Run();
        NobunaAdminUISettings::Run();
        NobunaAdminUIDownloads::Run();
        NobunaAdminUIBackups::Run();
    }

    public static function AddMenuGroupAndSubgroups() {
        add_menu_page(__nb('Nobuna Plugins'), __nb('Nobuna Plugins'), 'install_plugins', 'nobuna-plugins', array('NobunaPlugins\Controllers\NobunaAdminUIMain', 'Index'), 'dashicons-smiley', 65);
        add_submenu_page('nobuna-plugins', __nb('Plugins'), __nb('Plugins'), 'install_plugins', NobunaAdminUIMain::SLUG, array('NobunaPlugins\Controllers\NobunaAdminUIMain', 'Index'));
        add_submenu_page('nobuna-plugins', __nb('Downloads'), __nb('Downloads'), 'install_plugins', NobunaAdminUIDownloads::SLUG, array('NobunaPlugins\Controllers\NobunaAdminUIDownloads', 'Index'));
        add_submenu_page('nobuna-plugins', __nb('Backups'), __nb('Backups'), 'install_plugins', NobunaAdminUIBackups::SLUG, array('NobunaPlugins\Controllers\NobunaAdminUIBackups', 'Index'));
        add_submenu_page('nobuna-plugins', __nb('Settings'), __nb('Settings'), 'install_plugins', NobunaAdminUISettings::SLUG, array('NobunaPlugins\Controllers\NobunaAdminUISettings', 'Index'));
    }
    
    private static function GetKey($name) {
        return sanitize_key(basename(__FILE__, '.php' ) . '-' . $name);
    }
    
    private static function AddAdminBarGroup($name) {
        global $wp_admin_bar;
        $wp_admin_bar->add_group(array('id' => static::GetKey($name)));
    }
    
    private static function AddAdminBarNode($name, $url, $parent, $prepend = '', $append = '') {
        global $wp_admin_bar;
        $wp_admin_bar->add_node(array(
            'id' => static::GetKey($name),
            'title' => $prepend . $name . $append,
            'href' => $url,
            'parent' => static::GetKey($parent)
        ));
    }
    
    public static function AddAdminBarMenu() {
        static::AddAdminBarGroup(__nb('Nobuna group'));
        static::AddAdminBarNode(__nb('Nobuna Plugins'), admin_url('admin.php?page='.NobunaAdminUIMain::SLUG), __nb('Nobuna group'), '<div class="wp-menu-image nobuna-color dashicons-before dashicons-smiley"> ', '</div>');
        static::AddAdminBarNode(__nb('Plugins'), admin_url('admin.php?page='.NobunaAdminUIMain::SLUG), __nb('Nobuna Plugins'));
        static::AddAdminBarNode(__nb('Downloads'), admin_url('admin.php?page='. NobunaAdminUIDownloads::SLUG), __nb('Nobuna Plugins'));
        static::AddAdminBarNode(__nb('Backups'), admin_url('admin.php?page='. NobunaAdminUIBackups::SLUG), __nb('Nobuna Plugins'));
        static::AddAdminBarNode(__nb('Settings'), admin_url('admin.php?page='. NobunaAdminUISettings::SLUG), __nb('Nobuna Plugins'));
    }

}
