<?php
namespace WP_Media_Folder\Aws\Lambda;

use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\CommandInterface;
use WP_Media_Folder\Aws\Middleware;

/**
 * This client is used to interact with AWS Lambda
 *
 * @method \WP_Media_Folder\Aws\Result addLayerVersionPermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addLayerVersionPermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addPermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addPermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createAlias(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createAliasAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createEventSourceMapping(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createEventSourceMappingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createFunction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createFunctionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteAlias(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteAliasAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteEventSourceMapping(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteEventSourceMappingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteFunction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteFunctionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteFunctionConcurrency(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteFunctionConcurrencyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteLayerVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteLayerVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAccountSettings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAccountSettingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getAlias(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getAliasAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getEventSourceMapping(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getEventSourceMappingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getFunction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getFunctionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getFunctionConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getFunctionConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getLayerVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getLayerVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getLayerVersionPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getLayerVersionPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result invoke(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise invokeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result invokeAsync(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise invokeAsyncAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listAliases(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listAliasesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listEventSourceMappings(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listEventSourceMappingsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listFunctions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listFunctionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listLayerVersions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listLayerVersionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listLayers(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listLayersAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listVersionsByFunction(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listVersionsByFunctionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result publishLayerVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise publishLayerVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result publishVersion(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise publishVersionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putFunctionConcurrency(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putFunctionConcurrencyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeLayerVersionPermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeLayerVersionPermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removePermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removePermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateAlias(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateAliasAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateEventSourceMapping(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateEventSourceMappingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateFunctionCode(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateFunctionCodeAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateFunctionConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateFunctionConfigurationAsync(array $args = [])
 */
class LambdaClient extends AwsClient
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $args)
    {
        parent::__construct($args);
        $list = $this->getHandlerList();
        if (extension_loaded('curl')) {
            $list->appendInit($this->getDefaultCurlOptionsMiddleware());
        }
    }

    /**
     * Provides a middleware that sets default Curl options for the command
     *
     * @return callable
     */
    public function getDefaultCurlOptionsMiddleware()
    {
        return Middleware::mapCommand(function (CommandInterface $cmd) {
            $defaultCurlOptions = [
                CURLOPT_TCP_KEEPALIVE => 1,
            ];
            if (!isset($cmd['@http']['curl'])) {
                $cmd['@http']['curl'] = $defaultCurlOptions;
            } else {
                $cmd['@http']['curl'] += $defaultCurlOptions;
            }
            return $cmd;
        });
    }
}
