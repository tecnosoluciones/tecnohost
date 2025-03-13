<?php
namespace WP_Media_Folder\Aws\ResourceGroupsTaggingAPI;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Resource Groups Tagging API** service.
 * @method \WP_Media_Folder\Aws\Result getResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTagKeys(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTagKeysAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTagValues(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTagValuesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourcesAsync(array $args = [])
 */
class ResourceGroupsTaggingAPIClient extends AwsClient {}
