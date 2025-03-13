<?php
namespace WP_Media_Folder\Aws\LicenseManager;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS License Manager** service.
 * @method \WP_Media_Folder\Aws\Result createLicenseConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createLicenseConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteLicenseConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteLicenseConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getLicenseConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getLicenseConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getServiceSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getServiceSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listAssociationsForLicenseConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listAssociationsForLicenseConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listLicenseConfigurations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listLicenseConfigurationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listLicenseSpecificationsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listLicenseSpecificationsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listResourceInventory(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listResourceInventoryAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listUsageForLicenseConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listUsageForLicenseConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateLicenseConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateLicenseConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateLicenseSpecificationsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateLicenseSpecificationsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateServiceSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateServiceSettingsAsync(array $args = [])
 */
class LicenseManagerClient extends AwsClient {}
