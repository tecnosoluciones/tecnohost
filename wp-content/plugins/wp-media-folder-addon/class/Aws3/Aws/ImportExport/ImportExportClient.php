<?php
namespace WP_Media_Folder\Aws\ImportExport;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Import/Export** service.
 * @method \WP_Media_Folder\Aws\Result cancelJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cancelJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getShippingLabel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getShippingLabelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateJobAsync(array $args = [])
 */
class ImportExportClient extends AwsClient {}
