<?php
namespace WP_Media_Folder\Aws\S3Control;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS S3 Control** service.
 * @method \WP_Media_Folder\Aws\Result deletePublicAccessBlock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deletePublicAccessBlockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getPublicAccessBlock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getPublicAccessBlockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putPublicAccessBlock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putPublicAccessBlockAsync(array $args = [])
 */
class S3ControlClient extends AwsClient 
{
    public static function getArguments()
    {
        $args = parent::getArguments();
        return $args + [
            'use_dual_stack_endpoint' => [
                'type' => 'config',
                'valid' => ['bool'],
                'doc' => 'Set to true to send requests to an S3 Control Dual Stack'
                    . ' endpoint by default, which enables IPv6 Protocol.'
                    . ' Can be enabled or disabled on individual operations by setting'
                    . ' \'@use_dual_stack_endpoint\' to true or false.',
                'default' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * In addition to the options available to
     * {@see WP_Media_Folder\Aws\AwsClient::__construct}, S3ControlClient accepts the following
     * option:
     *
     * - use_dual_stack_endpoint: (bool) Set to true to send requests to an S3
     *   Control Dual Stack endpoint by default, which enables IPv6 Protocol.
     *   Can be enabled or disabled on individual operations by setting
     *   '@use_dual_stack_endpoint\' to true or false. Note:
     *   you cannot use it together with an accelerate endpoint.
     *
     * @param array $args
     */
    public function __construct(array $args)
    {
        parent::__construct($args);
        $stack = $this->getHandlerList();
        $stack->appendBuild(
            S3ControlEndpointMiddleware::wrap(
                $this->getRegion(),
                [
                    'dual_stack' => $this->getConfig('use_dual_stack_endpoint'),
                ]
            ),
            's3control.endpoint_middleware'
        );
    }
}
