<?php
namespace WP_Media_Folder\Aws\EKS;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Elastic Container Service for Kubernetes** service.
 * @method \WP_Media_Folder\Aws\Result createCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCluster(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeClusterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listClusters(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listClustersAsync(array $args = [])
 */
class EKSClient extends AwsClient {}
