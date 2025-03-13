<?php
namespace WP_Media_Folder\Aws\AutoScalingPlans;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Auto Scaling Plans** service.
 * @method \WP_Media_Folder\Aws\Result createScalingPlan(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createScalingPlanAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteScalingPlan(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteScalingPlanAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeScalingPlanResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeScalingPlanResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeScalingPlans(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeScalingPlansAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getScalingPlanResourceForecastData(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getScalingPlanResourceForecastDataAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateScalingPlan(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateScalingPlanAsync(array $args = [])
 */
class AutoScalingPlansClient extends AwsClient {}
