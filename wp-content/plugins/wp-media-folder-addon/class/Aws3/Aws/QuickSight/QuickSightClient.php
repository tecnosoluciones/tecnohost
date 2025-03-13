<?php
namespace WP_Media_Folder\Aws\QuickSight;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon QuickSight** service.
 * @method \WP_Media_Folder\Aws\Result createGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createGroupMembership(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createGroupMembershipAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteGroupMembership(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteGroupMembershipAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDashboardEmbedUrl(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDashboardEmbedUrlAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listGroupMemberships(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listGroupMembershipsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUserGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUserGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUsers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUsersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result registerUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise registerUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserAsync(array $args = [])
 */
class QuickSightClient extends AwsClient {}
