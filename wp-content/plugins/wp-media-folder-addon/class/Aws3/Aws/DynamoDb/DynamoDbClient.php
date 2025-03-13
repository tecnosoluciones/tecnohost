<?php
namespace WP_Media_Folder\Aws\DynamoDb;

use WP_Media_Folder\Aws\Api\Parser\Crc32ValidatingParser;
use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\ClientResolver;
use WP_Media_Folder\Aws\Exception\AwsException;
use WP_Media_Folder\Aws\HandlerList;
use WP_Media_Folder\Aws\Middleware;
use WP_Media_Folder\Aws\RetryMiddleware;

/**
 * This client is used to interact with the **Amazon DynamoDB** service.
 *
 * @method \WP_Media_Folder\Aws\Result batchGetItem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchGetItemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchWriteItem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchWriteItemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createTable(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createTableAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteItem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteItemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTable(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTableAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTable(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTableAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getItem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getItemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTables(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTablesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putItem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putItemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result query(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise queryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result scan(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise scanAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateItem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateItemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateTable(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateTableAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createBackup(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createBackupAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result createGlobalTable(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createGlobalTableAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result deleteBackup(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBackupAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result describeBackup(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBackupAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result describeContinuousBackups(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeContinuousBackupsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result describeEndpoints(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEndpointsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result describeGlobalTable(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeGlobalTableAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result describeGlobalTableSettings(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeGlobalTableSettingsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result describeLimits(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeLimitsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result describeTimeToLive(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTimeToLiveAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result listBackups(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBackupsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result listGlobalTables(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listGlobalTablesAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result listTagsOfResource(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsOfResourceAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result restoreTableFromBackup(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreTableFromBackupAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result restoreTableToPointInTime(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreTableToPointInTimeAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result transactGetItems(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise transactGetItemsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result transactWriteItems(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise transactWriteItemsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result updateContinuousBackups(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateContinuousBackupsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result updateGlobalTable(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateGlobalTableAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result updateGlobalTableSettings(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateGlobalTableSettingsAsync(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\Aws\Result updateTimeToLive(array $args = []) (supported in versions 2012-08-10)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateTimeToLiveAsync(array $args = []) (supported in versions 2012-08-10)
 */
class DynamoDbClient extends AwsClient
{
    public static function getArguments()
    {
        $args = parent::getArguments();
        $args['retries']['default'] = 10;
        $args['retries']['fn'] = [__CLASS__, '_applyRetryConfig'];
        $args['api_provider']['fn'] = [__CLASS__, '_applyApiProvider'];

        return $args;
    }

    /**
     * Convenience method for instantiating and registering the DynamoDB
     * Session handler with this DynamoDB client object.
     *
     * @param array $config Array of options for the session handler factory
     *
     * @return SessionHandler
     */
    public function registerSessionHandler(array $config = [])
    {
        $handler = SessionHandler::fromClient($this, $config);
        $handler->register();

        return $handler;
    }

    /** @internal */
    public static function _applyRetryConfig($value, array &$args, HandlerList $list)
    {
        if (!$value) {
            return;
        }

        $list->appendSign(
            Middleware::retry(
                RetryMiddleware::createDefaultDecider(
                    $value,
                    ['errorCodes' => ['TransactionInProgressException']]
                ),
                function ($retries) {
                    return $retries
                        ? RetryMiddleware::exponentialDelay($retries) / 2
                        : 0;
                },
                isset($args['stats']['retries'])
                    ? (bool) $args['stats']['retries']
                    : false
            ),
            'retry'
        );
    }

    /** @internal */
    public static function _applyApiProvider($value, array &$args, HandlerList $list)
    {
        ClientResolver::_apply_api_provider($value, $args, $list);
        $args['parser'] = new Crc32ValidatingParser($args['parser']);
    }
}
