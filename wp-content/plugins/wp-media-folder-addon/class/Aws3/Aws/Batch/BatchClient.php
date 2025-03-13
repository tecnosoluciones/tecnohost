<?php
namespace WP_Media_Folder\Aws\Batch;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Batch** service.
 * @method \WP_Media_Folder\Aws\Result cancelJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cancelJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createComputeEnvironment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createComputeEnvironmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createJobQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createJobQueueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteComputeEnvironment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteComputeEnvironmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteJobQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteJobQueueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deregisterJobDefinition(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deregisterJobDefinitionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeComputeEnvironments(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeComputeEnvironmentsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeJobDefinitions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeJobDefinitionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeJobQueues(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeJobQueuesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result registerJobDefinition(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise registerJobDefinitionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result submitJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise submitJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result terminateJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise terminateJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateComputeEnvironment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateComputeEnvironmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateJobQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateJobQueueAsync(array $args = [])
 */
class BatchClient extends AwsClient {}
