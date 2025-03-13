<?php

namespace NobunaPlugins\Controllers;

use Exception;
use NBHelpers\Config;
use NBHelpers\File;
use WPHelpers\WarningRenderer;
use WPHelpers\Package;
use NobunaPlugins\Updaters\PluginUpgrader;
use NobunaPlugins\Updaters\ThemeUpgrader;
use NobunaPlugins\Model\NobunaRequest;
use NobunaPlugins\Model\NobunaProduct;
use NobunaPlugins\Exceptions\UnableToDetectPackageException;
use NobunaPlugins\Exceptions\UnableToInstallPackageException;
use NobunaPlugins\Updaters\NobunaInstallerSkin;
use File_Upload_Upgrader;
use Plugin_Installer_Skin;
use Theme_Installer_Skin;

class NobunaPluginsApp {

    private static $shared = NULL;
    
    public static function Init() {
        if (!static::ShouldInit()) { return; }
        $app = \NobunaPlugins\Controllers\NobunaPluginsApp::Shared();
        $app->registerHooks();
        NobunaAdminUI::Init();
    }
    
    public static function ShouldInit() {
        return is_admin();
    }
    
    /**
     * @return \NobunaPlugins\Controllers\NobunaPluginsApp
     */
    public static function Shared() {
        if (static::$shared === NULL) {
            static::$shared = new NobunaPluginsApp();
        }
        return static::$shared;
    }

    private function __construct() {
        Config::RequireConfigFiles(NOBUNA_PLUGINS_CONFIG_DIR);
    }

    public function registerHooks() {
        add_action('init', array(__CLASS__, 'initHook'));
        add_action('plugins_loaded', array(__CLASS__, 'pluginsLoadedHook'));
        add_action('admin_notices', array(__CLASS__, 'adminNoticesHook'));
        add_action('load-update.php', array(__CLASS__, 'RegisterMoreHooks'));
    }

    public static function RegisterMoreHooks() {
        add_action('admin_action_upload-theme', array(__CLASS__, 'UpdateTheme'));
        add_action('admin_action_upload-plugin', array(__CLASS__, 'UpdatePlugin'));
    }

    public static function InstallPackage(NobunaProduct $product) {
        if(!$product->has_download()) {
            throw new \Exception('The product does not have a valid download');
        }
        $download = $product->getMyVersionDownload();
        $package = new Package(sprintf('%s/%s', ABSPATH, $download->file_path));
        try {
            $package->check();
        } catch(Exception $e) {
            throw new UnableToDetectPackageException($product->product_name, '', 0, $e);
        }
        
        $result = FALSE;
        if($package->is_plugin) {
            $result = static::UpdatePlugin('', TRUE, $package, $product);
        }
        if($package->is_theme) {
            $result = static::UpdateTheme('', TRUE, $package, $product);
        }
        
        if($result !== TRUE) {
            throw new UnableToInstallPackageException($product->product_name);
        }
        
        return $result;
    }
    
    public static function UpdatePlugin($uselessVar = '', $simulating = FALSE, $packageObject = NULL, $nobunaProduct = NULL) {
        if($simulating === TRUE) {
            ob_start();
        }
        
        include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        if (!current_user_can('upload_plugins')) {
            if($simulating === FALSE) {
                wp_die(esc_html__('Sorry, you are not allowed to install plugins on this site.'));
            } else {
                return NULL;
            }
        }

        if($simulating === FALSE) {
            check_admin_referer('plugin-upload');
            $file_upload = new File_Upload_Upgrader('pluginzip', 'package');
            $title = __('Upload Plugin');
            $parent_file = 'plugins.php';
            $submenu_file = 'plugin-install.php';
            require_once( ABSPATH . 'wp-admin/admin-header.php' );

            $title = sprintf(__('Installing Plugin from uploaded file: %s'), esc_html(basename($file_upload->filename)));
            $nonce = 'plugin-upload';
            $url = add_query_arg(array('package' => $file_upload->id), 'update.php?action=upload-plugin');
            $type = 'upload'; // Install plugin type, From Web or an Upload.
            
            $skin = new Plugin_Installer_Skin(compact('type', 'title', 'nonce', 'url'));
            $package = $file_upload->package;
        } else {
            $skin = new NobunaInstallerSkin;
            $package = $packageObject->copyToTmpDir();
        }
        
        $upgrader = new PluginUpgrader($skin);
        if($nobunaProduct !== NULL) {
            $upgrader->set_nobuna_product($nobunaProduct, $packageObject);
        }
        $result = $upgrader->install($package);
        
        if($simulating === FALSE) {
            if ($result || is_wp_error($result)) {
                $file_upload->cleanup();
            }
            
            include( ABSPATH . 'wp-admin/admin-footer.php' );

            exit();
        }
        
        @unlink($package);
        @ob_end_clean();
        
        return $result;
    }

    public static function UpdateTheme($uselessVar = '', $simulating = FALSE, $packageObject = NULL, $nobunaProduct = NULL) {
        if($simulating === TRUE) {
            ob_start();
        }
        
        include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        if (!current_user_can('upload_themes')) {
            if($simulating === FALSE) {
                wp_die(esc_html__('Sorry, you are not allowed to install themes on this site.'));
            }
            return NULL;
        }

        if($simulating === FALSE) {
            check_admin_referer('theme-upload');

            $file_upload = new File_Upload_Upgrader('themezip', 'package');

            wp_enqueue_script('customize-loader');

            $title = __('Upload Theme');
            $parent_file = 'themes.php';
            $submenu_file = 'theme-install.php';

            require_once( ABSPATH . 'wp-admin/admin-header.php' );

            $title = sprintf(__('Installing Theme from uploaded file: %s'), esc_html(basename($file_upload->filename)));
            $nonce = 'theme-upload';
            $url = add_query_arg(array('package' => $file_upload->id), 'update.php?action=upload-theme');
            $type = 'upload'; // Install plugin type, From Web or an Upload.
            $skin = new Theme_Installer_Skin(compact('type', 'title', 'nonce', 'url'));
            $package = $file_upload->package;
        } else {
            $skin = new NobunaInstallerSkin;
            $package = $packageObject->path_to_zip;
        }

        $upgrader = new ThemeUpgrader($skin);
        if($nobunaProduct !== NULL) {
            $upgrader->set_nobuna_product($nobunaProduct, $packageObject);
        }
        $result = $upgrader->install($package);

        if($simulating === FALSE) {
            if ($result || is_wp_error($result)) {
                $file_upload->cleanup();
            }

            include( ABSPATH . 'wp-admin/admin-footer.php' );

            exit();
        }

        @ob_end_clean();
       
        return TRUE;
    }

    public static function initHook() {
        $installer = new NobunaInstaller();
        $installer->run();
    }

    public static function adminNoticesHook() {
        static::NoticeCanUseRequestLibraries();
        static::NoticeCanCreateBackupsFolder();
    }

    private static function NoticeCanUseRequestLibraries() {
        if(NobunaRequest::EnabledMethod() === NULL) {
            $msg = __nb('Nobuna Plugins Error: allow_url_fopen is disabled in server, and curl extension is not enabled. Please '
                    . 'install curl extension or set allow_url_fopen=1 in php.ini file.');
            echo WarningRenderer::GetErrorMessage($msg, FALSE);
        }
    }
    
    private static function NoticeCanCreateBackupsFolder() {
        if (!static::CanCreateBackupsFolder()) {
            $folder_array = static::GetBackupsFolderPath();
            echo WarningRenderer::GetErrorMessage(
                    __nb('Nobuna Plugins Error: The plugin directory is not writable: %s', $folder_array['basedir']));
        }
    }
    
    /**
     * 
     * @param bool $create_dir
     * @return array
     */
    public static function GetBackupsFolderPath($create_dir = FALSE) {
        return static::GetFolderPath('backup', $create_dir);
    }

    /**
     * 
     * @param bool $create_dir
     * @return array
     */
    public static function GetDownloadsFolderPath($create_dir = FALSE) {
        return static::GetFolderPath('download', $create_dir);
    }

    private static function GetFolderPath($type, $create_dir = FALSE) {
        if($type === 'backup') {
            $folder = NOBUNA_BACKUP_DIRECTORY_PATH;
            $url = NOBUNA_BACKUP_DIRECTORY_URL;
        } else if($type === 'download') {
            $folder = NOBUNA_DOWNLOAD_DIRECTORY_PATH;
            $url = NOBUNA_DOWNLOAD_DIRECTORY_URL;
        }
        $res = array(
            'path' => $folder,
            'url' => $url,
            'subdir' => '',
            'basedir' => $folder,
            'baseurl' => $url,
        );
        File::CreateDirectory($res['basedir']);
        if($create_dir === TRUE) {
            File::CreateDirectory($res['path']);
        }
        return $res;
    }

    private static function CanCreateBackupsFolder() {
        $folder_array = static::GetBackupsFolderPath();
        $basedir = $folder_array['basedir'];
        return is_dir($basedir) && is_writable($basedir);
    }

    public static function pluginsLoadedHook() {
        load_plugin_textdomain(NOBUNA_PLUGINS_DOMAIN, FALSE, NOBUNA_PLUGINS_LOCALE_FOLDER);
    }

}
