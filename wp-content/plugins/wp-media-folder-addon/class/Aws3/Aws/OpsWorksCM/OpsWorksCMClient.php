<?php
namespace WP_Media_Folder\Aws\OpsWorksCM;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS OpsWorks for Chef Automate** service.
 * @method \WP_Media_Folder\Aws\Result associateNode(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateNodeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createBackup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createBackupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBackup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBackupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAccountAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAccountAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBackups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBackupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeNodeAssociationStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeNodeAssociationStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeServers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeServersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateNode(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateNodeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result exportServerEngineAttribute(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise exportServerEngineAttributeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result restoreServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startMaintenance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startMaintenanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateServer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateServerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateServerEngineAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateServerEngineAttributesAsync(array $args = [])
 */
class OpsWorksCMClient extends AwsClient {}
