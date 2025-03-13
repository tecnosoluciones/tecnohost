<?php
namespace WP_Media_Folder\Aws\KinesisAnalytics;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Kinesis Analytics** service.
 * @method \WP_Media_Folder\Aws\Result addApplicationCloudWatchLoggingOption(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addApplicationCloudWatchLoggingOptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addApplicationInput(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addApplicationInputAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addApplicationInputProcessingConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addApplicationInputProcessingConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addApplicationOutput(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addApplicationOutputAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addApplicationReferenceDataSource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addApplicationReferenceDataSourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApplicationCloudWatchLoggingOption(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApplicationCloudWatchLoggingOptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApplicationInputProcessingConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApplicationInputProcessingConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApplicationOutput(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApplicationOutputAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApplicationReferenceDataSource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApplicationReferenceDataSourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result discoverInputSchema(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise discoverInputSchemaAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listApplications(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listApplicationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result stopApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise stopApplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateApplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateApplicationAsync(array $args = [])
 */
class KinesisAnalyticsClient extends AwsClient {}
