<?php
namespace WP_Media_Folder\Aws\IoTJobsDataPlane;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS IoT Jobs Data Plane** service.
 * @method \WP_Media_Folder\Aws\Result describeJobExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeJobExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getPendingJobExecutions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getPendingJobExecutionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startNextPendingJobExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startNextPendingJobExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateJobExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateJobExecutionAsync(array $args = [])
 */
class IoTJobsDataPlaneClient extends AwsClient {}
