<?php
namespace WP_Media_Folder\Aws\CloudHsm;

use WP_Media_Folder\Aws\Api\ApiProvider;
use WP_Media_Folder\Aws\Api\DocModel;
use WP_Media_Folder\Aws\Api\Service;
use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with **AWS CloudHSM**.
 *
 * @method \WP_Media_Folder\Aws\Result addTagsToResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addTagsToResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createHapg(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createHapgAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createHsm(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createHsmAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createLunaClient(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createLunaClientAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteHapg(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteHapgAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteHsm(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteHsmAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteLunaClient(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteLunaClientAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeHapg(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeHapgAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeHsm(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeHsmAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeLunaClient(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeLunaClientAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getConfig(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getConfigAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listAvailableZones(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listAvailableZonesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listHapgs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listHapgsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listHsms(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listHsmsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listLunaClients(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listLunaClientsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyHapg(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyHapgAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyHsm(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyHsmAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result modifyLunaClient(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise modifyLunaClientAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeTagsFromResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeTagsFromResourceAsync(array $args = [])
 */
class CloudHsmClient extends AwsClient
{
    public function __call($name, array $args)
    {
        // Overcomes a naming collision with `AwsClient::getConfig`.
        if (lcfirst($name) === 'getConfigFiles') {
            $name = 'GetConfig';
        } elseif (lcfirst($name) === 'getConfigFilesAsync') {
            $name = 'GetConfigAsync';
        }

        return parent::__call($name, $args);
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function applyDocFilters(array $api, array $docs)
    {
        // Overcomes a naming collision with `AwsClient::getConfig`.
        $api['operations']['GetConfigFiles'] = $api['operations']['GetConfig'];
        $docs['operations']['GetConfigFiles'] = $docs['operations']['GetConfig'];
        unset($api['operations']['GetConfig'], $docs['operations']['GetConfig']);
        ksort($api['operations']);

        return [
            new Service($api, ApiProvider::defaultProvider()),
            new DocModel($docs)
        ];
    }
}
