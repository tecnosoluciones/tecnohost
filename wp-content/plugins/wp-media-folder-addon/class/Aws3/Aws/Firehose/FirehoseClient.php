<?php
namespace WP_Media_Folder\Aws\Firehose;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Kinesis Firehose** service.
 *
 * @method \WP_Media_Folder\Aws\Result createDeliveryStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDeliveryStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDeliveryStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDeliveryStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDeliveryStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDeliveryStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listDeliveryStreams(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listDeliveryStreamsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForDeliveryStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForDeliveryStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putRecord(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putRecordAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putRecordBatch(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putRecordBatchAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startDeliveryStreamEncryption(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startDeliveryStreamEncryptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopDeliveryStreamEncryption(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopDeliveryStreamEncryptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagDeliveryStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagDeliveryStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagDeliveryStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagDeliveryStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateDestination(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateDestinationAsync(array $args = [])
 */
class FirehoseClient extends AwsClient {}
