<?php
namespace WP_Media_Folder\Aws\DynamoDbStreams;

use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\DynamoDb\DynamoDbClient;

/**
 * This client is used to interact with the **Amazon DynamoDb Streams** service.
 *
 * @method \WP_Media_Folder\Aws\Result describeStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getRecords(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getRecordsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getShardIterator(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getShardIteratorAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listStreams(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listStreamsAsync(array $args = [])
 */
class DynamoDbStreamsClient extends AwsClient
{
    public static function getArguments()
    {
        $args = parent::getArguments();
        $args['retries']['default'] = 11;
        $args['retries']['fn'] = [DynamoDbClient::class, '_applyRetryConfig'];

        return $args;
    }
}