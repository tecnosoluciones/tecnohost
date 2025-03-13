<?php
namespace WP_Media_Folder\Aws\MQ;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AmazonMQ** service.
 * @method \WP_Media_Folder\Aws\Result createBroker(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createBrokerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBroker(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBrokerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBroker(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBrokerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeConfigurationRevision(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeConfigurationRevisionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeUserAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBrokers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBrokersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listConfigurationRevisions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listConfigurationRevisionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listConfigurations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listConfigurationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUsers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUsersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result rebootBroker(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise rebootBrokerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateBroker(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateBrokerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateUser(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateUserAsync(array $args = [])
 */
class MQClient extends AwsClient {}
