<?php
/**
 * Capability interface declaring the known capabilities.
 *
 * @package Requests\Utilities
 *
 * @license ISC
 * Modified by learndash on 05-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace StellarWP\Learndash\WpOrg\Requests;

/**
 * Capability interface declaring the known capabilities.
 *
 * This is used as the authoritative source for which capabilities can be queried.
 *
 * @package Requests\Utilities
 */
interface Capability {

	/**
	 * Support for SSL.
	 *
	 * @var string
	 */
	const SSL = 'ssl';

	/**
	 * Collection of all capabilities supported in Requests.
	 *
	 * Note: this does not automatically mean that the capability will be supported for your chosen transport!
	 *
	 * @var string[]
	 */
	const ALL = [
		self::SSL,
	];
}
