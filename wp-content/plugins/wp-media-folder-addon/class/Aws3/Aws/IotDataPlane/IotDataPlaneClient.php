<?php
namespace WP_Media_Folder\Aws\IotDataPlane;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS IoT Data Plane** service.
 *
 * @method \WP_Media_Folder\Aws\Result deleteThingShadow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteThingShadowAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getThingShadow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getThingShadowAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result publish(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise publishAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateThingShadow(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateThingShadowAsync(array $args = [])
 */
class IotDataPlaneClient extends AwsClient {}
