<?php
namespace WP_Media_Folder\Aws\IoT1ClickDevicesService;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS IoT 1-Click Devices Service** service.
 * @method \WP_Media_Folder\Aws\Result claimDevicesByClaimCode(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise claimDevicesByClaimCodeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDevice(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDeviceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result finalizeDeviceClaim(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise finalizeDeviceClaimAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDeviceMethods(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDeviceMethodsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result initiateDeviceClaim(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise initiateDeviceClaimAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result invokeDeviceMethod(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise invokeDeviceMethodAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listDeviceEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listDeviceEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listDevices(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listDevicesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result unclaimDevice(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise unclaimDeviceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateDeviceState(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateDeviceStateAsync(array $args = [])
 */
class IoT1ClickDevicesServiceClient extends AwsClient {}
