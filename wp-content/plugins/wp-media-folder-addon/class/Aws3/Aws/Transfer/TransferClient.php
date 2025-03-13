<?php
namespace WP_Media_Folder\Aws\Transfer;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Transfer for SFTP** service.
 * @method \WP_Media_Folder\Aws\Result createServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteSshPublicKey(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteSshPublicKeyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result importSshPublicKey(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise importSshPublicKeyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listServers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listServersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUsers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUsersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result testIdentityProvider(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise testIdentityProviderAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserAsync(array $args = [])
 */
class TransferClient extends AwsClient {}
