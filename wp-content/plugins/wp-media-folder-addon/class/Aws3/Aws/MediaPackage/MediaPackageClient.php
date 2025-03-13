<?php
namespace WP_Media_Folder\Aws\MediaPackage;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Elemental MediaPackage** service.
 * @method \WP_Media_Folder\Aws\Result createChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createOriginEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createOriginEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteOriginEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteOriginEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeOriginEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeOriginEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listChannels(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listChannelsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listOriginEndpoints(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listOriginEndpointsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result rotateChannelCredentials(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise rotateChannelCredentialsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result rotateIngestEndpointCredentials(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise rotateIngestEndpointCredentialsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateOriginEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateOriginEndpointAsync(array $args = [])
 */
class MediaPackageClient extends AwsClient {}
