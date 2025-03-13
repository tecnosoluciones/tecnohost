<?php
namespace WP_Media_Folder\Aws\CognitoIdentity;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Cognito Identity** service.
 *
 * @method \WP_Media_Folder\Aws\Result createIdentityPool(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createIdentityPoolAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteIdentities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteIdentitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteIdentityPool(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteIdentityPoolAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeIdentityPool(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeIdentityPoolAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCredentialsForIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCredentialsForIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getId(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getIdentityPoolRoles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdentityPoolRolesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getOpenIdToken(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getOpenIdTokenAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getOpenIdTokenForDeveloperIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getOpenIdTokenForDeveloperIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listIdentities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listIdentitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listIdentityPools(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listIdentityPoolsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result lookupDeveloperIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise lookupDeveloperIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result mergeDeveloperIdentities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise mergeDeveloperIdentitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setIdentityPoolRoles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setIdentityPoolRolesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result unlinkDeveloperIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise unlinkDeveloperIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result unlinkIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise unlinkIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateIdentityPool(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateIdentityPoolAsync(array $args = [])
 */
class CognitoIdentityClient extends AwsClient {}
