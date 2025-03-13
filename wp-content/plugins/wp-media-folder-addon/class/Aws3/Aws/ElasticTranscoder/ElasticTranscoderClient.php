<?php
namespace WP_Media_Folder\Aws\ElasticTranscoder;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Elastic Transcoder** service.
 *
 * @method \WP_Media_Folder\Aws\Result cancelJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cancelJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createPipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createPipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createPreset(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createPresetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deletePipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deletePipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deletePreset(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deletePresetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listJobsByPipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listJobsByPipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listJobsByStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listJobsByStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listPipelines(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPipelinesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listPresets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPresetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result readJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise readJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result readPipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise readPipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result readPreset(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise readPresetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result testRole(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise testRoleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updatePipeline(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updatePipelineAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updatePipelineNotifications(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updatePipelineNotificationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updatePipelineStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updatePipelineStatusAsync(array $args = [])
 */
class ElasticTranscoderClient extends AwsClient {}
