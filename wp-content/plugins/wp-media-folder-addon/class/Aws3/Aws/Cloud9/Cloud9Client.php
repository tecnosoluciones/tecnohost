<?php
namespace WP_Media_Folder\Aws\Cloud9;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Cloud9** service.
 * @method \WP_Media_Folder\Aws\Result createEnvironmentEC2(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createEnvironmentEC2Async(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createEnvironmentMembership(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createEnvironmentMembershipAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEnvironment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEnvironmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEnvironmentMembership(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEnvironmentMembershipAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEnvironmentMemberships(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEnvironmentMembershipsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEnvironmentStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEnvironmentStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEnvironments(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEnvironmentsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listEnvironments(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listEnvironmentsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEnvironment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEnvironmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEnvironmentMembership(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEnvironmentMembershipAsync(array $args = [])
 */
class Cloud9Client extends AwsClient {}
