<?php
namespace WP_Media_Folder\Aws\MediaStoreData;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Elemental MediaStore Data Plane** service.
 * @method \WP_Media_Folder\Aws\Result deleteObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listItems(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listItemsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putObjectAsync(array $args = [])
 */
class MediaStoreDataClient extends AwsClient {}
