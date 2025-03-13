<?php
/**
 * Exception for 403 Forbidden responses
 *
 * @package Requests\Exceptions
 *
 * @license ISC
 * Modified by learndash on 05-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace StellarWP\Learndash\WpOrg\Requests\Exception\Http;

use StellarWP\Learndash\WpOrg\Requests\Exception\Http;

/**
 * Exception for 403 Forbidden responses
 *
 * @package Requests\Exceptions
 */
final class Status403 extends Http {
	/**
	 * HTTP status code
	 *
	 * @var integer
	 */
	protected $code = 403;

	/**
	 * Reason phrase
	 *
	 * @var string
	 */
	protected $reason = 'Forbidden';
}
