<?php
namespace WP_Media_Folder\Aws\ResourceGroups;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Resource Groups** service.
 * @method \WP_Media_Folder\Aws\Result createGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getGroupQuery(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getGroupQueryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listGroupResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listGroupResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result searchResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise searchResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tag(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untag(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateGroupQuery(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateGroupQueryAsync(array $args = [])
 */
class ResourceGroupsClient extends AwsClient {}
