<?php
namespace WP_Media_Folder\Aws\Route53;

use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\CommandInterface;
use WP_Media_Folder\Psr\Http\Message\RequestInterface;

/**
 * This client is used to interact with the **Amazon Route 53** service.
 *
 * @method \WP_Media_Folder\Aws\Result associateVPCWithHostedZone(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateVPCWithHostedZoneAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result changeResourceRecordSets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise changeResourceRecordSetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result changeTagsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise changeTagsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createHealthCheck(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createHealthCheckAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createHostedZone(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createHostedZoneAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createQueryLoggingConfig(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createQueryLoggingConfigAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createReusableDelegationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createReusableDelegationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createTrafficPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createTrafficPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createTrafficPolicyInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createTrafficPolicyInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createTrafficPolicyVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createTrafficPolicyVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createVPCAssociationAuthorization(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createVPCAssociationAuthorizationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteHealthCheck(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteHealthCheckAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteHostedZone(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteHostedZoneAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteQueryLoggingConfig(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteQueryLoggingConfigAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteReusableDelegationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteReusableDelegationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTrafficPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTrafficPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTrafficPolicyInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTrafficPolicyInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteVPCAssociationAuthorization(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteVPCAssociationAuthorizationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateVPCFromHostedZone(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateVPCFromHostedZoneAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAccountLimit(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAccountLimitAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getChange(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getChangeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCheckerIpRanges(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCheckerIpRangesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getGeoLocation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getGeoLocationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getHealthCheck(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHealthCheckAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getHealthCheckCount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHealthCheckCountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getHealthCheckLastFailureReason(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHealthCheckLastFailureReasonAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getHealthCheckStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHealthCheckStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getHostedZone(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHostedZoneAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getHostedZoneCount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHostedZoneCountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getHostedZoneLimit(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getHostedZoneLimitAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getQueryLoggingConfig(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getQueryLoggingConfigAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getReusableDelegationSet(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getReusableDelegationSetAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getReusableDelegationSetLimit(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getReusableDelegationSetLimitAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTrafficPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTrafficPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTrafficPolicyInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTrafficPolicyInstanceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTrafficPolicyInstanceCount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTrafficPolicyInstanceCountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listGeoLocations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listGeoLocationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listHealthChecks(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listHealthChecksAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listHostedZones(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listHostedZonesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listHostedZonesByName(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listHostedZonesByNameAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listQueryLoggingConfigs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listQueryLoggingConfigsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listResourceRecordSets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listResourceRecordSetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listReusableDelegationSets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listReusableDelegationSetsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTrafficPolicies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTrafficPoliciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTrafficPolicyInstances(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTrafficPolicyInstancesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTrafficPolicyInstancesByHostedZone(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTrafficPolicyInstancesByHostedZoneAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTrafficPolicyInstancesByPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTrafficPolicyInstancesByPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTrafficPolicyVersions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTrafficPolicyVersionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listVPCAssociationAuthorizations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listVPCAssociationAuthorizationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result testDNSAnswer(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise testDNSAnswerAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateHealthCheck(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateHealthCheckAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateHostedZoneComment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateHostedZoneCommentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateTrafficPolicyComment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateTrafficPolicyCommentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateTrafficPolicyInstance(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateTrafficPolicyInstanceAsync(array $args = [])
 */
class Route53Client extends AwsClient
{
    public function __construct(array $args)
    {
        parent::__construct($args);
        $this->getHandlerList()->appendInit($this->cleanIdFn(), 'route53.clean_id');
    }

    private function cleanIdFn()
    {
        return function (callable $handler) {
            return function (CommandInterface $c, RequestInterface $r = null) use ($handler) {
                foreach (['Id', 'HostedZoneId', 'DelegationSetId'] as $clean) {
                    if ($c->hasParam($clean)) {
                        $c[$clean] = $this->cleanId($c[$clean]);
                    }
                }
                return $handler($c, $r);
            };
        };
    }

    private function cleanId($id)
    {
        static $toClean = ['/hostedzone/', '/change/', '/delegationset/'];

        return str_replace($toClean, '', $id);
    }
}
