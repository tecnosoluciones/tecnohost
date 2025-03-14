<?php

// File generated from our OpenAPI spec

namespace StellarWP\Learndash\Stripe\Issuing;

/**
 * An issuing token object is created when an issued card is added to a digital wallet. As a <a href="https://stripe.com/docs/issuing">card issuer</a>, you can <a href="https://stripe.com/docs/issuing/controls/token-management">view and manage these tokens</a> through Stripe.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object's type. Objects of the same type share the same value.
 * @property string|\StellarWP\Learndash\Stripe\Issuing\Card $card Card associated with this token.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|string $device_fingerprint The hashed ID derived from the device ID from the card network associated with the token.
 * @property null|string $last4 The last four digits of the token.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property string $network The token service provider / card network associated with the token.
 * @property null|\StellarWP\Learndash\Stripe\StripeObject $network_data
 * @property int $network_updated_at Time at which the token was last updated by the card network. Measured in seconds since the Unix epoch.
 * @property string $status The usage state of the token.
 * @property null|string $wallet_provider The digital wallet for this token, if one was used.
 *
 * @license MIT
 * Modified by learndash on 05-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
class Token extends \StellarWP\Learndash\Stripe\ApiResource
{
    const OBJECT_NAME = 'issuing.token';

    use \StellarWP\Learndash\Stripe\ApiOperations\All;
    use \StellarWP\Learndash\Stripe\ApiOperations\Retrieve;
    use \StellarWP\Learndash\Stripe\ApiOperations\Update;

    const NETWORK_MASTERCARD = 'mastercard';
    const NETWORK_VISA = 'visa';

    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_REQUESTED = 'requested';
    const STATUS_SUSPENDED = 'suspended';

    const WALLET_PROVIDER_APPLE_PAY = 'apple_pay';
    const WALLET_PROVIDER_GOOGLE_PAY = 'google_pay';
    const WALLET_PROVIDER_SAMSUNG_PAY = 'samsung_pay';
}
