<?php
/**
 * Uninstall page
 *
 * @package BulkTableEditor
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'wbte_options' );
