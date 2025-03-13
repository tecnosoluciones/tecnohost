<?php
namespace WP_Media_Folder\Aws\SnowBall;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Import/Export Snowball** service.
 * @method \WP_Media_Folder\Aws\Result cancelCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cancelClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result cancelJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cancelJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createAddress(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createAddressAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAddress(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAddressAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAddresses(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAddressesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getJobManifest(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getJobManifestAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getJobUnlockCode(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getJobUnlockCodeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSnowballUsage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSnowballUsageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listClusterJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listClusterJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listClusters(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listClustersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listCompatibleImages(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listCompatibleImagesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateJobAsync(array $args = [])
 */
class SnowBallClient extends AwsClient {}
