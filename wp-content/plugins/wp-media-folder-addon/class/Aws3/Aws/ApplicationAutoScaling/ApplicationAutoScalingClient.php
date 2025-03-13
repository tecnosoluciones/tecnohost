<?php
namespace WP_Media_Folder\Aws\ApplicationAutoScaling;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Application Auto Scaling** service.
 * @method \WP_Media_Folder\Aws\Result deleteScalingPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteScalingPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteScheduledAction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteScheduledActionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deregisterScalableTarget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deregisterScalableTargetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeScalableTargets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeScalableTargetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeScalingActivities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeScalingActivitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeScalingPolicies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeScalingPoliciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeScheduledActions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeScheduledActionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putScalingPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putScalingPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putScheduledAction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putScheduledActionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result registerScalableTarget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise registerScalableTargetAsync(array $args = [])
 */
class ApplicationAutoScalingClient extends AwsClient {}
