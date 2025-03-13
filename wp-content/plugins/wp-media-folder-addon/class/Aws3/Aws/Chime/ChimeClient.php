<?php
namespace WP_Media_Folder\Aws\Chime;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Chime** service.
 * @method \WP_Media_Folder\Aws\Result batchSuspendUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchSuspendUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchUnsuspendUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchUnsuspendUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchUpdateUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchUpdateUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAccountSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAccountSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result inviteUsers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise inviteUsersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listAccounts(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listAccountsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUsers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUsersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result logoutUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise logoutUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result resetPersonalPIN(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise resetPersonalPINAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateAccountSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateAccountSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserAsync(array $args = [])
 */
class ChimeClient extends AwsClient {}
