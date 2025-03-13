<?php
namespace WP_Media_Folder\Aws\DLM;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Data Lifecycle Manager** service.
 * @method \WP_Media_Folder\Aws\Result createLifecyclePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createLifecyclePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteLifecyclePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteLifecyclePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getLifecyclePolicies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getLifecyclePoliciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getLifecyclePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getLifecyclePolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateLifecyclePolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateLifecyclePolicyAsync(array $args = [])
 */
class DLMClient extends AwsClient {}
