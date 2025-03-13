<?php
namespace WP_Media_Folder\Aws\Connect;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Connect Service** service.
 * @method \WP_Media_Folder\Aws\Result createUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeUserHierarchyGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeUserHierarchyGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeUserHierarchyStructure(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeUserHierarchyStructureAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCurrentMetricData(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCurrentMetricDataAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getFederationToken(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getFederationTokenAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getMetricData(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getMetricDataAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listRoutingProfiles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listRoutingProfilesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listSecurityProfiles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listSecurityProfilesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUserHierarchyGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUserHierarchyGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUsers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUsersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startOutboundVoiceContact(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startOutboundVoiceContactAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopContact(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopContactAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateContactAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateContactAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUserHierarchy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserHierarchyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUserIdentityInfo(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserIdentityInfoAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUserPhoneConfig(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserPhoneConfigAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUserRoutingProfile(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserRoutingProfileAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUserSecurityProfiles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserSecurityProfilesAsync(array $args = [])
 */
class ConnectClient extends AwsClient {}
