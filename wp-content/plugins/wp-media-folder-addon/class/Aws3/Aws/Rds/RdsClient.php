<?php
namespace WP_Media_Folder\Aws\Rds;

use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\Api\Service;
use WP_Media_Folder\Aws\Api\DocModel;
use WP_Media_Folder\Aws\Api\ApiProvider;
use WP_Media_Folder\Aws\PresignUrlMiddleware;

/**
 * This client is used to interact with the **Amazon Relational Database Service (Amazon RDS)**.
 *
 * @method \WP_Media_Folder\Aws\Result addSourceIdentifierToSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addSourceIdentifierToSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addTagsToResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addTagsToResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result authorizeDBSecurityGroupIngress(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise authorizeDBSecurityGroupIngressAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result copyDBParameterGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise copyDBParameterGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result copyDBSnapshot(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise copyDBSnapshotAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result copyOptionGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise copyOptionGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDBInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDBInstanceReadReplica(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBInstanceReadReplicaAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDBParameterGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBParameterGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDBSecurityGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBSecurityGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDBSnapshot(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBSnapshotAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createDBSubnetGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBSubnetGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createEventSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createEventSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createOptionGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createOptionGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDBInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDBParameterGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBParameterGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDBSecurityGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBSecurityGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDBSnapshot(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBSnapshotAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteDBSubnetGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBSubnetGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEventSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEventSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteOptionGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteOptionGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBEngineVersions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBEngineVersionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBInstances(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBInstancesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBLogFiles(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBLogFilesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBParameterGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBParameterGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBParameters(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBParametersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBSecurityGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBSecurityGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBSnapshots(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBSnapshotsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeDBSubnetGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBSubnetGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEngineDefaultParameters(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEngineDefaultParametersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEventCategories(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventCategoriesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEventSubscriptions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventSubscriptionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeOptionGroupOptions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeOptionGroupOptionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeOptionGroups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeOptionGroupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeOrderableDBInstanceOptions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeOrderableDBInstanceOptionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeReservedDBInstances(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeReservedDBInstancesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeReservedDBInstancesOfferings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeReservedDBInstancesOfferingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result downloadDBLogFilePortion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise downloadDBLogFilePortionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyDBInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyDBParameterGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBParameterGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyDBSubnetGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBSubnetGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyEventSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyEventSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyOptionGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyOptionGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result promoteReadReplica(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise promoteReadReplicaAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result purchaseReservedDBInstancesOffering(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise purchaseReservedDBInstancesOfferingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result rebootDBInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise rebootDBInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeSourceIdentifierFromSubscription(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeSourceIdentifierFromSubscriptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeTagsFromResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeTagsFromResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result resetDBParameterGroup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise resetDBParameterGroupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result restoreDBInstanceFromDBSnapshot(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreDBInstanceFromDBSnapshotAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result restoreDBInstanceToPointInTime(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreDBInstanceToPointInTimeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result revokeDBSecurityGroupIngress(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise revokeDBSecurityGroupIngressAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addRoleToDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addRoleToDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result applyPendingMaintenanceAction(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise applyPendingMaintenanceActionAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result backtrackDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise backtrackDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result copyDBClusterParameterGroup(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise copyDBClusterParameterGroupAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result copyDBClusterSnapshot(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise copyDBClusterSnapshotAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result createDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result createDBClusterEndpoint(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBClusterEndpointAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result createDBClusterParameterGroup(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBClusterParameterGroupAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result createDBClusterSnapshot(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createDBClusterSnapshotAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result createGlobalCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createGlobalClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result deleteDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result deleteDBClusterEndpoint(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBClusterEndpointAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result deleteDBClusterParameterGroup(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBClusterParameterGroupAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result deleteDBClusterSnapshot(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBClusterSnapshotAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result deleteDBInstanceAutomatedBackup(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteDBInstanceAutomatedBackupAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result deleteGlobalCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteGlobalClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeAccountAttributes(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAccountAttributesAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeCertificates(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeCertificatesAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBClusterBacktracks(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBClusterBacktracksAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBClusterEndpoints(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBClusterEndpointsAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBClusterParameterGroups(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBClusterParameterGroupsAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBClusterParameters(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBClusterParametersAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBClusterSnapshotAttributes(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBClusterSnapshotAttributesAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBClusterSnapshots(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBClusterSnapshotsAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBClusters(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBClustersAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBInstanceAutomatedBackups(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBInstanceAutomatedBackupsAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeDBSnapshotAttributes(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeDBSnapshotAttributesAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeEngineDefaultClusterParameters(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeEngineDefaultClusterParametersAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeGlobalClusters(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeGlobalClustersAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describePendingMaintenanceActions(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describePendingMaintenanceActionsAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeSourceRegions(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeSourceRegionsAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result describeValidDBInstanceModifications(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeValidDBInstanceModificationsAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result failoverDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise failoverDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyCurrentDBClusterCapacity(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyCurrentDBClusterCapacityAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyDBClusterEndpoint(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBClusterEndpointAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyDBClusterParameterGroup(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBClusterParameterGroupAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyDBClusterSnapshotAttribute(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBClusterSnapshotAttributeAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyDBSnapshot(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBSnapshotAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyDBSnapshotAttribute(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyDBSnapshotAttributeAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result modifyGlobalCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyGlobalClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result promoteReadReplicaDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise promoteReadReplicaDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result removeFromGlobalCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeFromGlobalClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result removeRoleFromDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeRoleFromDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result resetDBClusterParameterGroup(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise resetDBClusterParameterGroupAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result restoreDBClusterFromS3(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreDBClusterFromS3Async(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result restoreDBClusterFromSnapshot(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreDBClusterFromSnapshotAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result restoreDBClusterToPointInTime(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreDBClusterToPointInTimeAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result restoreDBInstanceFromS3(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreDBInstanceFromS3Async(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result startDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result startDBInstance(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startDBInstanceAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result stopDBCluster(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopDBClusterAsync(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\Aws\Result stopDBInstance(array $args = []) (supported in versions 2014-10-31)
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopDBInstanceAsync(array $args = []) (supported in versions 2014-10-31)
 */
class RdsClient extends AwsClient
{
    public function __construct(array $args)
    {
        $args['with_resolved'] = function (array $args) {
            $this->getHandlerList()->appendInit(
                PresignUrlMiddleware::wrap(
                    $this,
                    $args['endpoint_provider'],
                    [
                        'operations' => [
                            'CopyDBSnapshot',
                            'CreateDBInstanceReadReplica',
                            'CopyDBClusterSnapshot',
                            'CreateDBCluster'
                        ],
                        'service' => 'rds',
                        'presign_param' => 'PreSignedUrl',
                        'require_different_region' => true,
                    ]
                ),
                'rds.presigner'
            );
        };

        parent::__construct($args);
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function applyDocFilters(array $api, array $docs)
    {
        // Add the SourceRegion parameter
        $docs['shapes']['SourceRegion']['base'] = 'A required parameter that indicates '
            . 'the region that the DB snapshot will be copied from.';
        $api['shapes']['SourceRegion'] = ['type' => 'string'];
        $api['shapes']['CopyDBSnapshotMessage']['members']['SourceRegion'] = ['shape' => 'SourceRegion'];
        $api['shapes']['CreateDBInstanceReadReplicaMessage']['members']['SourceRegion'] = ['shape' => 'SourceRegion'];

        // Add the DestinationRegion parameter
        $docs['shapes']['DestinationRegion']['base']
            = '<div class="alert alert-info">The SDK will populate this '
            . 'parameter on your behalf using the configured region value of '
            . 'the client.</div>';
        $api['shapes']['DestinationRegion'] = ['type' => 'string'];
        $api['shapes']['CopyDBSnapshotMessage']['members']['DestinationRegion'] = ['shape' => 'DestinationRegion'];
        $api['shapes']['CreateDBInstanceReadReplicaMessage']['members']['DestinationRegion'] = ['shape' => 'DestinationRegion'];

        // Several parameters in presign APIs are optional.
        $docs['shapes']['String']['refs']['CopyDBSnapshotMessage$PreSignedUrl']
            = '<div class="alert alert-info">The SDK will compute this value '
            . 'for you on your behalf.</div>';
        $docs['shapes']['String']['refs']['CopyDBSnapshotMessage$DestinationRegion']
            = '<div class="alert alert-info">The SDK will populate this '
            . 'parameter on your behalf using the configured region value of '
            . 'the client.</div>';

        // Several parameters in presign APIs are optional.
        $docs['shapes']['String']['refs']['CreateDBInstanceReadReplicaMessage$PreSignedUrl']
            = '<div class="alert alert-info">The SDK will compute this value '
            . 'for you on your behalf.</div>';
        $docs['shapes']['String']['refs']['CreateDBInstanceReadReplicaMessage$DestinationRegion']
            = '<div class="alert alert-info">The SDK will populate this '
            . 'parameter on your behalf using the configured region value of '
            . 'the client.</div>';

        if ($api['metadata']['apiVersion'] != '2014-09-01') {
            $api['shapes']['CopyDBClusterSnapshotMessage']['members']['SourceRegion'] = ['shape' => 'SourceRegion'];
            $api['shapes']['CreateDBClusterMessage']['members']['SourceRegion'] = ['shape' => 'SourceRegion'];

            $api['shapes']['CopyDBClusterSnapshotMessage']['members']['DestinationRegion'] = ['shape' => 'DestinationRegion'];
            $api['shapes']['CreateDBClusterMessage']['members']['DestinationRegion'] = ['shape' => 'DestinationRegion'];

            // Several parameters in presign APIs are optional.
            $docs['shapes']['String']['refs']['CopyDBClusterSnapshotMessage$PreSignedUrl']
                = '<div class="alert alert-info">The SDK will compute this value '
                . 'for you on your behalf.</div>';
            $docs['shapes']['String']['refs']['CopyDBClusterSnapshotMessage$DestinationRegion']
                = '<div class="alert alert-info">The SDK will populate this '
                . 'parameter on your behalf using the configured region value of '
                . 'the client.</div>';

            // Several parameters in presign APIs are optional.
            $docs['shapes']['String']['refs']['CreateDBClusterMessage$PreSignedUrl']
                = '<div class="alert alert-info">The SDK will compute this value '
                . 'for you on your behalf.</div>';
            $docs['shapes']['String']['refs']['CreateDBClusterMessage$DestinationRegion']
                = '<div class="alert alert-info">The SDK will populate this '
                . 'parameter on your behalf using the configured region value of '
                . 'the client.</div>';
        }

        return [
            new Service($api, ApiProvider::defaultProvider()),
            new DocModel($docs)
        ];
    }
}
