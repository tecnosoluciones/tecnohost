<?php
/**
 * Bulk Table Editor
 *
 * @package BulkTableEditor
 *
 * Plugin Name: Bulk Table Editor for WooCommerce
 * Plugin URI: https://woocommerce.com/products/bulk-table-editor-for-woocommerce/
 * Description: Enables bulk updating of product prices, stock, sale values, sku, tags and other.
 * Version: 2.2.19
 * Author: Consortia
 * Author URI: http://www.consortia.no/en/
 * Text Domain: woo-bulk-table-editor
 * Domain Path: /languages
 *
 * Tested up to: 6.0.1
 * Woo: 4703437:2514ffe2d12bf99178aacbc3755dc518
 * WC requires at least: 3.3
 * WC tested up to: 7.1.0
 *
 * Copyright: © 2018-2022 Consortia AS.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

global $wbte_version;
$wbte_version = '2.2.19';

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

register_activation_hook( __FILE__, 'wbte_install' );
register_deactivation_hook( __FILE__, 'wbte_deactivate' );

require_once dirname( __FILE__ ) . '/includes/class-wbte-woo.php';

/**
 * Install
 *
 * @return void
 */
function wbte_install() {

	global $wp_version;

	if ( version_compare( $wp_version, '4.1', '<' ) ) {
		wp_die( 'This plugin require WordPress 4.1 or higher.' );
	}

	$wbte_options_arr = array(
		'wbte_posts_per_page'             => '',
		'wbte_product_cat'                => '',
		'wbte_no_stock_management'        => '',
		'wbte_no_autofocus'               => '',
		'wbte_custom_price_1'             => '',
		'wbte_custom_price_1_header'      => '',
		'wbte_custom_price_1_visible'     => '',
		'wbte_custom_price_1_normal_calc' => '',
		'wbte_date_format_autofocus'      => '',
		'wbte_use_sku_main_page'          => '',
		'wbte_vendor_integration'         => '',
		'wbte_sku_count'                  => '',
		'wbte_product_status'             => '',
		'wbte_sku_delimiter'              => '',
		'wbte_integration'                => '',
		'wbte_disable_description'        => '',
		'wbte_table_sale_filter'          => '',
	);
	
	update_option( 'wbte_options', $wbte_options_arr );

	set_transient( 'wbte-admin-notice-activated', true );

	flush_rewrite_rules();
}

/**
 * Deactivate
 *
 * @return void
 */
function wbte_deactivate() {

	flush_rewrite_rules();
	
}

