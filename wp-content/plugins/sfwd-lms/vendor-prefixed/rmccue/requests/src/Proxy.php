<?php
/**
 * Proxy connection interface
 *
 * @package Requests\Proxy
 * @since   1.6
 *
 * @license ISC
 * Modified by learndash on 05-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace StellarWP\Learndash\WpOrg\Requests;

use StellarWP\Learndash\WpOrg\Requests\Hooks;

/**
 * Proxy connection interface
 *
 * Implement this interface to handle proxy settings and authentication
 *
 * Parameters should be passed via the constructor where possible, as this
 * makes it much easier for users to use your provider.
 *
 * @see \StellarWP\Learndash\WpOrg\Requests\Hooks
 *
 * @package Requests\Proxy
 * @since   1.6
 */
interface Proxy {
	/**
	 * Register hooks as needed
	 *
	 * This method is called in {@see \WpOrg\Requests\Requests::request()} when the user
	 * has set an instance as the 'auth' option. Use this callback to register all the
	 * hooks you'll need.
	 *
	 * @see \StellarWP\Learndash\WpOrg\Requests\Hooks::register()
	 * @param \StellarWP\Learndash\WpOrg\Requests\Hooks $hooks Hook system
	 */
	public function register(Hooks $hooks);
}
