<?php
namespace WP_Media_Folder\Aws\KinesisVideoArchivedMedia;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Kinesis Video Streams Archived Media** service.
 * @method \WP_Media_Folder\Aws\Result getHLSStreamingSessionURL(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHLSStreamingSessionURLAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getMediaForFragmentList(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getMediaForFragmentListAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listFragments(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listFragmentsAsync(array $args = [])
 */
class KinesisVideoArchivedMediaClient extends AwsClient {}
