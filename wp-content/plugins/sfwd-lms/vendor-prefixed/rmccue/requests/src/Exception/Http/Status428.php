<?php
/**
 * Exception for 428 Precondition Required responses
 *
 * @link https://tools.ietf.org/html/rfc6585
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
 * Exception for 428 Precondition Required responses
 *
 * @link https://tools.ietf.org/html/rfc6585
 *
 * @package Requests\Exceptions
 */
final class Status428 extends Http {
	/**
	 * HTTP status code
	 *
	 * @var integer
	 */
	protected $code = 428;

	/**
	 * Reason phrase
	 *
	 * @var string
	 */
	protected $reason = 'Precondition Required';
}
