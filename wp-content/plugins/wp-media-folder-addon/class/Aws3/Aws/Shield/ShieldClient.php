<?php
namespace WP_Media_Folder\Aws\Shield;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Shield** service.
 * @method \WP_Media_Folder\Aws\Result associateDRTLogBucket(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateDRTLogBucketAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result associateDRTRole(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateDRTRoleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createProtection(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createProtectionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteProtection(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteProtectionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAttack(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAttackAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDRTAccess(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDRTAccessAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEmergencyContactSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEmergencyContactSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeProtection(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeProtectionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateDRTLogBucket(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateDRTLogBucketAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateDRTRole(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateDRTRoleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSubscriptionState(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSubscriptionStateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listAttacks(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listAttacksAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listProtections(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listProtectionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEmergencyContactSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEmergencyContactSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateSubscriptionAsync(array $args = [])
 */
class ShieldClient extends AwsClient {}
