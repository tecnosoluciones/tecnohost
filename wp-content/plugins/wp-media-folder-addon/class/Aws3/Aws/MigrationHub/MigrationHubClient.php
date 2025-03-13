<?php
namespace WP_Media_Folder\Aws\MigrationHub;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Migration Hub** service.
 * @method \WP_Media_Folder\Aws\Result associateCreatedArtifact(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateCreatedArtifactAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result associateDiscoveredResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateDiscoveredResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createProgressUpdateStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createProgressUpdateStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteProgressUpdateStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteProgressUpdateStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeApplicationState(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeApplicationStateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeMigrationTask(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeMigrationTaskAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateCreatedArtifact(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateCreatedArtifactAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateDiscoveredResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateDiscoveredResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result importMigrationTask(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise importMigrationTaskAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listCreatedArtifacts(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listCreatedArtifactsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listDiscoveredResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listDiscoveredResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listMigrationTasks(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listMigrationTasksAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listProgressUpdateStreams(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listProgressUpdateStreamsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result notifyApplicationState(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise notifyApplicationStateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result notifyMigrationTaskState(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise notifyMigrationTaskStateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putResourceAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putResourceAttributesAsync(array $args = [])
 */
class MigrationHubClient extends AwsClient {}
