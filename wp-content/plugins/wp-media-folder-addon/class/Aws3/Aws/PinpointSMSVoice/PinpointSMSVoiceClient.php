<?php
namespace WP_Media_Folder\Aws\PinpointSMSVoice;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Pinpoint SMS and Voice Service** service.
 * @method \WP_Media_Folder\Aws\Result createConfigurationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createConfigurationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createConfigurationSetEventDestination(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createConfigurationSetEventDestinationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteConfigurationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteConfigurationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteConfigurationSetEventDestination(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteConfigurationSetEventDestinationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getConfigurationSetEventDestinations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getConfigurationSetEventDestinationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendVoiceMessage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendVoiceMessageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateConfigurationSetEventDestination(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateConfigurationSetEventDestinationAsync(array $args = [])
 */
class PinpointSMSVoiceClient extends AwsClient {}
