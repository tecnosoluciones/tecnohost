<?php
namespace WP_Media_Folder\Aws\MediaTailor;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS MediaTailor** service.
 * @method \WP_Media_Folder\Aws\Result deletePlaybackConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deletePlaybackConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getPlaybackConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getPlaybackConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listPlaybackConfigurations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPlaybackConfigurationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putPlaybackConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putPlaybackConfigurationAsync(array $args = [])
 */
class MediaTailorClient extends AwsClient {}
