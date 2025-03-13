<?php
/*
Plugin Name: Premium Addons PRO
Description: Premium Addons PRO Plugin Includes 36+ premium widgets & addons for Elementor Page Builder.
Plugin URI: https://premiumaddons.com
Version: 2.9.26
Author: Leap13
Elementor tested up to: 3.25.9
Elementor Pro tested up to: 3.25.4
Author URI: https://leap13.com/
Text Domain: premium-addons-pro
Domain Path: /languages
*/

/**
 * Checking if WordPress is installed
 */
if ( ! function_exists( 'add_action' ) ) {
	die( 'WordPress not Installed' ); // if WordPress not installed kill the page.
}

update_option( 'papro_license_status', 'valid' );
update_option( 'papro_license_key', 'GPL001122334455AA6677BB8899CC000' );

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No access of directly access.
}

define( 'PREMIUM_PRO_ADDONS_VERSION', '2.9.26' );
define( 'PREMIUM_PRO_ADDONS_STABLE_VERSION', '2.9.25' );
define( 'PREMIUM_PRO_ADDONS_URL', plugins_url( '/', __FILE__ ) );
define( 'PREMIUM_PRO_ADDONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PREMIUM_PRO_ADDONS_FILE', __FILE__ );
define( 'PREMIUM_PRO_ADDONS_BASENAME', plugin_basename( PREMIUM_PRO_ADDONS_FILE ) );
define( 'PAPRO_ITEM_NAME', 'Premium Addons PRO' );
define( 'PAPRO_STORE_URL', 'http://my.leap13.com' );
define( 'PAPRO_ITEM_ID', 361 );

update_option( 'papro_license_key','1415b451be1a13c283ba771ea52d38bb' );
update_option( 'papro_license_status','valid');
// If both versions are updated, run all dependencies.
update_option( 'papro_updated', 'true' );

/*
 * Load plugin core file.
 */
require_once PREMIUM_PRO_ADDONS_PATH . 'includes/class-papro-core.php';
