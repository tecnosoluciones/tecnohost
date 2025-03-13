<?php

namespace WPHelpers;

use Exception;
use NBHelpers\File;
use NobunaPlugins\Exceptions\UnableToCreateDirectoryException;
use NobunaPlugins\Exceptions\UnableToDetectPackageException;

use NobunaPlugins\Exceptions\NobunaError;
use NobunaPlugins\Model\NobunaGlobals;

class Package {
    
    public $path_to_zip = NULL;
    
    public $info_file = NULL;
    public $info;
    
    public $main_name = '';
    public $installed = FALSE;
    
    public $error = FALSE;
    public $error_message = '';
    
    private $_single_file = FALSE;
    public $is_plugin = FALSE;
    public $is_theme = FALSE;
    public $type = NULL;
    
    public function __construct($path_to_zip) {
        $this->path_to_zip = $path_to_zip;
    }
    
    /**
     * @global type $wp_filesystem
     * @throws Exception
     * @throws UnableToCreateDirectoryException
     * @throws UnableToDetectPackageException
     */
    public function check() {
        $unzip_folder = $this->unzipPackage();
        try {
            $res = $this->definePackageType($unzip_folder);
            $this->installed = $this->isInstalled();
        } catch(Exception $e) {
            throw new UnableToDetectPackageException($this->path_to_zip, '', 0, $e);
        }
        File::DeleteDirOrFile($unzip_folder);
        if($res === FALSE) {
            throw new UnableToDetectPackageException($this->path_to_zip);
        }
    }

    /**
     * @param string $package_folder
     * @return boolean
     */
    private function definePackageType($package_folder) {
        $main_folders = glob(sprintf('%s/*', $package_folder), GLOB_ONLYDIR);
        if(empty($main_folders)) {
            $this->_single_file = TRUE;
            $main_folders = array($package_folder);
            $this->is_theme = FALSE;
        } else {
            // Check if theme
            $style_file0 = sprintf('%s/style.css', $package_folder);
            $style_file1 = sprintf('%s/style.css', $main_folders[0]);
            $info = static::GetThemeInfo($style_file0);
            $info = $info !== NULL ? $info : static::GetThemeInfo($style_file1);
            if($info !== NULL) {
                $this->main_name = basename($main_folders[0]);
                $this->is_theme = TRUE;
                $this->info_file = str_replace(ABSPATH, '', $style_file1);
                $this->info = $info;
                $this->type = NOBUNA_TYPE_THEME;
                return TRUE;
            }
        }
        
        // check if plugin
        $info = static::GetPluginInfo($main_folders[0]);

        if($info !== NULL) {
            $info_file = static::GetPluginFile($main_folders[0]);
            $this->main_name = basename($main_folders[0]);
            if($this->_single_file === TRUE) {
                $this->main_name = str_ireplace('.php', '', basename($info_file));
            }
            $this->info_file = $info_file;
            $this->info = $info;
            $this->is_plugin = TRUE;
            $this->type = NOBUNA_TYPE_PLUGIN;
            return TRUE;
        }

        $this->error = TRUE;
        $this->error_message = 'Package files not found';
        return FALSE;
        
    }
    
    public function isInstalled() {
        return static::Installed($this->main_name, $this->type);
    }
    
    public static function Installed($main_name, $type) {
        $d = static::GetRootInstallFolder($type);
        if($d === NULL) {
            return FALSE;
        }
        $path = sprintf('%s/%s', $d, $main_name);
        return file_exists($path);
    }
    
    public static function InstalledVersion($main_name, $type) {
        if(static::Installed($main_name, $type) === FALSE) { return NULL; }
        $d = static::GetRootInstallFolder($type);
        if($type === 'theme') {
            $info = static::GetThemeInfo(sprintf('%s/%s/style.css', $d, $main_name));
        } else {
            $info = static::GetPluginInfo(sprintf('%s/%s', $d, $main_name));
        }
        return $info === NULL ? NULL : $info['Version'];
    }
    
    public static function GetInstalledMainFilePath($main_name, $type) {
        if(static::Installed($main_name, $type) === FALSE) { return NULL; }
        $d = static::GetRootInstallFolder($type);
        if($type === 'theme') {
            return sprintf('%s/%s/style.css', $d, $main_name);
        }
        if($type === 'plugin') {
            return static::GetPluginFile(sprintf('%s/%s', $d, $main_name));
        }
        return NULL;
    }
    
    protected static function GetRootInstallFolder($type) {
        $d = NULL;
        switch($type) {
            case 'theme':
                $d = get_theme_root();
                break;
            case 'plugin':
                $d = WP_PLUGIN_DIR;
                break;
        }
        return $d;
    }
    
    public static function GetThemeInfo($file) {
        if(!file_exists($file)) {
            return NULL;
        }
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $headers = array(
            'Name' => 'Theme Name',
            'SkinName' => 'Skin Name',
            'Version' => 'Version',
        );
        $data = get_file_data($file, $headers);
        if(empty($data['Name']) && !empty($data['SkinName'])) {
            $data['Name'] = $data['SkinName'];
        }
        if(empty($data['Name'])) {
            return NULL;
        }
        return $data;
    }
    
    public static function GetPluginInfo($directory) {
        $plugin_file = static::GetPluginFile($directory);
        if($plugin_file !== NULL) {
            return static::GetPHPFileInfo($plugin_file);
        }
        return NULL;
    }
    
    public static function GetPluginFile($directory) {
        if(is_file($directory)) {
            return (static::GetPHPFileInfo($directory) !== NULL) ? $directory : NULL;
        }
        $php_files = glob(sprintf('%s*php', trailingslashit($directory)));
        foreach($php_files as $php_file) {
            $data = static::GetPHPFileInfo($php_file);
            if($data !== NULL) {
                return $php_file;
            }
        }
        return NULL;
    }
    
    protected static $_plugins_info = array();
    public static function GetPHPFileInfo($file) {
        if(!isset(static::$_plugins_info[$file])) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            static::$_plugins_info[$file] = NULL;
            $info = get_plugin_data($file, false, false);
            if (!empty($info['Name'])) {
                static::$_plugins_info[$file] = $info;
            }
        }
        return static::$_plugins_info[$file];
    }

    public static function GetInstalledPlugins() {
        require_once (ABSPATH . '/wp-admin/includes/plugin.php');
        return get_plugins();
    }
    
    /**
     * @global \WPHelpers\type $wp_filesystem
     * @return string
     * @throws Exception
     * @throws UnableToCreateDirectoryException
     * @throws UnableToDetectPackageException
     */
    private function unzipPackage() {
        if(!file_exists($this->path_to_zip)) {
            throw new Exception('The file does not exist');
        }
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            WP_Filesystem();
        }

        $basename = basename($this->path_to_zip);

        if (static::CreateTmpDir() === FALSE) {
            throw new UnableToCreateDirectoryException(static::TmpPath());
        }
        $unzip_folder = static::TmpPath($basename);
        File::DeleteDirOrFile($unzip_folder);
        if (static::CreateTmpDir($basename) === FALSE) {
            throw new UnableToCreateDirectoryException($unzip_folder);
        }

        $res = unzip_file($this->path_to_zip, $unzip_folder);
        if ($res === FALSE || is_wp_error($res)) {
            if(is_wp_error($res)) {
                /* @var $res \WP_Error */
                NobunaGlobals::SetGlobalError(new NobunaError($res->get_error_code(), $res->get_error_message(), $res->get_error_data()));
            }
            throw new UnableToDetectPackageException($basename);
        }
        
        return $unzip_folder;
    }
    
    public function copyToTmpDir() {
        static::CreateTmpDir($this->main_name);
        $path = sprintf('%s/%s.zip', static::TmpPath($this->main_name), $this->main_name);
        File::DeleteDirOrFile($path);
        copy($this->path_to_zip, $path);
        return $path;
    }
    
    private static function CreateTmpDir($append_folder = NULL) {
        return File::CreateDirectory(static::TmpPath($append_folder));
    }

    private static function TmpPath($append_folder = NULL) {
        return NOBUNA_TMP_DIRECTORY . ($append_folder !== NULL ? '/' . $append_folder : '');
    }
    
}
