<?php
namespace WP_Media_Folder\Aws\signer;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Signer** service.
 * @method \WP_Media_Folder\Aws\Result cancelSigningProfile(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cancelSigningProfileAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeSigningJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeSigningJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSigningPlatform(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSigningPlatformAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSigningProfile(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSigningProfileAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listSigningJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listSigningJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listSigningPlatforms(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listSigningPlatformsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listSigningProfiles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listSigningProfilesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putSigningProfile(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putSigningProfileAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startSigningJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startSigningJobAsync(array $args = [])
 */
class signerClient extends AwsClient {}
