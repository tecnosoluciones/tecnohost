<?php
namespace WP_Media_Folder\Aws\ServiceDiscovery;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Route 53 Auto Naming** service.
 * @method \WP_Media_Folder\Aws\Result createHttpNamespace(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createHttpNamespaceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createPrivateDnsNamespace(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createPrivateDnsNamespaceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createPublicDnsNamespace(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createPublicDnsNamespaceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createService(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createServiceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteNamespace(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteNamespaceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteService(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteServiceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deregisterInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deregisterInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result discoverInstances(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise discoverInstancesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getInstancesHealthStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getInstancesHealthStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getNamespace(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getNamespaceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getOperation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getOperationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getService(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getServiceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listInstances(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listInstancesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listNamespaces(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listNamespacesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listOperations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listOperationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listServices(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listServicesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result registerInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise registerInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateInstanceCustomHealthStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateInstanceCustomHealthStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateService(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateServiceAsync(array $args = [])
 */
class ServiceDiscoveryClient extends AwsClient {}
