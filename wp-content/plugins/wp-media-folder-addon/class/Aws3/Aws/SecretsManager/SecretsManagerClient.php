<?php
namespace WP_Media_Folder\Aws\SecretsManager;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Secrets Manager** service.
 * @method \WP_Media_Folder\Aws\Result cancelRotateSecret(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cancelRotateSecretAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createSecret(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createSecretAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteResourcePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteResourcePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteSecret(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteSecretAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeSecret(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeSecretAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getRandomPassword(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getRandomPasswordAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getResourcePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getResourcePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSecretValue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSecretValueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listSecretVersionIds(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listSecretVersionIdsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listSecrets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listSecretsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putResourcePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putResourcePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putSecretValue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putSecretValueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result restoreSecret(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreSecretAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result rotateSecret(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise rotateSecretAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateSecret(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateSecretAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateSecretVersionStage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateSecretVersionStageAsync(array $args = [])
 */
class SecretsManagerClient extends AwsClient {}
