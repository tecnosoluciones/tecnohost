<?php
namespace WP_Media_Folder\Aws\Efs;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with **Amazon EFS**.
 *
 * @method \WP_Media_Folder\Aws\Result createFileSystem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createFileSystemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createMountTarget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createMountTargetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteFileSystem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteFileSystemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteMountTarget(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteMountTargetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeFileSystems(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeFileSystemsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeMountTargetSecurityGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeMountTargetSecurityGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeMountTargets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeMountTargetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyMountTargetSecurityGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyMountTargetSecurityGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateFileSystem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateFileSystemAsync(array $args = [])
 */
class EfsClient extends AwsClient {}
