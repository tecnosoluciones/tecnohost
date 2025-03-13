<?php
namespace WP_Media_Folder\Aws\Sfn;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Step Functions** service.
 * @method \WP_Media_Folder\Aws\Result createActivity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createActivityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createStateMachine(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createStateMachineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteActivity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteActivityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteStateMachine(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteStateMachineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeActivity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeActivityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeStateMachine(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeStateMachineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeStateMachineForExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeStateMachineForExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getActivityTask(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getActivityTaskAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getExecutionHistory(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getExecutionHistoryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listActivities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listActivitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listExecutions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listExecutionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listStateMachines(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listStateMachinesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendTaskFailure(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendTaskFailureAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendTaskHeartbeat(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendTaskHeartbeatAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendTaskSuccess(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendTaskSuccessAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateStateMachine(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateStateMachineAsync(array $args = [])
 */
class SfnClient extends AwsClient {}
