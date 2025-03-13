<?php
namespace WP_Media_Folder\Aws\CloudHSMV2;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS CloudHSM V2** service.
 * @method \WP_Media_Folder\Aws\Result copyBackupToRegion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise copyBackupToRegionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createHsm(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createHsmAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBackup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBackupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteHsm(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteHsmAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBackups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBackupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeClusters(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeClustersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result initializeCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise initializeClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result restoreBackup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreBackupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class CloudHSMV2Client extends AwsClient {}
