<?php
namespace WP_Media_Folder\Aws\PI;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Performance Insights** service.
 * @method \WP_Media_Folder\Aws\Result describeDimensionKeys(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDimensionKeysAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getResourceMetrics(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getResourceMetricsAsync(array $args = [])
 */
class PIClient extends AwsClient {}
