<?php
namespace WP_Media_Folder\Aws\CloudWatchEvents;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon CloudWatch Events** service.
 *
 * @method \WP_Media_Folder\Aws\Result deleteRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEventBus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventBusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disableRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disableRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result enableRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise enableRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listRuleNamesByTarget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listRuleNamesByTargetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listRules(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listRulesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTargetsByRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTargetsByRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putPermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putPermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putTargets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putTargetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removePermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removePermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeTargets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeTargetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result testEventPattern(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise testEventPatternAsync(array $args = [])
 */
class CloudWatchEventsClient extends AwsClient {}
