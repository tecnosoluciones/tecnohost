<?php
/**
 * Handles all hooks/filters related to the admin screens.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 *
 * @license GPL-2.0-or-later
 * Modified by learndash on 05-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace StellarWP\Learndash\StellarWP\Telemetry\Admin;

use StellarWP\Learndash\StellarWP\Telemetry\Contracts\Abstract_Subscriber;
use StellarWP\Learndash\StellarWP\Telemetry\Opt_In\Opt_In_Template;

/**
 * Handles all hooks/filters related to the admin screens.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
class Admin_Subscriber extends Abstract_Subscriber {

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', [ $this, 'maybe_enqueue_admin_assets' ] );

	}

	/**
	 * Registers required hooks to set up the admin assets.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function maybe_enqueue_admin_assets() {
		global $pagenow;

		if ( 'plugins.php' === $pagenow || $this->container->get( Opt_In_Template::class )->should_render() ) {
			$this->container->get( Resources::class )->enqueue_admin_assets();
		}
	}

}
