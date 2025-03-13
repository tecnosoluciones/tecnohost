<?php
namespace WP_Media_Folder\Aws\Ses;

use WP_Media_Folder\Aws\Api\ApiProvider;
use WP_Media_Folder\Aws\Api\DocModel;
use WP_Media_Folder\Aws\Api\Service;
use WP_Media_Folder\Aws\Credentials\CredentialsInterface;

/**
 * This client is used to interact with the **Amazon Simple Email Service (Amazon SES)**.
 *
 * @method \WP_Media_Folder\Aws\Result cloneReceiptRuleSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise cloneReceiptRuleSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createConfigurationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createConfigurationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createConfigurationSetEventDestination(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createConfigurationSetEventDestinationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createConfigurationSetTrackingOptions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createConfigurationSetTrackingOptionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCustomVerificationEmailTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createReceiptFilter(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createReceiptFilterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createReceiptRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createReceiptRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createReceiptRuleSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createReceiptRuleSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteConfigurationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteConfigurationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteConfigurationSetEventDestination(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteConfigurationSetEventDestinationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteConfigurationSetTrackingOptions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteConfigurationSetTrackingOptionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCustomVerificationEmailTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteIdentityPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteIdentityPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteReceiptFilter(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteReceiptFilterAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteReceiptRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteReceiptRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteReceiptRuleSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteReceiptRuleSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteVerifiedEmailAddress(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteVerifiedEmailAddressAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeActiveReceiptRuleSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeActiveReceiptRuleSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeConfigurationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeConfigurationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeReceiptRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeReceiptRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeReceiptRuleSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeReceiptRuleSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAccountSendingEnabled(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAccountSendingEnabledAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCustomVerificationEmailTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getIdentityDkimAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdentityDkimAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getIdentityMailFromDomainAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdentityMailFromDomainAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getIdentityNotificationAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdentityNotificationAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getIdentityPolicies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdentityPoliciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getIdentityVerificationAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getIdentityVerificationAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSendQuota(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSendQuotaAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSendStatistics(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSendStatisticsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listConfigurationSets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listConfigurationSetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listCustomVerificationEmailTemplates(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listCustomVerificationEmailTemplatesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listIdentities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listIdentitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listIdentityPolicies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listIdentityPoliciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listReceiptFilters(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listReceiptFiltersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listReceiptRuleSets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listReceiptRuleSetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTemplates(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTemplatesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listVerifiedEmailAddresses(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listVerifiedEmailAddressesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putIdentityPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putIdentityPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result reorderReceiptRuleSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise reorderReceiptRuleSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendBounce(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendBounceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendBulkTemplatedEmail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendBulkTemplatedEmailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendCustomVerificationEmail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendCustomVerificationEmailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendEmail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendEmailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendRawEmail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendRawEmailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendTemplatedEmail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendTemplatedEmailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setActiveReceiptRuleSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setActiveReceiptRuleSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setIdentityDkimEnabled(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setIdentityDkimEnabledAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setIdentityFeedbackForwardingEnabled(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setIdentityFeedbackForwardingEnabledAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setIdentityHeadersInNotificationsEnabled(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setIdentityHeadersInNotificationsEnabledAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setIdentityMailFromDomain(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setIdentityMailFromDomainAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setIdentityNotificationTopic(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setIdentityNotificationTopicAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setReceiptRulePosition(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setReceiptRulePositionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result testRenderTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise testRenderTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateAccountSendingEnabled(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateAccountSendingEnabledAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateConfigurationSetEventDestination(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateConfigurationSetEventDestinationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateConfigurationSetReputationMetricsEnabled(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateConfigurationSetReputationMetricsEnabledAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateConfigurationSetSendingEnabled(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateConfigurationSetSendingEnabledAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateConfigurationSetTrackingOptions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateConfigurationSetTrackingOptionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateCustomVerificationEmailTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateCustomVerificationEmailTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateReceiptRule(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateReceiptRuleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateTemplate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateTemplateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result verifyDomainDkim(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise verifyDomainDkimAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result verifyDomainIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise verifyDomainIdentityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result verifyEmailAddress(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise verifyEmailAddressAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result verifyEmailIdentity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise verifyEmailIdentityAsync(array $args = [])
 */
class SesClient extends \WP_Media_Folder\Aws\AwsClient
{
    /**
     * Create an SMTP password for a given IAM user's credentials.
     *
     * The SMTP username is the Access Key ID for the provided credentials.
     *
     * @link http://docs.aws.amazon.com/ses/latest/DeveloperGuide/smtp-credentials.html#smtp-credentials-convert
     *
     * @param CredentialsInterface $creds
     *
     * @return string
     */
    public static function generateSmtpPassword(CredentialsInterface $creds)
    {
        static $version = "\x02";
        static $algo = 'sha256';
        static $message = 'SendRawEmail';
        $signature = hash_hmac($algo, $message, $creds->getSecretKey(), true);

        return base64_encode($version . $signature);
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function applyDocFilters(array $api, array $docs)
    {
        $b64 = '<div class="alert alert-info">This value will be base64 encoded on your behalf.</div>';

        $docs['shapes']['RawMessage']['append'] = $b64;

        return [
            new Service($api, ApiProvider::defaultProvider()),
            new DocModel($docs)
        ];
    }
}
