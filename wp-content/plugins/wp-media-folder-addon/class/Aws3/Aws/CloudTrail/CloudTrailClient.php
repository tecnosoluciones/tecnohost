<?php
namespace WP_Media_Folder\Aws\CloudTrail;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS CloudTrail** service.
 *
 * @method \WP_Media_Folder\Aws\Result addTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createTrail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createTrailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTrail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTrailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTrails(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTrailsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getEventSelectors(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getEventSelectorsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTrailStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTrailStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listPublicKeys(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPublicKeysAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result lookupEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise lookupEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putEventSelectors(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putEventSelectorsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startLogging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startLoggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopLogging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopLoggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateTrail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateTrailAsync(array $args = [])
 */
class CloudTrailClient extends AwsClient {}
