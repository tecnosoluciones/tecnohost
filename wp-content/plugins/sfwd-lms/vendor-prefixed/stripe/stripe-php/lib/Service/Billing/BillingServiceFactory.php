<?php

// File generated from our OpenAPI spec

namespace StellarWP\Learndash\Stripe\Service\Billing;

/**
 * Service factory class for API resources in the Billing namespace.
 *
 * @property MeterEventAdjustmentService $meterEventAdjustments
 * @property MeterEventService $meterEvents
 * @property MeterService $meters
 *
 * @license MIT
 * Modified by learndash on 05-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */
class BillingServiceFactory extends \StellarWP\Learndash\Stripe\Service\AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'meterEventAdjustments' => MeterEventAdjustmentService::class,
        'meterEvents' => MeterEventService::class,
        'meters' => MeterService::class,
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
