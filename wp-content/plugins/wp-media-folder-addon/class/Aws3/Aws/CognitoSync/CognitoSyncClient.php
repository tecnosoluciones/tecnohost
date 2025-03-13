<?php
namespace WP_Media_Folder\Aws\CognitoSync;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Cognito Sync** service.
 *
 * @method \WP_Media_Folder\Aws\Result bulkPublish(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise bulkPublishAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDataset(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDatasetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDataset(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDatasetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeIdentityPoolUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeIdentityPoolUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeIdentityUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeIdentityUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBulkPublishDetails(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBulkPublishDetailsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCognitoEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCognitoEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getIdentityPoolConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdentityPoolConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listDatasets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listDatasetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listIdentityPoolUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listIdentityPoolUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listRecords(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listRecordsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result registerDevice(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise registerDeviceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setCognitoEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setCognitoEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setIdentityPoolConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setIdentityPoolConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result subscribeToDataset(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise subscribeToDatasetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result unsubscribeFromDataset(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise unsubscribeFromDatasetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateRecords(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateRecordsAsync(array $args = [])
 */
class CognitoSyncClient extends AwsClient {}
