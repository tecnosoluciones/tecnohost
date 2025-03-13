<?php
namespace WP_Media_Folder\Aws\Translate;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Translate** service.
 * @method \WP_Media_Folder\Aws\Result deleteTerminology(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTerminologyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTerminology(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTerminologyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result importTerminology(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise importTerminologyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTerminologies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTerminologiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result translateText(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise translateTextAsync(array $args = [])
 */
class TranslateClient extends AwsClient {}
