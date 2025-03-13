<?php
namespace WP_Media_Folder\Aws\Health;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Health APIs and Notifications** service.
 * @method \WP_Media_Folder\Aws\Result describeAffectedEntities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAffectedEntitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEntityAggregates(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEntityAggregatesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEventAggregates(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventAggregatesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEventDetails(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventDetailsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEventTypes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventTypesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventsAsync(array $args = [])
 */
class HealthClient extends AwsClient {}
