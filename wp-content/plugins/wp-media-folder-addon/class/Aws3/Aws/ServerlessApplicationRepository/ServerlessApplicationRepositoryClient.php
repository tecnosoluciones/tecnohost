<?php
namespace WP_Media_Folder\Aws\ServerlessApplicationRepository;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWSServerlessApplicationRepository** service.
 * @method \WP_Media_Folder\Aws\Result createApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createApplicationVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createApplicationVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCloudFormationChangeSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createCloudFormationChangeSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCloudFormationTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createCloudFormationTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApplicationPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getApplicationPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCloudFormationTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCloudFormationTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listApplicationDependencies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listApplicationDependenciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listApplicationVersions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listApplicationVersionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listApplications(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listApplicationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putApplicationPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putApplicationPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateApplicationAsync(array $args = [])
 */
class ServerlessApplicationRepositoryClient extends AwsClient {}
