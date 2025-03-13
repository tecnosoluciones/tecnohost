<?php

namespace NobunaPlugins\Controllers;

use NBHelpers\Str;
use WPHelpers\Pages;
use WPHelpers\Package;
use NobunaPlugins\Model\HTMLResult;
use NobunaPlugins\Exceptions\NobunaError;

abstract class NobunaAdminUIBase {

//    abstract public static function Init();

//    abstract public static function Index();

//    abstract public static function ShouldAddCommonItems();
    
    protected static $_plugin_info = NULL;

    protected static $_styles_to_add = array();
    
    protected static $_scripts_to_add = array();
    
    /**
     * @return HTMLResult
     */
    protected static function GlobalResult() {
        return HTMLResult::GlobalResult();
    }

    protected static function SetGlobalError(NobunaError $error) {
        static::GlobalResult()->setError($error);
    }
    
    protected static function AddGlobalWarning($msg) {
        static::GlobalResult()->addWarning($msg);
    }

    protected static function AddGlobalSuccess($msg) {
        static::GlobalResult()->addSuccess($msg);
    }

    protected static function AddGlobalInfo($msg) {
        static::GlobalResult()->addInfo($msg);
    }

    protected static function NobunaPluginInfo() {
        if (static::$_plugin_info === NULL) {
            static::$_plugin_info = Package::GetPHPFileInfo(NOBUNA_PLUGINS_MAIN_FILE);
        }
        return static::$_plugin_info;
    }

    protected static function NobunaPluginVersion() {
        $info = static::NobunaPluginInfo();
        return $info['Version'];
    }

    private static function CommonLocalizedStrings() {
        return array(
            'UnknownError' => __nb('Unknown error 04'),
            'BrowserHTML5' => __nb('Your browser does not support HTML5. Please upgrade to get full functionality.'),
        );
    }

    protected static function MyLocalizedStrings() {
        return array();
    }

    private static function AddLocalizedStrings($handler_name) {
        $localized = array_merge(static::CommonLocalizedStrings(), static::MyLocalizedStrings());
        wp_localize_script($handler_name, 'nb_lang', $localized);
    }

    private static function AddCommonImagesPaths($handler_name) {
        $images = array(
            'loading_img_path' => static::GetImageUrl('loading.gif'),
            'miniloading_img_path' => static::GetImageUrl('miniloading.gif'),
            'loading_img' => '<img src="' . static::GetImageUrl('loading.gif') . '" />',
            'miniloading_img' => '<img src="' . static::GetImageUrl('miniloading.gif') . '" />',
        );
        wp_localize_script($handler_name, 'nb_imgs', $images);
    }

    public static function Run() {
        static::Init();
        add_action('admin_enqueue_scripts', array(get_called_class(), 'AddStyles'));
        add_action('admin_enqueue_scripts', array(get_called_class(), 'AddScripts'));
    }
    
    public static function AddStyles() {
        reset(static::$_styles_to_add);
        foreach(static::$_styles_to_add as $style) {
            static::EnqueueStyle($style);
        }
    }

    public static function AddScripts() {
        if (static::ShouldAddCommonItems()) {
            $handler_name = static::RegisterScript('common.js');
            static::AddLocalizedStrings($handler_name);
            static::AddCommonImagesPaths($handler_name);
            wp_enqueue_script($handler_name);
        }
        reset(static::$_scripts_to_add);
        foreach(static::$_scripts_to_add as $script) {
            static::EnqueueScript($script);
        }
    }
    
    public static function AddStyle($name) {
        static::$_styles_to_add[$name] = $name;
    }

    public static function EnqueueStyle($name) {
        $hname = static::GetHName($name);
        $path = static::StylesPath($name);
        wp_enqueue_style($hname, $path);
        return $hname;
    }

    protected static function AddScript($name) {
        static::$_scripts_to_add[$name] = $name;
    }
    
    protected static function EnqueueScript($name) {
        $hname = static::GetHName($name);
        $path = static::ScriptsPath($name);
        wp_enqueue_script($hname, $path);
        return $hname;
    }
    
    protected static function RegisterScript($name) {
        $hname = static::GetHName($name);
        $path = static::ScriptsPath($name);
        wp_register_script($hname, $path);
        return $hname;
    }
    
    protected static function StylesPath($name) {
        $path = static::GetWPContentURLPath() . '/plugins/' . NOBUNA_PLUGINS_DOMAIN . '/assets/css/' . $name . '?v=' . static::NobunaPluginVersion();
        return $path;
    }
    
    protected static function ScriptsPath($name) {
        $path = static::GetWPContentURLPath() . '/plugins/' . NOBUNA_PLUGINS_DOMAIN . '/assets/scripts/' . $name . '?v=' . static::NobunaPluginVersion();
        return $path;
    }
    
    protected static function GetHName($name) {
        $hname = 'nobuna_' . Str::CreateSlug($name);
        return $hname;
    }
    
    protected static function AddNBStyleIfMyPage() {
        if (static::IsMyPage()) {
            static::AddNBAdminStyle();
        }
    }

    protected static function IsMyPage() {
        return Pages::IsInAdminPage(static::SLUG);
    }

    protected static function AddNBAdminStyle() {
        static::AddStyle('admin.css');
    }

    protected static function AddAjaxMethod($action, $method) {
        $uri_base = basename($_SERVER['REQUEST_URI']);
        $paction = isset($_POST['action']) ? $_POST['action'] : NULL;
        if ($uri_base === 'admin-ajax.php' && $paction == $action) {
            add_action('wp_ajax_' . $action, array(get_called_class(), $method));
        }
    }

    protected static function AddPostMethod($action, $method) {
        $uri_base = basename($_SERVER['REQUEST_URI']);
        $paction = isset($_POST['action']) ? $_POST['action'] : NULL;
        if ($uri_base === 'admin-post.php' && $paction == $action) {
            add_action('admin_post_' . $action, array(get_called_class(), $method));
        }
    }

    protected static function GetMiniLoadingImageTag($id = NULL) {
        $url = static::GetImageUrl('miniloading.gif');
        if ($id === NULL) {
            $tag = sprintf('<img src="%s" />', $url);
        } else {
            $tag = sprintf('<img id="%s" src="%s" />', $id, $url);
        }
        return $tag;
    }

    protected static function GetWPContentURLPath() {
        return '/wp-content';
    }
    
    protected static function GetImageUrl($name) {
        return static::GetWPContentURLPath() . '/plugins/' . NOBUNA_PLUGINS_DOMAIN . '/assets/images/' . $name;
    }

    protected static function GetTableHTML($titles, $rows) {
        $out = '';
        $out .= '<table class="wp-list-table widefat plugins">' . PHP_EOL;
        $out .= ' <thead><tr>' . PHP_EOL;
        $pattern_title1 = '<th scope="col" class="manage-column column-primary">%s</th>' . PHP_EOL;
        $pattern_title = '<th scope="col" class="manage-column">%s</th>' . PHP_EOL;
        $i = 0;
        foreach($titles as $title) {
            $out .= sprintf($i === 0 ? $pattern_title1 : $pattern_title, $title);
            $i++;
        }
        $out .= '  </tr></thead>' . PHP_EOL;
        $out .= ' <tbody id="the-list">' . PHP_EOL;
        foreach($rows as $row) {
            $row_id = isset($row['id']) ? $row['id'] : '';
            $row_class = isset($row['class']) ? $row['class'] : '';
            $out .= sprintf('<tr id="%s" class="%s">' . PHP_EOL, $row_id, $row_class);
            foreach($row['columns'] as $column) {
                $column_id = isset($column['id']) ? $column['id'] : '';
                $column_content = isset($column['content']) ? $column['content'] : '';
                $out .= sprintf('<td id="%s">%s</td>' . PHP_EOL,  $column_id, $column_content);
            }
            $out .= '  </tr>' . PHP_EOL;
        }
        $out .= ' </tbody>' . PHP_EOL;
        $out .= '</table>' . PHP_EOL;
        return $out;
    }

}
