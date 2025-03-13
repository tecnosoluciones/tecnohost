<?php
namespace WP_Media_Folder\Aws\Ecr;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon EC2 Container Registry** service.
 *
 * @method \WP_Media_Folder\Aws\Result batchCheckLayerAvailability(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchCheckLayerAvailabilityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchDeleteImage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchDeleteImageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchGetImage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchGetImageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result completeLayerUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise completeLayerUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createRepository(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createRepositoryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteLifecyclePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteLifecyclePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteRepository(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteRepositoryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteRepositoryPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteRepositoryPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeImages(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeImagesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeRepositories(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeRepositoriesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAuthorizationToken(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAuthorizationTokenAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDownloadUrlForLayer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDownloadUrlForLayerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getLifecyclePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getLifecyclePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getLifecyclePolicyPreview(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getLifecyclePolicyPreviewAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getRepositoryPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getRepositoryPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result initiateLayerUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise initiateLayerUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listImages(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listImagesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putImage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putImageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putLifecyclePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putLifecyclePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setRepositoryPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setRepositoryPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startLifecyclePolicyPreview(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startLifecyclePolicyPreviewAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result uploadLayerPart(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise uploadLayerPartAsync(array $args = [])
 */
class EcrClient extends AwsClient {}
