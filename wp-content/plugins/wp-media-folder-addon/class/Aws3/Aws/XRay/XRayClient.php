<?php
namespace WP_Media_Folder\Aws\XRay;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS X-Ray** service.
 * @method \WP_Media_Folder\Aws\Result batchGetTraces(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchGetTracesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createSamplingRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createSamplingRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteSamplingRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteSamplingRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getEncryptionConfig(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getEncryptionConfigAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSamplingRules(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSamplingRulesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSamplingStatisticSummaries(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSamplingStatisticSummariesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSamplingTargets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSamplingTargetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getServiceGraph(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getServiceGraphAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTraceGraph(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTraceGraphAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTraceSummaries(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTraceSummariesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putEncryptionConfig(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putEncryptionConfigAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putTelemetryRecords(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putTelemetryRecordsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putTraceSegments(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putTraceSegmentsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateSamplingRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateSamplingRuleAsync(array $args = [])
 */
class XRayClient extends AwsClient {}
