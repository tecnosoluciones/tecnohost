<?php
namespace WP_Media_Folder\Aws\CostExplorer;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Cost Explorer Service** service.
 * @method \WP_Media_Folder\Aws\Result getCostAndUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCostAndUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCostForecast(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCostForecastAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDimensionValues(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDimensionValuesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getReservationCoverage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getReservationCoverageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getReservationPurchaseRecommendation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getReservationPurchaseRecommendationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getReservationUtilization(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getReservationUtilizationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTagsAsync(array $args = [])
 */
class CostExplorerClient extends AwsClient {}
