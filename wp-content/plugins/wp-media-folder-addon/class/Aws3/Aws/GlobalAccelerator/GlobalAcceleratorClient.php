<?php
namespace WP_Media_Folder\Aws\GlobalAccelerator;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Global Accelerator** service.
 * @method \WP_Media_Folder\Aws\Result createAccelerator(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createAcceleratorAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createEndpointGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createEndpointGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createListener(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createListenerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteAccelerator(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteAcceleratorAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEndpointGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEndpointGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteListener(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteListenerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAccelerator(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAcceleratorAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAcceleratorAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAcceleratorAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEndpointGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEndpointGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeListener(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeListenerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listAccelerators(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listAcceleratorsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listEndpointGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listEndpointGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listListeners(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listListenersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateAccelerator(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateAcceleratorAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateAcceleratorAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateAcceleratorAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEndpointGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEndpointGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateListener(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateListenerAsync(array $args = [])
 */
class GlobalAcceleratorClient extends AwsClient {}
