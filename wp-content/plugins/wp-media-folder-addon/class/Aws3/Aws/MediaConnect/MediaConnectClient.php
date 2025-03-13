<?php
namespace WP_Media_Folder\Aws\MediaConnect;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS MediaConnect** service.
 * @method \WP_Media_Folder\Aws\Result addFlowOutputs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addFlowOutputsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createFlow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createFlowAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteFlow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteFlowAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeFlow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeFlowAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result grantFlowEntitlements(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise grantFlowEntitlementsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listEntitlements(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listEntitlementsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listFlows(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listFlowsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeFlowOutput(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeFlowOutputAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result revokeFlowEntitlement(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise revokeFlowEntitlementAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startFlow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startFlowAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopFlow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopFlowAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateFlowEntitlement(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateFlowEntitlementAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateFlowOutput(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateFlowOutputAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateFlowSource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateFlowSourceAsync(array $args = [])
 */
class MediaConnectClient extends AwsClient {}
