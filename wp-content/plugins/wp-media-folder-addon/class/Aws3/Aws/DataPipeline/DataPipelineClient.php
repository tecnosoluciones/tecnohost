<?php
namespace WP_Media_Folder\Aws\DataPipeline;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Data Pipeline** service.
 *
 * @method \WP_Media_Folder\Aws\Result activatePipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise activatePipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createPipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createPipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deactivatePipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deactivatePipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deletePipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deletePipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeObjects(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeObjectsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describePipelines(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describePipelinesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result evaluateExpression(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise evaluateExpressionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getPipelineDefinition(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getPipelineDefinitionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listPipelines(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPipelinesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result pollForTask(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise pollForTaskAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putPipelineDefinition(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putPipelineDefinitionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result queryObjects(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise queryObjectsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result reportTaskProgress(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise reportTaskProgressAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result reportTaskRunnerHeartbeat(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise reportTaskRunnerHeartbeatAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setTaskStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setTaskStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result validatePipelineDefinition(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise validatePipelineDefinitionAsync(array $args = [])
 */
class DataPipelineClient extends AwsClient {}
