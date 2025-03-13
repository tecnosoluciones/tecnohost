<?php
namespace WP_Media_Folder\Aws\Pricing;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Price List Service** service.
 * @method \WP_Media_Folder\Aws\Result describeServices(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeServicesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAttributeValues(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAttributeValuesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getProducts(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getProductsAsync(array $args = [])
 */
class PricingClient extends AwsClient {}
