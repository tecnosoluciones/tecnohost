<?php
namespace WP_Media_Folder\Aws\KinesisVideo;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Kinesis Video Streams** service.
 * @method \WP_Media_Folder\Aws\Result createStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDataEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDataEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listStreams(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listStreamsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateDataRetention(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateDataRetentionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateStreamAsync(array $args = [])
 */
class KinesisVideoClient extends AwsClient {}
