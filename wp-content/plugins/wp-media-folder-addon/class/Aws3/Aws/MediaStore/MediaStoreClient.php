<?php
namespace WP_Media_Folder\Aws\MediaStore;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Elemental MediaStore** service.
 * @method \WP_Media_Folder\Aws\Result createContainer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createContainerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteContainer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteContainerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteContainerPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteContainerPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCorsPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteCorsPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeContainer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeContainerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getContainerPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getContainerPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCorsPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCorsPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listContainers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listContainersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putContainerPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putContainerPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putCorsPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putCorsPolicyAsync(array $args = [])
 */
class MediaStoreClient extends AwsClient {}
