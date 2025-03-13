<?php
namespace WP_Media_Folder\Aws;

/**
 * Builds AWS clients based on configuration settings.
 *
 * @method \WP_Media_Folder\Aws\ACMPCA\ACMPCAClient createACMPCA(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionACMPCA(array $args = [])
 * @method \WP_Media_Folder\Aws\Acm\AcmClient createAcm(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAcm(array $args = [])
 * @method \WP_Media_Folder\Aws\AlexaForBusiness\AlexaForBusinessClient createAlexaForBusiness(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAlexaForBusiness(array $args = [])
 * @method \WP_Media_Folder\Aws\Amplify\AmplifyClient createAmplify(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAmplify(array $args = [])
 * @method \WP_Media_Folder\Aws\ApiGateway\ApiGatewayClient createApiGateway(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionApiGateway(array $args = [])
 * @method \WP_Media_Folder\Aws\AppMesh\AppMeshClient createAppMesh(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAppMesh(array $args = [])
 * @method \WP_Media_Folder\Aws\AppSync\AppSyncClient createAppSync(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAppSync(array $args = [])
 * @method \WP_Media_Folder\Aws\ApplicationAutoScaling\ApplicationAutoScalingClient createApplicationAutoScaling(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionApplicationAutoScaling(array $args = [])
 * @method \WP_Media_Folder\Aws\ApplicationDiscoveryService\ApplicationDiscoveryServiceClient createApplicationDiscoveryService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionApplicationDiscoveryService(array $args = [])
 * @method \WP_Media_Folder\Aws\Appstream\AppstreamClient createAppstream(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAppstream(array $args = [])
 * @method \WP_Media_Folder\Aws\Athena\AthenaClient createAthena(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAthena(array $args = [])
 * @method \WP_Media_Folder\Aws\AutoScaling\AutoScalingClient createAutoScaling(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAutoScaling(array $args = [])
 * @method \WP_Media_Folder\Aws\AutoScalingPlans\AutoScalingPlansClient createAutoScalingPlans(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionAutoScalingPlans(array $args = [])
 * @method \WP_Media_Folder\Aws\Batch\BatchClient createBatch(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionBatch(array $args = [])
 * @method \WP_Media_Folder\Aws\Budgets\BudgetsClient createBudgets(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionBudgets(array $args = [])
 * @method \WP_Media_Folder\Aws\Chime\ChimeClient createChime(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionChime(array $args = [])
 * @method \WP_Media_Folder\Aws\Cloud9\Cloud9Client createCloud9(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloud9(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudDirectory\CloudDirectoryClient createCloudDirectory(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudDirectory(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudFormation\CloudFormationClient createCloudFormation(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudFormation(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudFront\CloudFrontClient createCloudFront(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudFront(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudHSMV2\CloudHSMV2Client createCloudHSMV2(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudHSMV2(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudHsm\CloudHsmClient createCloudHsm(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudHsm(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudSearch\CloudSearchClient createCloudSearch(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudSearch(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudSearchDomain\CloudSearchDomainClient createCloudSearchDomain(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudSearchDomain(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudTrail\CloudTrailClient createCloudTrail(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudTrail(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudWatch\CloudWatchClient createCloudWatch(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudWatch(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudWatchEvents\CloudWatchEventsClient createCloudWatchEvents(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudWatchEvents(array $args = [])
 * @method \WP_Media_Folder\Aws\CloudWatchLogs\CloudWatchLogsClient createCloudWatchLogs(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCloudWatchLogs(array $args = [])
 * @method \WP_Media_Folder\Aws\CodeBuild\CodeBuildClient createCodeBuild(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCodeBuild(array $args = [])
 * @method \WP_Media_Folder\Aws\CodeCommit\CodeCommitClient createCodeCommit(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCodeCommit(array $args = [])
 * @method \WP_Media_Folder\Aws\CodeDeploy\CodeDeployClient createCodeDeploy(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCodeDeploy(array $args = [])
 * @method \WP_Media_Folder\Aws\CodePipeline\CodePipelineClient createCodePipeline(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCodePipeline(array $args = [])
 * @method \WP_Media_Folder\Aws\CodeStar\CodeStarClient createCodeStar(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCodeStar(array $args = [])
 * @method \WP_Media_Folder\Aws\CognitoIdentity\CognitoIdentityClient createCognitoIdentity(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCognitoIdentity(array $args = [])
 * @method \WP_Media_Folder\Aws\CognitoIdentityProvider\CognitoIdentityProviderClient createCognitoIdentityProvider(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCognitoIdentityProvider(array $args = [])
 * @method \WP_Media_Folder\Aws\CognitoSync\CognitoSyncClient createCognitoSync(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCognitoSync(array $args = [])
 * @method \WP_Media_Folder\Aws\Comprehend\ComprehendClient createComprehend(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionComprehend(array $args = [])
 * @method \WP_Media_Folder\Aws\ComprehendMedical\ComprehendMedicalClient createComprehendMedical(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionComprehendMedical(array $args = [])
 * @method \WP_Media_Folder\Aws\ConfigService\ConfigServiceClient createConfigService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionConfigService(array $args = [])
 * @method \WP_Media_Folder\Aws\Connect\ConnectClient createConnect(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionConnect(array $args = [])
 * @method \WP_Media_Folder\Aws\CostExplorer\CostExplorerClient createCostExplorer(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCostExplorer(array $args = [])
 * @method \WP_Media_Folder\Aws\CostandUsageReportService\CostandUsageReportServiceClient createCostandUsageReportService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionCostandUsageReportService(array $args = [])
 * @method \WP_Media_Folder\Aws\DAX\DAXClient createDAX(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDAX(array $args = [])
 * @method \WP_Media_Folder\Aws\DLM\DLMClient createDLM(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDLM(array $args = [])
 * @method \WP_Media_Folder\Aws\DataPipeline\DataPipelineClient createDataPipeline(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDataPipeline(array $args = [])
 * @method \WP_Media_Folder\Aws\DataSync\DataSyncClient createDataSync(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDataSync(array $args = [])
 * @method \WP_Media_Folder\Aws\DatabaseMigrationService\DatabaseMigrationServiceClient createDatabaseMigrationService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDatabaseMigrationService(array $args = [])
 * @method \WP_Media_Folder\Aws\DeviceFarm\DeviceFarmClient createDeviceFarm(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDeviceFarm(array $args = [])
 * @method \WP_Media_Folder\Aws\DirectConnect\DirectConnectClient createDirectConnect(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDirectConnect(array $args = [])
 * @method \WP_Media_Folder\Aws\DirectoryService\DirectoryServiceClient createDirectoryService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDirectoryService(array $args = [])
 * @method \WP_Media_Folder\Aws\DynamoDb\DynamoDbClient createDynamoDb(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDynamoDb(array $args = [])
 * @method \WP_Media_Folder\Aws\DynamoDbStreams\DynamoDbStreamsClient createDynamoDbStreams(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionDynamoDbStreams(array $args = [])
 * @method \WP_Media_Folder\Aws\EKS\EKSClient createEKS(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionEKS(array $args = [])
 * @method \WP_Media_Folder\Aws\Ec2\Ec2Client createEc2(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionEc2(array $args = [])
 * @method \WP_Media_Folder\Aws\Ecr\EcrClient createEcr(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionEcr(array $args = [])
 * @method \WP_Media_Folder\Aws\Ecs\EcsClient createEcs(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionEcs(array $args = [])
 * @method \WP_Media_Folder\Aws\Efs\EfsClient createEfs(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionEfs(array $args = [])
 * @method \WP_Media_Folder\Aws\ElastiCache\ElastiCacheClient createElastiCache(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionElastiCache(array $args = [])
 * @method \WP_Media_Folder\Aws\ElasticBeanstalk\ElasticBeanstalkClient createElasticBeanstalk(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionElasticBeanstalk(array $args = [])
 * @method \WP_Media_Folder\Aws\ElasticLoadBalancing\ElasticLoadBalancingClient createElasticLoadBalancing(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionElasticLoadBalancing(array $args = [])
 * @method \WP_Media_Folder\Aws\ElasticLoadBalancingV2\ElasticLoadBalancingV2Client createElasticLoadBalancingV2(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionElasticLoadBalancingV2(array $args = [])
 * @method \WP_Media_Folder\Aws\ElasticTranscoder\ElasticTranscoderClient createElasticTranscoder(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionElasticTranscoder(array $args = [])
 * @method \WP_Media_Folder\Aws\ElasticsearchService\ElasticsearchServiceClient createElasticsearchService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionElasticsearchService(array $args = [])
 * @method \WP_Media_Folder\Aws\Emr\EmrClient createEmr(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionEmr(array $args = [])
 * @method \WP_Media_Folder\Aws\FMS\FMSClient createFMS(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionFMS(array $args = [])
 * @method \WP_Media_Folder\Aws\FSx\FSxClient createFSx(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionFSx(array $args = [])
 * @method \WP_Media_Folder\Aws\Firehose\FirehoseClient createFirehose(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionFirehose(array $args = [])
 * @method \WP_Media_Folder\Aws\GameLift\GameLiftClient createGameLift(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionGameLift(array $args = [])
 * @method \WP_Media_Folder\Aws\Glacier\GlacierClient createGlacier(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionGlacier(array $args = [])
 * @method \WP_Media_Folder\Aws\GlobalAccelerator\GlobalAcceleratorClient createGlobalAccelerator(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionGlobalAccelerator(array $args = [])
 * @method \WP_Media_Folder\Aws\Glue\GlueClient createGlue(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionGlue(array $args = [])
 * @method \WP_Media_Folder\Aws\Greengrass\GreengrassClient createGreengrass(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionGreengrass(array $args = [])
 * @method \WP_Media_Folder\Aws\GuardDuty\GuardDutyClient createGuardDuty(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionGuardDuty(array $args = [])
 * @method \WP_Media_Folder\Aws\Health\HealthClient createHealth(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionHealth(array $args = [])
 * @method \WP_Media_Folder\Aws\Iam\IamClient createIam(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionIam(array $args = [])
 * @method \WP_Media_Folder\Aws\ImportExport\ImportExportClient createImportExport(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionImportExport(array $args = [])
 * @method \WP_Media_Folder\Aws\Inspector\InspectorClient createInspector(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionInspector(array $args = [])
 * @method \WP_Media_Folder\Aws\IoT1ClickDevicesService\IoT1ClickDevicesServiceClient createIoT1ClickDevicesService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionIoT1ClickDevicesService(array $args = [])
 * @method \WP_Media_Folder\Aws\IoT1ClickProjects\IoT1ClickProjectsClient createIoT1ClickProjects(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionIoT1ClickProjects(array $args = [])
 * @method \WP_Media_Folder\Aws\IoTAnalytics\IoTAnalyticsClient createIoTAnalytics(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionIoTAnalytics(array $args = [])
 * @method \WP_Media_Folder\Aws\IoTJobsDataPlane\IoTJobsDataPlaneClient createIoTJobsDataPlane(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionIoTJobsDataPlane(array $args = [])
 * @method \WP_Media_Folder\Aws\Iot\IotClient createIot(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionIot(array $args = [])
 * @method \WP_Media_Folder\Aws\IotDataPlane\IotDataPlaneClient createIotDataPlane(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionIotDataPlane(array $args = [])
 * @method \WP_Media_Folder\Aws\Kafka\KafkaClient createKafka(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKafka(array $args = [])
 * @method \WP_Media_Folder\Aws\Kinesis\KinesisClient createKinesis(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKinesis(array $args = [])
 * @method \WP_Media_Folder\Aws\KinesisAnalytics\KinesisAnalyticsClient createKinesisAnalytics(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKinesisAnalytics(array $args = [])
 * @method \WP_Media_Folder\Aws\KinesisAnalyticsV2\KinesisAnalyticsV2Client createKinesisAnalyticsV2(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKinesisAnalyticsV2(array $args = [])
 * @method \WP_Media_Folder\Aws\KinesisVideo\KinesisVideoClient createKinesisVideo(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKinesisVideo(array $args = [])
 * @method \WP_Media_Folder\Aws\KinesisVideoArchivedMedia\KinesisVideoArchivedMediaClient createKinesisVideoArchivedMedia(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKinesisVideoArchivedMedia(array $args = [])
 * @method \WP_Media_Folder\Aws\KinesisVideoMedia\KinesisVideoMediaClient createKinesisVideoMedia(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKinesisVideoMedia(array $args = [])
 * @method \WP_Media_Folder\Aws\Kms\KmsClient createKms(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionKms(array $args = [])
 * @method \WP_Media_Folder\Aws\Lambda\LambdaClient createLambda(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionLambda(array $args = [])
 * @method \WP_Media_Folder\Aws\LexModelBuildingService\LexModelBuildingServiceClient createLexModelBuildingService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionLexModelBuildingService(array $args = [])
 * @method \WP_Media_Folder\Aws\LexRuntimeService\LexRuntimeServiceClient createLexRuntimeService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionLexRuntimeService(array $args = [])
 * @method \WP_Media_Folder\Aws\LicenseManager\LicenseManagerClient createLicenseManager(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionLicenseManager(array $args = [])
 * @method \WP_Media_Folder\Aws\Lightsail\LightsailClient createLightsail(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionLightsail(array $args = [])
 * @method \WP_Media_Folder\Aws\MQ\MQClient createMQ(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMQ(array $args = [])
 * @method \WP_Media_Folder\Aws\MTurk\MTurkClient createMTurk(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMTurk(array $args = [])
 * @method \WP_Media_Folder\Aws\MachineLearning\MachineLearningClient createMachineLearning(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMachineLearning(array $args = [])
 * @method \WP_Media_Folder\Aws\Macie\MacieClient createMacie(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMacie(array $args = [])
 * @method \WP_Media_Folder\Aws\MarketplaceCommerceAnalytics\MarketplaceCommerceAnalyticsClient createMarketplaceCommerceAnalytics(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMarketplaceCommerceAnalytics(array $args = [])
 * @method \WP_Media_Folder\Aws\MarketplaceEntitlementService\MarketplaceEntitlementServiceClient createMarketplaceEntitlementService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMarketplaceEntitlementService(array $args = [])
 * @method \WP_Media_Folder\Aws\MarketplaceMetering\MarketplaceMeteringClient createMarketplaceMetering(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMarketplaceMetering(array $args = [])
 * @method \WP_Media_Folder\Aws\MediaConnect\MediaConnectClient createMediaConnect(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMediaConnect(array $args = [])
 * @method \WP_Media_Folder\Aws\MediaConvert\MediaConvertClient createMediaConvert(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMediaConvert(array $args = [])
 * @method \WP_Media_Folder\Aws\MediaLive\MediaLiveClient createMediaLive(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMediaLive(array $args = [])
 * @method \WP_Media_Folder\Aws\MediaPackage\MediaPackageClient createMediaPackage(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMediaPackage(array $args = [])
 * @method \WP_Media_Folder\Aws\MediaStore\MediaStoreClient createMediaStore(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMediaStore(array $args = [])
 * @method \WP_Media_Folder\Aws\MediaStoreData\MediaStoreDataClient createMediaStoreData(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMediaStoreData(array $args = [])
 * @method \WP_Media_Folder\Aws\MediaTailor\MediaTailorClient createMediaTailor(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMediaTailor(array $args = [])
 * @method \WP_Media_Folder\Aws\MigrationHub\MigrationHubClient createMigrationHub(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMigrationHub(array $args = [])
 * @method \WP_Media_Folder\Aws\Mobile\MobileClient createMobile(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionMobile(array $args = [])
 * @method \WP_Media_Folder\Aws\Neptune\NeptuneClient createNeptune(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionNeptune(array $args = [])
 * @method \WP_Media_Folder\Aws\OpsWorks\OpsWorksClient createOpsWorks(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionOpsWorks(array $args = [])
 * @method \WP_Media_Folder\Aws\OpsWorksCM\OpsWorksCMClient createOpsWorksCM(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionOpsWorksCM(array $args = [])
 * @method \WP_Media_Folder\Aws\Organizations\OrganizationsClient createOrganizations(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionOrganizations(array $args = [])
 * @method \WP_Media_Folder\Aws\PI\PIClient createPI(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionPI(array $args = [])
 * @method \WP_Media_Folder\Aws\Pinpoint\PinpointClient createPinpoint(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionPinpoint(array $args = [])
 * @method \WP_Media_Folder\Aws\PinpointEmail\PinpointEmailClient createPinpointEmail(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionPinpointEmail(array $args = [])
 * @method \WP_Media_Folder\Aws\PinpointSMSVoice\PinpointSMSVoiceClient createPinpointSMSVoice(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionPinpointSMSVoice(array $args = [])
 * @method \WP_Media_Folder\Aws\Polly\PollyClient createPolly(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionPolly(array $args = [])
 * @method \WP_Media_Folder\Aws\Pricing\PricingClient createPricing(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionPricing(array $args = [])
 * @method \WP_Media_Folder\Aws\QuickSight\QuickSightClient createQuickSight(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionQuickSight(array $args = [])
 * @method \WP_Media_Folder\Aws\RAM\RAMClient createRAM(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRAM(array $args = [])
 * @method \WP_Media_Folder\Aws\RDSDataService\RDSDataServiceClient createRDSDataService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRDSDataService(array $args = [])
 * @method \WP_Media_Folder\Aws\Rds\RdsClient createRds(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRds(array $args = [])
 * @method \WP_Media_Folder\Aws\Redshift\RedshiftClient createRedshift(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRedshift(array $args = [])
 * @method \WP_Media_Folder\Aws\Rekognition\RekognitionClient createRekognition(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRekognition(array $args = [])
 * @method \WP_Media_Folder\Aws\ResourceGroups\ResourceGroupsClient createResourceGroups(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionResourceGroups(array $args = [])
 * @method \WP_Media_Folder\Aws\ResourceGroupsTaggingAPI\ResourceGroupsTaggingAPIClient createResourceGroupsTaggingAPI(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionResourceGroupsTaggingAPI(array $args = [])
 * @method \WP_Media_Folder\Aws\RoboMaker\RoboMakerClient createRoboMaker(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRoboMaker(array $args = [])
 * @method \WP_Media_Folder\Aws\Route53\Route53Client createRoute53(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRoute53(array $args = [])
 * @method \WP_Media_Folder\Aws\Route53Domains\Route53DomainsClient createRoute53Domains(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRoute53Domains(array $args = [])
 * @method \WP_Media_Folder\Aws\Route53Resolver\Route53ResolverClient createRoute53Resolver(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionRoute53Resolver(array $args = [])
 * @method \WP_Media_Folder\Aws\S3\S3Client createS3(array $args = [])
 * @method \WP_Media_Folder\Aws\S3\S3MultiRegionClient createMultiRegionS3(array $args = [])
 * @method \WP_Media_Folder\Aws\S3Control\S3ControlClient createS3Control(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionS3Control(array $args = [])
 * @method \WP_Media_Folder\Aws\SageMaker\SageMakerClient createSageMaker(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSageMaker(array $args = [])
 * @method \WP_Media_Folder\Aws\SageMakerRuntime\SageMakerRuntimeClient createSageMakerRuntime(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSageMakerRuntime(array $args = [])
 * @method \WP_Media_Folder\Aws\SecretsManager\SecretsManagerClient createSecretsManager(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSecretsManager(array $args = [])
 * @method \WP_Media_Folder\Aws\SecurityHub\SecurityHubClient createSecurityHub(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSecurityHub(array $args = [])
 * @method \WP_Media_Folder\Aws\ServerlessApplicationRepository\ServerlessApplicationRepositoryClient createServerlessApplicationRepository(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionServerlessApplicationRepository(array $args = [])
 * @method \WP_Media_Folder\Aws\ServiceCatalog\ServiceCatalogClient createServiceCatalog(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionServiceCatalog(array $args = [])
 * @method \WP_Media_Folder\Aws\ServiceDiscovery\ServiceDiscoveryClient createServiceDiscovery(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionServiceDiscovery(array $args = [])
 * @method \WP_Media_Folder\Aws\Ses\SesClient createSes(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSes(array $args = [])
 * @method \WP_Media_Folder\Aws\Sfn\SfnClient createSfn(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSfn(array $args = [])
 * @method \WP_Media_Folder\Aws\Shield\ShieldClient createShield(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionShield(array $args = [])
 * @method \WP_Media_Folder\Aws\Sms\SmsClient createSms(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSms(array $args = [])
 * @method \WP_Media_Folder\Aws\SnowBall\SnowBallClient createSnowBall(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSnowBall(array $args = [])
 * @method \WP_Media_Folder\Aws\Sns\SnsClient createSns(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSns(array $args = [])
 * @method \WP_Media_Folder\Aws\Sqs\SqsClient createSqs(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSqs(array $args = [])
 * @method \WP_Media_Folder\Aws\Ssm\SsmClient createSsm(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSsm(array $args = [])
 * @method \WP_Media_Folder\Aws\StorageGateway\StorageGatewayClient createStorageGateway(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionStorageGateway(array $args = [])
 * @method \WP_Media_Folder\Aws\Sts\StsClient createSts(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSts(array $args = [])
 * @method \WP_Media_Folder\Aws\Support\SupportClient createSupport(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSupport(array $args = [])
 * @method \WP_Media_Folder\Aws\Swf\SwfClient createSwf(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionSwf(array $args = [])
 * @method \WP_Media_Folder\Aws\TranscribeService\TranscribeServiceClient createTranscribeService(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionTranscribeService(array $args = [])
 * @method \WP_Media_Folder\Aws\Transfer\TransferClient createTransfer(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionTransfer(array $args = [])
 * @method \WP_Media_Folder\Aws\Translate\TranslateClient createTranslate(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionTranslate(array $args = [])
 * @method \WP_Media_Folder\Aws\Waf\WafClient createWaf(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionWaf(array $args = [])
 * @method \WP_Media_Folder\Aws\WafRegional\WafRegionalClient createWafRegional(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionWafRegional(array $args = [])
 * @method \WP_Media_Folder\Aws\WorkDocs\WorkDocsClient createWorkDocs(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionWorkDocs(array $args = [])
 * @method \WP_Media_Folder\Aws\WorkMail\WorkMailClient createWorkMail(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionWorkMail(array $args = [])
 * @method \WP_Media_Folder\Aws\WorkSpaces\WorkSpacesClient createWorkSpaces(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionWorkSpaces(array $args = [])
 * @method \WP_Media_Folder\Aws\XRay\XRayClient createXRay(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionXRay(array $args = [])
 * @method \WP_Media_Folder\Aws\signer\signerClient createsigner(array $args = [])
 * @method \WP_Media_Folder\Aws\MultiRegionClient createMultiRegionsigner(array $args = [])
 */
class Sdk
{
    const VERSION = '3.80.2';

    /** @var array Arguments for creating clients */
    private $args;

    /**
     * Constructs a new SDK object with an associative array of default
     * client settings.
     *
     * @param array $args
     *
     * @throws \InvalidArgumentException
     * @see WP_Media_Folder\Aws\AwsClient::__construct for a list of available options.
     */
    public function __construct(array $args = [])
    {
        $this->args = $args;

        if (!isset($args['handler']) && !isset($args['http_handler'])) {
            $this->args['http_handler'] = default_http_handler();
        }
    }

    public function __call($name, array $args)
    {
        $args = isset($args[0]) ? $args[0] : [];
        if (strpos($name, 'createMultiRegion') === 0) {
            return $this->createMultiRegionClient(substr($name, 17), $args);
        }

        if (strpos($name, 'create') === 0) {
            return $this->createClient(substr($name, 6), $args);
        }

        throw new \BadMethodCallException("Unknown method: {$name}.");
    }

    /**
     * Get a client by name using an array of constructor options.
     *
     * @param string $name Service name or namespace (e.g., DynamoDb, s3).
     * @param array  $args Arguments to configure the client.
     *
     * @return AwsClientInterface
     * @throws \InvalidArgumentException if any required options are missing or
     *                                   the service is not supported.
     * @see WP_Media_Folder\Aws\AwsClient::__construct for a list of available options for args.
     */
    public function createClient($name, array $args = [])
    {
        // Get information about the service from the manifest file.
        $service = manifest($name);
        $namespace = $service['namespace'];

        // Instantiate the client class.
        $client = "WP_Media_Folder\Aws\\{$namespace}\\{$namespace}Client";
        return new $client($this->mergeArgs($namespace, $service, $args));
    }

    public function createMultiRegionClient($name, array $args = [])
    {
        // Get information about the service from the manifest file.
        $service = manifest($name);
        $namespace = $service['namespace'];

        $klass = "WP_Media_Folder\Aws\\{$namespace}\\{$namespace}MultiRegionClient";
        $klass = class_exists($klass) ? $klass : 'WP_Media_Folder\Aws\\MultiRegionClient';

        return new $klass($this->mergeArgs($namespace, $service, $args));
    }

    private function mergeArgs($namespace, array $manifest, array $args = [])
    {
        // Merge provided args with stored, service-specific args.
        if (isset($this->args[$namespace])) {
            $args += $this->args[$namespace];
        }

        // Provide the endpoint prefix in the args.
        if (!isset($args['service'])) {
            $args['service'] = $manifest['endpoint'];
        }

        return $args + $this->args;
    }

    /**
     * Determine the endpoint prefix from a client namespace.
     *
     * @param string $name Namespace name
     *
     * @return string
     * @internal
     * @deprecated Use the `\WP_Media_Folder\Aws\manifest()` function instead.
     */
    public static function getEndpointPrefix($name)
    {
        return manifest($name)['endpoint'];
    }
}
