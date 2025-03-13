<?php
namespace WP_Media_Folder\Aws\Pinpoint;

use WP_Media_Folder\Aws\Api\ApiProvider;
use WP_Media_Folder\Aws\Api\DocModel;
use WP_Media_Folder\Aws\Api\Service;
use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Pinpoint** service.
 * @method \WP_Media_Folder\Aws\Result createApp(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createAppAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCampaign(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createCampaignAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createExportJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createExportJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createImportJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createImportJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createSegment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createSegmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteAdmChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteAdmChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApnsChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApnsChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApnsSandboxChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApnsSandboxChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApnsVoipChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApnsVoipChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApnsVoipSandboxChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteApnsVoipSandboxChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteApp(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteAppAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBaiduChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBaiduChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCampaign(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteCampaignAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEmailChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEmailChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEventStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEventStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteGcmChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteGcmChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteSegment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteSegmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteSmsChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteSmsChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteUserEndpoints(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteUserEndpointsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteVoiceChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteVoiceChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAdmChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAdmChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApnsChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getApnsChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApnsSandboxChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getApnsSandboxChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApnsVoipChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getApnsVoipChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApnsVoipSandboxChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getApnsVoipSandboxChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApp(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAppAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApplicationSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getApplicationSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getApps(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAppsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBaiduChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBaiduChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCampaign(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCampaignAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCampaignActivities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCampaignActivitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCampaignVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCampaignVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCampaignVersions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCampaignVersionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCampaigns(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCampaignsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getChannels(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getChannelsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getEmailChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getEmailChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getEventStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getEventStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getExportJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getExportJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getExportJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getExportJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getGcmChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getGcmChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getImportJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getImportJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getImportJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getImportJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSegment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSegmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSegmentExportJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSegmentExportJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSegmentImportJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSegmentImportJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSegmentVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSegmentVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSegmentVersions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSegmentVersionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSegments(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSegmentsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getSmsChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getSmsChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getUserEndpoints(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getUserEndpointsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getVoiceChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getVoiceChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result phoneNumberValidate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise phoneNumberValidateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putEventStream(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putEventStreamAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putEvents(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putEventsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendMessages(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendMessagesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendUsersMessages(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendUsersMessagesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateAdmChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateAdmChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateApnsChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateApnsChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateApnsSandboxChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateApnsSandboxChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateApnsVoipChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateApnsVoipChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateApnsVoipSandboxChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateApnsVoipSandboxChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateApplicationSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateApplicationSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateBaiduChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateBaiduChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateCampaign(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateCampaignAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEmailChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEmailChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEndpoint(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEndpointAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEndpointsBatch(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEndpointsBatchAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateGcmChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateGcmChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateSegment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateSegmentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateSmsChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateSmsChannelAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateVoiceChannel(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateVoiceChannelAsync(array $args = [])
 */
class PinpointClient extends AwsClient
{
    private static $nameCollisionOverrides = [
        'GetUserEndpoint' => 'GetEndpoint',
        'GetUserEndpointAsync' => 'GetEndpointAsync',
        'UpdateUserEndpoint' => 'UpdateEndpoint',
        'UpdateUserEndpointAsync' => 'UpdateEndpointAsync',
        'UpdateUserEndpointsBatch' => 'UpdateEndpointsBatch',
        'UpdateUserEndpointsBatchAsync' => 'UpdateEndpointsBatchAsync',
    ];

    public function __call($name, array $args)
    {
        // Overcomes a naming collision with `AwsClient::getEndpoint`.
        if (isset(self::$nameCollisionOverrides[ucfirst($name)])) {
            $name = self::$nameCollisionOverrides[ucfirst($name)];
        }

        return parent::__call($name, $args);
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function applyDocFilters(array $api, array $docs)
    {
        foreach (self::$nameCollisionOverrides as $overrideName => $operationName) {
            if (substr($overrideName, -5) === 'Async') {
                continue;
            }
            // Overcomes a naming collision with `AwsClient::getEndpoint`.
            $api['operations'][$overrideName] = $api['operations'][$operationName];
            $docs['operations'][$overrideName] = $docs['operations'][$operationName];
            unset($api['operations'][$operationName], $docs['operations'][$operationName]);
        }
        ksort($api['operations']);

        return [
            new Service($api, ApiProvider::defaultProvider()),
            new DocModel($docs)
        ];
    }
}
