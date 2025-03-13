<?php
namespace WP_Media_Folder\Aws\Kafka;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Managed Streaming for Kafka** service.
 * @method \WP_Media_Folder\Aws\Result createCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBootstrapBrokers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBootstrapBrokersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listClusters(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listClustersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listNodes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listNodesAsync(array $args = [])
 */
class KafkaClient extends AwsClient {}
