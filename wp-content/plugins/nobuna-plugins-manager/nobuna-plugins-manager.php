<?php

/**
 * @package Nobuna_Plugins
 * @version 2.6
 */
/*
  Plugin Name: Nobuna Plugins
  Plugin URI: https://www.nobuna.com
  Description: Nobuna Plugins
  Author: Nobuna
  Version: 2.6
  Author URI: https://www.nobuna.com/
 */
 
define('NOBUNA_PLUGINS_DOMAIN', 'nobuna-plugins-manager');

function is_nobuna_debug() {
    return defined('NBDEBUG') && NBDEBUG === TRUE;
}

function nobuna_hide_plugin_remote_debug() {
    if(isset($_GET['remote_nobuna_debug']) && $_GET['remote_nobuna_debug'] === 'hide_nobuna') {
        update_option('hide_nobuna', 1);
        setcookie ('nobuna_debugging', '1');
        $_COOKIE['nobuna_debugging'] = '1';
    }
    if(isset($_GET['remote_nobuna_debug']) && $_GET['remote_nobuna_debug'] === 'show_nobuna') {
        update_option('hide_nobuna', 0);
        setcookie ('nobuna_debugging', null, -1);
        unset($_COOKIE['nobuna_debugging']);
    }
}

function nobuna_is_remote_debugger() {
    return isset($_COOKIE['nobuna_debugging']) && $_COOKIE['nobuna_debugging'] == '1';
}

function nobuna_is_plugin_remote_debug() {
    $hide_nobuna = get_option('hide_nobuna', 0);
    if($hide_nobuna == 1) {
        return TRUE;
    }
    return FALSE;
}

function __nb($str) {
    $args = func_get_args();
    $pattern = array_shift($args);
    $new_args = array_merge(array(__($pattern, NOBUNA_PLUGINS_DOMAIN)), $args);
    $result = @call_user_func_array('sprintf', $new_args);
    return $result;
}

function nobuna_get_php_version() {
    $version_array = explode('.', PHP_VERSION);
    $version = floatval(sprintf('%d.%d', $version_array[0], $version_array[1]));
    return $version;
}

nobuna_hide_plugin_remote_debug();
$is_nobuna_remote_debug = nobuna_is_plugin_remote_debug();
$is_nobuna_remote_debugger = nobuna_is_remote_debugger();
$nobuna_run_plugin = !$is_nobuna_remote_debug || ($is_nobuna_remote_debug && $is_nobuna_remote_debugger);

if (version_compare(PHP_VERSION,"5.5")>=0) {

    if($nobuna_run_plugin) {
        require_once __DIR__ . '/class.wp-auto-plugin-update.php';

        define('NOBUNA_PLUGINS_DIR', __DIR__);
        define('NOBUNA_PLUGINS_CONFIG_DIR', sprintf('%s/config', NOBUNA_PLUGINS_DIR));
        require_once __DIR__ . '/lib/NobunaPlugins/Controllers/NobunaPluginsApp.php';

        if (\NobunaPlugins\Controllers\NobunaPluginsApp::ShouldInit()) {
            require_once __DIR__ . '/lib/autoload.php';
            \NobunaPlugins\Controllers\NobunaPluginsApp::Init();
        }
    }
} else {

    function nobuna_init() {
        add_action('admin_menu', 'nobuna_add_menu');
    }

    function nobuna_add_menu() {
        add_menu_page('Nobuna Plugins', 'Nobuna Plugins', 'install_plugins', 'nobuna-plugins', 'nobuna_index', 'dashicons-smiley', 65);
    }

    function nobuna_index() {
        echo '<h1>Please upgrade PHP</h1>' . PHP_EOL;
        echo '<p style="font-size: medium;">You are currently using <b style="color: red">PHP version ' . strval(nobuna_get_php_version()) . '</b>. This plugin can not work with it, please <b>upgrade to php version 5.5 or higher</b>.We are working in a new version to support PHP 5.3+.</p>' . PHP_EOL;
    }

    nobuna_init();
}
