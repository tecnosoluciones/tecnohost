<?php
namespace WP_Media_Folder\Aws\Athena;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Athena** service.
 * @method \WP_Media_Folder\Aws\Result batchGetNamedQuery(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchGetNamedQueryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result batchGetQueryExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise batchGetQueryExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createNamedQuery(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createNamedQueryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteNamedQuery(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteNamedQueryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getNamedQuery(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getNamedQueryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getQueryExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getQueryExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getQueryResults(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getQueryResultsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listNamedQueries(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listNamedQueriesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listQueryExecutions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listQueryExecutionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startQueryExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startQueryExecutionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopQueryExecution(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopQueryExecutionAsync(array $args = [])
 */
class AthenaClient extends AwsClient {}
