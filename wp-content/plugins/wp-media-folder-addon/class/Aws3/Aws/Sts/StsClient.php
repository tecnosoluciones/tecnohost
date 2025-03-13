<?php
namespace WP_Media_Folder\Aws\Sts;

use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\Result;
use WP_Media_Folder\Aws\Credentials\Credentials;

/**
 * This client is used to interact with the **AWS Security Token Service (AWS STS)**.
 *
 * @method \WP_Media_Folder\Aws\Result assumeRole(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise assumeRoleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result assumeRoleWithSAML(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise assumeRoleWithSAMLAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result assumeRoleWithWebIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise assumeRoleWithWebIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result decodeAuthorizationMessage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise decodeAuthorizationMessageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCallerIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCallerIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getFederationToken(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getFederationTokenAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSessionToken(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSessionTokenAsync(array $args = [])
 */
class StsClient extends AwsClient
{
    /**
     * Creates credentials from the result of an STS operations
     *
     * @param Result $result Result of an STS operation
     *
     * @return Credentials
     * @throws \InvalidArgumentException if the result contains no credentials
     */
    public function createCredentials(Result $result)
    {
        if (!$result->hasKey('Credentials')) {
            throw new \InvalidArgumentException('Result contains no credentials');
        }

        $c = $result['Credentials'];

        return new Credentials(
            $c['AccessKeyId'],
            $c['SecretAccessKey'],
            isset($c['SessionToken']) ? $c['SessionToken'] : null,
            isset($c['Expiration']) && $c['Expiration'] instanceof \DateTimeInterface
                ? (int) $c['Expiration']->format('U')
                : null
        );
    }
}
