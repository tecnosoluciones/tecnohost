<?php
namespace WP_Media_Folder\Aws\MarketplaceMetering;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWSMarketplace Metering** service.
 * @method \WP_Media_Folder\Aws\Result batchMeterUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchMeterUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result meterUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise meterUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result registerUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise registerUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result resolveCustomer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise resolveCustomerAsync(array $args = [])
 */
class MarketplaceMeteringClient extends AwsClient {}
