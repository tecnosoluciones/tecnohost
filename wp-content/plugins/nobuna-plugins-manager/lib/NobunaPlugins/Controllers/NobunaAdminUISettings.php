<?php

namespace NobunaPlugins\Controllers;

use NobunaPlugins\Model\NobunaSettings;
use NobunaPlugins\Model\NobunaRequest;
use NBHelpers\File;
use NBHelpers\Number;
use NBHelpers\HTTPRequest;

class NobunaAdminUISettings extends NobunaAdminUIBase {

    const SLUG = 'nobuna-plugins-settings';
    
    public static function ShouldAddCommonItems() {
        return static::IsMyPage();
    }
    
    public static function Init() {
        static::AddAjaxMethod('nobuna_folder_size', 'FolderSizeAjax');
        static::AddPostMethod('nobuna_settings', 'SettingsPagePost');
        if(static::IsMyPage()) {
            static::AddNBAdminStyle();
            static::AddScript('nobuna_settings.js');
        }
    }

    public static function Index() {
        $settings = NobunaSettings::Shared();
        $backups_string = static::GetBackupsFolderString();
        $downloads_string = static::GetDownloadsFolderString();
        $out = '';
        $out .= '<h1 class="nobuna">'.__nb('Settings').'</h1>' . PHP_EOL;
        $out .= '<div class="wrap nobuna-settings">' . PHP_EOL;
        $out .= '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">' . PHP_EOL;
        $out .= '<input type="hidden" name="action" value="nobuna_settings" />' . PHP_EOL;
        $out .= '<div><p>' . __nb('You can get your API keys <a href="%s" target="_blank">here</a>', NOBUNA_PLUGINS_USER_CONFIG_URL) . '</p></div>' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="customer-key">' . __nb('Key') . '</label> <input type="text" id="customer-key" name="customer-key" value="' . $settings->key . '" /></div>' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="customer-secret">' . __nb('Secret') . '</label> <input type="text" id="customer-secret" name="customer-secret" value="' . $settings->secret . '" /></div>' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="downloads-count">' . __nb('How many <b>versions</b> do you want to keep for each product?') . '</label> <input type="number" id="downloads-count" name="downloads-count" value="' . $settings->downloads_count . '" /></div>' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="backups-count">' . __nb('How many <b>backups</b> do you want to keep for each product?') . '</label> <input type="number" id="backups-count" name="backups-count" value="' . $settings->backups_count . '" /></div>' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="use-https">' . __nb('Using https for requests?') . '</label> <input type="checkbox" id="use-https" name="use-https" ' . ($settings->requests_protocol === NOBUNA_PROTOCOL_HTTPS ? 'checked' : '') . ' /></div>' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="use-admin-menu">' . __nb('Admin toolbar menu enabled') . '</label> <input type="checkbox" id="use-admin-menu" name="use-admin-menu" ' . ($settings->use_admin_menu === TRUE ? 'checked' : '') . ' /></div>' . PHP_EOL;
        $lib = NobunaRequest::EnabledMethod();
        $current_lib = $lib === NULL ? __nb('None') : ($lib === NobunaRequest::REQUEST_METHOD_CURL ? __nb('CURL') : __nb('Native PHP fopen'));
        $out .= '<div><label class="nb-plugins-label" for="request-method">' . __nb('Library used: ') . '</label> ' . $current_lib . '</div>' . PHP_EOL;
        $out .= '<br />' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="backups-size">' . __nb('Backups folder') . '</label><span id="folder-backups">' . $backups_string . '</span></div>' . PHP_EOL;
        $out .= '<div><label class="nb-plugins-label" for="downloads-size">' . __nb('Downloads folder') . '</label><span id="folder-downloads">' . $downloads_string . '</span></div>' . PHP_EOL;
        $out .= get_submit_button() . PHP_EOL;
        $out .= '</form>' . PHP_EOL;
        $out .= '<hr><a name="check"></a> ' . PHP_EOL;
        $out .= '<div><button class="button button-primary button-large" onclick="location.href=\''. admin_url('admin.php?page=nobuna-plugins-settings&check=1#check').'\'">' . __nb('Check connection to server') . '</button></div>' . PHP_EOL;
        $out .= '<div id="check-connection">' . PHP_EOL;
        if(isset($_GET['check']) && $_GET['check'] == 1) {
            $out .= '<pre>' . PHP_EOL;
            $out .= '<hr>';
            $out .= __nb('PHP Version: %s', phpversion()) . PHP_EOL;
            $out .= '<hr>';
            
            if(!HTTPRequest::IsFopenOrCURL()) {
                $out .= __nb('CURL disabled and allow_url_fopen=Off in php.ini');
            } else {
                $https_result = HTTPRequest::GetContentBestMethod('https://storage.nobuna.com/api/is_ok.php');
                $out .= $https_result->stringResults();
                $http_result = HTTPRequest::GetContentBestMethod('http://storage.nobuna.com/api/is_ok.php');
                $out .= '<hr>';
                $out .= $http_result->stringResults();
                $out .= '<hr>';
            }
            $out .= '</pre>' . PHP_EOL;
        }
        $out .= '</div>' . PHP_EOL;
        
        $out .= '</div>' . PHP_EOL;
        echo $out;
    }

    public static function FolderSizeAjax() {
        $p = $_POST;
        $type = $p['type'];
        $html = '';
        switch($type) {
            case 'downloads':
                $html = static::GetDownloadsFolderString(TRUE);
                break;
            case 'backups':
                $html = static::GetBackupsFolderString(TRUE);
                break;
            default:
                $html = '';
        }
        $result = array(
            'html' => $html,
        );
        echo json_encode($result);
        wp_die();
    }
    
    private static function GetBackupsFolderString($display_size = FALSE) {
        $folder_array = NobunaPluginsApp::GetBackupsFolderPath(TRUE);
        $backups_folder = $folder_array['path'];
        if($display_size === TRUE) {
            $backups_size = sprintf('- (%s)', Number::FormatBytes(File::FolderSize($backups_folder)));
        } else {
            $backups_size = static::GetMiniLoadingImageTag();
        }
        $backups_string = sprintf('%s %s', $backups_folder, $backups_size);
        return $backups_string;
    }
    
    private static function GetDownloadsFolderString($display_size = FALSE) {
        $folder_array = NobunaPluginsApp::GetDownloadsFolderPath(TRUE);
        $downloads_folder = $folder_array['path'];
        if($display_size === TRUE) {
            $downloads_size = sprintf('- (%s)', Number::FormatBytes(File::FolderSize($downloads_folder)));
        } else {
            $downloads_size = static::GetMiniLoadingImageTag();
        }
        $downloads_string = sprintf('%s %s', $downloads_folder, $downloads_size);
        return $downloads_string;
    }
    
    public static function SettingsPagePost() {
        $p = $_POST;
        $settings = NobunaSettings::Shared();
        $settings->setKey($p['customer-key']);
        $settings->setSecret($p['customer-secret']);
        $settings->setDownloadsCount(intval($p['downloads-count']));
        $settings->setBackupsCount(intval($p['backups-count']));
        $settings->setRequestsProtocol(isset($p['use-https']) && $p['use-https'] == 'on' ? NOBUNA_PROTOCOL_HTTPS : NOBUNA_PROTOCOL_HTTP);
        $settings->setUseAdminMenu(isset($p['use-admin-menu']) && $p['use-admin-menu'] == 'on' ? TRUE : FALSE);
        wp_redirect('/wp-admin/admin.php?page=' . static::SLUG);
    }

}
