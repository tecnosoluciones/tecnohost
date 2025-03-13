<?php
namespace WP_Media_Folder\Aws\Support;

use WP_Media_Folder\Aws\AwsClient;

/**
 * AWS Support client.
 *
 * @method \WP_Media_Folder\Aws\Result addAttachmentsToSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addAttachmentsToSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addCommunicationToCase(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addCommunicationToCaseAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCase(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createCaseAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeAttachment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeAttachmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCases(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeCasesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCommunications(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeCommunicationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeServices(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeServicesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeSeverityLevels(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeSeverityLevelsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTrustedAdvisorCheckRefreshStatuses(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTrustedAdvisorCheckRefreshStatusesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTrustedAdvisorCheckResult(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTrustedAdvisorCheckResultAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTrustedAdvisorCheckSummaries(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTrustedAdvisorCheckSummariesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeTrustedAdvisorChecks(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeTrustedAdvisorChecksAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result refreshTrustedAdvisorCheck(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise refreshTrustedAdvisorCheckAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result resolveCase(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise resolveCaseAsync(array $args = [])
 */
class SupportClient extends AwsClient {}
