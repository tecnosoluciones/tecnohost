<?php

namespace Gravity_Flow\Gravity_Flow\Config;

use \Gravity_Flow;

/**
 * Class Gravity_Flow_JS_Config
 *
 * Contains methods to add configuration objects to JS files.
 *
 * @since 2.7.1-dev
 */
class JS_Config {

	/**
	 * Localize admin config object to the scripts-admin.js file.
	 */
	public function localize_admin_config() {
		$admin  = $this->admin_config();
		$shared = $this->shared_config();

		$config = array_merge( $admin, $shared );

		wp_localize_script( Gravity_Flow::ADMIN_JS, 'gflow_config', $config );
	}

	/**
	 * Localize theme config object to the theme-scripts.js file.
	 */
	public function localize_theme_config() {
		if ( ! gravity_flow()->look_for_shortcode() ) {
			return;
		}

		$theme  = $this->theme_config();
		$shared = $this->shared_config();

		$config = array_merge( $theme, $shared );

		wp_localize_script( Gravity_Flow::THEME_JS, 'gflow_config', $config );
	}

	/**
	 * Configuration values to be shared between both the theme and admin JS files. Useful for things like
	 * file paths, ajax endpoints, etc.
	 *
	 * @return array
	 */
	protected function shared_config() {
		$config = array(
			'script_debug'      => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET[ 'gform_debug' ] ) ? 1 : 0,
			'hmr_dev'           => defined( 'HMR_DEV' ) && HMR_DEV === true ? 1 : 0,
			'user_id'           => get_current_user_id(),
			'is_user_logged_in' => is_user_logged_in(),
			'public_path'       => trailingslashit( gravity_flow()->get_base_url() ) . 'assets/js/dist/',
		);

		/**
		 * Allows third-party code to modify the config array sent to both admin and theme JS.
		 *
		 * @param array $config
		 */
		return apply_filters( 'gravityflow_js_config_shared', $config );
	}

	/**
	 * Configuration values to be used within the admin JS file.
	 *
	 * @return array
	 */
	protected function admin_config() {
		$config = array();

		/**
		 * Allows third-party code to modify the config array sent to admin JS.
		 *
		 * @param array $config
		 */
		return apply_filters( 'gravityflow_js_config_admin', $config );
	}

	/**
	 * Configuration values to be used within the theme JS file.
	 *
	 * @return array
	 */
	protected function theme_config() {
		$config = array();

		/**
		 * Allows third-party code to modify the config array sent to theme JS.
		 *
		 * @param array $config
		 */
		return apply_filters( 'gravityflow_js_config_theme', $config );
	}

}
