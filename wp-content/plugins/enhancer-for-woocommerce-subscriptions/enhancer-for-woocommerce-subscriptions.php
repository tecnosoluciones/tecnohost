<?php

/**
 * Plugin Name: Enhancer for WooCommerce Subscriptions
 * Description: Additional features for WooCommerce Subscriptions such as price updation for existing users, separate shipping cycle, cancel delay, auto-renewal reminder, etc.
 * Version: 4.0.0
 * Author: Flintop
 * Author URI: https://flintop.com
 * Text Domain: enhancer-for-woocommerce-subscriptions
 * Domain Path: /languages
 * Woo: 5834751:b0f115cc74f785a3e38e8aa056cebc4f
 * Tested up to: 6.2.2
 * WC tested up to: 7.8.0
 * WC requires at least: 3.5.0
 * WCS tested up to: 5.1.3
 * WCS requires at least: 3.0.14
 * Copyright: © 2023 Flintop
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
defined( 'ABSPATH' ) || exit;

/**
 * Define our plugin constants.
 */
define( 'ENR_FILE', __FILE__ );
define( 'ENR_DIR', plugin_dir_path( ENR_FILE ) );
define( 'ENR_URL', untrailingslashit( plugins_url( '/', ENR_FILE ) ) );
define( 'ENR_PREFIX', '_enr_' );

/**
 * Add HPOS support.
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * Initiate Plugin Core class.
 * 
 * @class ENR_For_WC_Subscriptions
 * @package Class
 */
final class ENR_For_WC_Subscriptions {

	/**
	 * Plugin version.
	 */
	const VERSION = '4.0.0';

	/**
	 * Required WC version.
	 */
	const REQ_WC_VERSION = '3.5.0';

	/**
	 * Required WC Subscriptions version.
	 */
	const REQ_WCS_VERSION = '3.0.1';

	/**
	 * The single instance of the class.
	 */
	protected static $instance = null;

	/**
	 * ENR_For_WC_Subscriptions constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		add_action( 'admin_notices', array( $this, 'plugin_dependencies_notice' ) );
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'enhancer-for-woocommerce-subscriptions' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'enhancer-for-woocommerce-subscriptions' ), '1.0' );
	}

	/**
	 * Main ENR_For_WC_Subscriptions Instance.
	 * Ensures only one instance of ENR_For_WC_Subscriptions is loaded or can be loaded.
	 * 
	 * @return ENR_For_WC_Subscriptions - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get plugin version.
	 * 
	 * @return string
	 */
	public function get_version() {
		return self::VERSION;
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return ENR_DIR . 'templates/';
	}

	/**
	 * Check whether the plugin dependencies met.
	 * 
	 * @return bool|string True on Success
	 */
	private function plugin_dependencies_met() {
		// WC Subscriptions check.
		if ( ! class_exists( 'WC_Subscriptions' ) || version_compare( WC_Subscriptions::$version, self::REQ_WCS_VERSION, '<' ) ) {
			if ( ! class_exists( 'WC_Subscriptions' ) ) {
				return wp_kses_post( __( '<strong>Enhancer for WooCommerce Subscriptions is inactive.</strong> The <a href="http://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">WooCommerce Subscriptions plugin</a> must be active for Enhancer for WooCommerce Subscriptions to work. Please install & activate WooCommerce Subscriptions.', 'enhancer-for-woocommerce-subscriptions' ) );
			} else {
				// translators: %s: required WCS version
				return sprintf( wp_kses_post( __( '<strong>Enhancer for WooCommerce Subscriptions is inactive.</strong> <a href="http://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">WooCommerce Subscriptions</a> plugin version <strong>%s</strong> or higher must be active for Enhancer for WooCommerce Subscriptions to work. Please update WooCommerce Subscriptions plugin and check.', 'enhancer-for-woocommerce-subscriptions' ) ), self::REQ_WCS_VERSION );
			}
		}

		if ( version_compare( get_option( 'woocommerce_db_version' ), WC_Subscriptions::$wc_minimum_supported_version, '<' ) ) {
			return false;
		}

		// WC check.
		if ( ! function_exists( 'WC' ) ) {
			$install_url = wp_nonce_url( add_query_arg( array( 'action' => 'install-plugin', 'plugin' => 'woocommerce' ), admin_url( 'update.php' ) ), 'install-plugin_woocommerce' );
			// translators: 1$-2$: opening and closing <strong> tags, 3$-4$: link tags, takes to woocommerce plugin on wp.org, 5$-6$: opening and closing link tags, leads to plugins.php in admin
			return sprintf( esc_html__( '%1$sEnhancer for WooCommerce Subscriptions is inactive.%2$s The %3$sWooCommerce plugin%4$s must be active for Enhancer for WooCommerce Subscriptions to work. Please %5$sinstall & activate WooCommerce &raquo;%6$s', 'enhancer-for-woocommerce-subscriptions' ), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . esc_url( $install_url ) . '">', '</a>' );
		}

		return true;
	}

	/**
	 * When WP has loaded all plugins, check whether the plugin is compatible with the present environment and load our files.
	 */
	public function plugins_loaded() {
		if ( true !== $this->plugin_dependencies_met() ) {
			return;
		}

		$this->include_files();
		$this->init_hooks();
		$this->load_plugin_textdomain();

		/**
		 * Trigger after the plugin is loaded.
		 * 
		 * @since 1.0
		 */
		do_action( 'enr_loaded' );
	}

	/**
	 * Output a admin notice when plugin dependencies not met.
	 */
	public function plugin_dependencies_notice() {
		$return = $this->plugin_dependencies_met();

		if ( true !== $return && $return && current_user_can( 'activate_plugins' ) ) {
			$dependency_notice = $return;
			printf( '<div class="error"><p>%s</p></div>', wp_kses_post( $dependency_notice ) );
		}
	}

	/**
	 * Is frontend request ?
	 *
	 * @return bool
	 */
	private function is_frontend() {
		if ( function_exists( 'wcs_is_frontend_request' ) ) {
			return wcs_is_frontend_request();
		}

		return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
	}

	/**
	 * Include required core files.
	 */
	private function include_files() {
		//Class autoloader.
		include_once('includes/class-enr-autoload.php');

		//Abstract classes.
		include_once('includes/abstracts/abstract-enr-subscribe-now.php');

		//Core functions.
		include_once('includes/enr-core-functions.php');

		//Core classes.
		include_once('includes/class-enr-post-types.php');
		include_once('includes/class-enr-install.php');
		include_once('includes/class-enr-emails.php');
		include_once('includes/class-enr-ajax.php');
		include_once('includes/privacy/class-enr-privacy.php');
		include_once('includes/class-enr-subscriptions-manager.php');

		if ( is_admin() ) {
			include_once('includes/admin/class-enr-admin.php');
			include_once('includes/admin/class-enr-admin-post-types.php');
		}

		if ( $this->is_frontend() ) {
			include_once('includes/class-enr-subscriptions-limiter.php');
			include_once('includes/class-enr-cart-level-subscribe-now.php');
			include_once('includes/class-enr-product-level-subscribe-now.php');
			include_once('includes/class-enr-form-handler.php');
			include_once('includes/enr-template-hooks.php');
		}
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		register_activation_hook( ENR_FILE, array( 'ENR_Install', 'install' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_script' ), 11 );
	}

	/**
	 * Load Localization files.
	 */
	public function load_plugin_textdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}

		/**
		 * Get the plugin text domain.
		 * 
		 * @since 1.0 
		 */
		$locale = apply_filters( 'plugin_locale', $locale, 'enhancer-for-woocommerce-subscriptions' );

		unload_textdomain( 'enhancer-for-woocommerce-subscriptions' );
		load_textdomain( 'enhancer-for-woocommerce-subscriptions', WP_LANG_DIR . '/enhancer-for-woocommerce-subscriptions/enhancer-for-woocommerce-subscriptions-' . $locale . '.mo' );
		load_plugin_textdomain( 'enhancer-for-woocommerce-subscriptions', false, dirname( plugin_basename( ENR_FILE ) ) . '/languages' );
	}

	/**
	 * Perform script localization in frontend.
	 */
	public function frontend_script() {
		global $wp, $post;

		$product_id = $post && 'product' === get_post_type( $post ) ? $post->ID : false;
		$product    = ! empty( $product_id ) ? wc_get_product( $product_id ) : false;

		wp_register_script( 'enr-frontend', ENR_URL . '/assets/js/frontend.js', array( 'jquery' ), _enr()->get_version() );
		wp_register_style( 'enr-frontend', ENR_URL . '/assets/css/frontend.css', array(), _enr()->get_version() );
		wp_localize_script( 'enr-frontend', 'enr_frontend_params', array(
			'ajax_url'                     => admin_url( 'admin-ajax.php' ),
			'is_switch_request'            => _enr_is_switch_request(),
			'subscribe_now_nonce'          => wp_create_nonce( 'enr-subscribe-now-handle' ),
			'subscribe_now_button_text'    => get_option( 'woocommerce_subscriptions_add_to_cart_button_text' ),
			'single_add_to_cart_text'      => $product ? $product->single_add_to_cart_text() : __( 'Add to cart', 'enhancer-for-woocommerce-subscriptions' ),
			'hide_variable_limited_notice' => $product && 'no' !== ENR_Subscriptions_Limiter::get_product_limitation( $product ) && 'variant-level' === get_post_meta( $product_id, '_enr_variable_subscription_limit_level', true ) ? 'yes' : '',
		) );
		wp_enqueue_script( 'enr-frontend' );
		wp_enqueue_style( 'enr-frontend' );
	}

}

/**
 * Main instance of ENR_For_WC_Subscriptions.
 * Returns the main instance of ENR_For_WC_Subscriptions.
 *
 * @return ENR_For_WC_Subscriptions
 */
function _enr() {
	return ENR_For_WC_Subscriptions::instance();
}

/**
 * Run Enhancer for WooCommerce Subscriptions
 */
_enr();
