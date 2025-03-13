<?php

namespace NobunaPlugins\Controllers;

use WP_Error;
use NBHelpers\Url;
use NBHelpers\HTML;
use WPHelpers\Pages;
use WPHelpers\Package;
use NobunaPlugins\Model\NobunaBackup;
use NobunaPlugins\Model\HTMLResult;
use NobunaPlugins\Exceptions\NobunaError;
use NobunaPlugins\Model\NobunaSettings;
use NobunaPlugins\Model\NobunaBackupTasks;

class NobunaAdminUIWPPluginsPage extends NobunaAdminUIBase {

    public static function ShouldAddCommonItems() {
        return Pages::IsPluginsPage();
    }

    public static function Init() {
        static::AddAjaxMethod('nobuna_backup', 'BackupPluginAjax');
        if (!Pages::IsPluginsPage()) {
            return;
        }
        static::AddNBAdminStyle();
        static::AddScript('nobuna_public_plugins_page.js');

        $plugin_main_files = array_keys(Package::GetInstalledPlugins());
        foreach ($plugin_main_files as $plugin_main_file) {
            add_filter('plugin_action_links_' . plugin_basename($plugin_main_file), array(__CLASS__, 'AddActionLinks'));
        }
    }

    public static function Index() {
        
    }

    public static function AddActionLinks($links) {
        if (!is_array($links) || count($links) <= 0 || isset($links['nobuna_backup'])) {
            return;
        }
        $plugin = NULL;
        foreach ($links as $link) {
            $href = HTML::GetAHref($link, 'a');
            $plugin = Url::GetURIParameter($href, 'plugin');
            if ($plugin !== NULL) {
                break;
            }
        }
        if ($plugin !== NULL) {
            $blink = sprintf('<a class="backup" data-plugin="%s">%s</a>', $plugin, __nb('Nobuna backup'));
            $miniloading_img = sprintf('<img class="nb-hidden nb-icon-16" width="16" height="16" src="%s" />', static::GetImageUrl('miniloading.gif'));
            $my_links = array('backup' => sprintf('%s%s', $miniloading_img, $blink));
            $links = array_merge($my_links, $links);
        }
        return $links;
    }

    public static function BackupPluginAjax() {
        $result = HTMLResult::GlobalResult();
        $plugin = $_POST['plugin'];
        $dirname = dirname($plugin);
        $toBackup = $dirname === '.' ? $plugin : $dirname;
        $backupPath = sprintf('%s/%s', WP_PLUGIN_DIR, $toBackup);
        $backup_result = NobunaBackup::CreateBackup($backupPath);

        if (NobunaError::IsNobunaError($backup_result)) {
            $result->setError($backup_result);
        } else {
            $result->addSuccess(__nb('Plugin successfuly backed up in: %s', $backup_result->path));
        }

        $result->printJson();
        wp_die();
    }

}
