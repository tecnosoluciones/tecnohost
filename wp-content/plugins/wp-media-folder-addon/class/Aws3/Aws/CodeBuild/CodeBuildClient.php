<?php
namespace WP_Media_Folder\Aws\CodeBuild;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS CodeBuild** service.
 * @method \WP_Media_Folder\Aws\Result batchDeleteBuilds(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchDeleteBuildsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchGetBuilds(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchGetBuildsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchGetProjects(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchGetProjectsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createWebhook(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createWebhookAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteWebhook(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteWebhookAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result invalidateProjectCache(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise invalidateProjectCacheAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBuilds(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBuildsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBuildsForProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBuildsForProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listCuratedEnvironmentImages(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listCuratedEnvironmentImagesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listProjects(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listProjectsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startBuild(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startBuildAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopBuild(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopBuildAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateWebhook(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateWebhookAsync(array $args = [])
 */
class CodeBuildClient extends AwsClient {}
