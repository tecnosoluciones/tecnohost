<?php
namespace WP_Media_Folder\Aws\FMS;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Firewall Management Service** service.
 * @method \WP_Media_Folder\Aws\Result associateAdminAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateAdminAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteNotificationChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteNotificationChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deletePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deletePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateAdminAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateAdminAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAdminAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAdminAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getComplianceDetail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getComplianceDetailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getNotificationChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getNotificationChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listComplianceStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listComplianceStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listMemberAccounts(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listMemberAccountsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listPolicies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPoliciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putNotificationChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putNotificationChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putPolicyAsync(array $args = [])
 */
class FMSClient extends AwsClient {}
