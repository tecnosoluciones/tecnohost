<?php
namespace WP_Media_Folder\Aws\Mobile;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Mobile** service.
 * @method \WP_Media_Folder\Aws\Result createProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBundle(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBundleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result exportBundle(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise exportBundleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result exportProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise exportProjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBundles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBundlesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listProjects(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listProjectsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateProject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateProjectAsync(array $args = [])
 */
class MobileClient extends AwsClient {}
