<?php

// File generated from our OpenAPI spec

namespace StellarWP\Learndash\Stripe\Service\TestHelpers\Issuing;

/**
 * @phpstan-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 *
 * @license MIT
 * Modified by learndash on 05-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
/**
 * @psalm-import-type RequestOptionsArray from \Stripe\Util\RequestOptions
 */
class TransactionService extends \StellarWP\Learndash\Stripe\Service\AbstractService
{
    /**
     * Allows the user to capture an arbitrary amount, also known as a forced capture.
     *
     * @param null|array $params
     * @param null|RequestOptionsArray|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Issuing\Transaction
     */
    public function createForceCapture($params = null, $opts = null)
    {
        return $this->request('post', '/v1/test_helpers/issuing/transactions/create_force_capture', $params, $opts);
    }

    /**
     * Allows the user to refund an arbitrary amount, also known as a unlinked refund.
     *
     * @param null|array $params
     * @param null|RequestOptionsArray|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Issuing\Transaction
     */
    public function createUnlinkedRefund($params = null, $opts = null)
    {
        return $this->request('post', '/v1/test_helpers/issuing/transactions/create_unlinked_refund', $params, $opts);
    }

    /**
     * Refund a test-mode Transaction.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Issuing\Transaction
     */
    public function refund($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/test_helpers/issuing/transactions/%s/refund', $id), $params, $opts);
    }
}
