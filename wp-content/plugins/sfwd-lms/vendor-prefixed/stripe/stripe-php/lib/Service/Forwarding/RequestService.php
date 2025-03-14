<?php

// File generated from our OpenAPI spec

namespace StellarWP\Learndash\Stripe\Service\Forwarding;

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
class RequestService extends \StellarWP\Learndash\Stripe\Service\AbstractService
{
    /**
     * Lists all ForwardingRequest objects.
     *
     * @param null|array $params
     * @param null|RequestOptionsArray|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Collection<\Stripe\Forwarding\Request>
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/forwarding/requests', $params, $opts);
    }

    /**
     * Creates a ForwardingRequest object.
     *
     * @param null|array $params
     * @param null|RequestOptionsArray|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Forwarding\Request
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/forwarding/requests', $params, $opts);
    }

    /**
     * Retrieves a ForwardingRequest object.
     *
     * @param string $id
     * @param null|array $params
     * @param null|RequestOptionsArray|\StellarWP\Learndash\Stripe\Util\RequestOptions $opts
     *
     * @throws \StellarWP\Learndash\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \StellarWP\Learndash\Stripe\Forwarding\Request
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/forwarding/requests/%s', $id), $params, $opts);
    }
}
