<?php
namespace WP_Media_Folder\Aws\Glacier;

use WP_Media_Folder\Aws\Api\ApiProvider;
use WP_Media_Folder\Aws\Api\DocModel;
use WP_Media_Folder\Aws\Api\Service;
use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\CommandInterface;
use WP_Media_Folder\Aws\Exception\CouldNotCreateChecksumException;
use WP_Media_Folder\Aws\HashingStream;
use WP_Media_Folder\Aws\Middleware;
use WP_Media_Folder\Aws\PhpHash;
use WP_Media_Folder\Psr\Http\Message\RequestInterface;

/**
 * This client is used to interact with the **Amazon Glacier** service.
 *
 * @method \WP_Media_Folder\Aws\Result abortMultipartUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise abortMultipartUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result abortVaultLock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise abortVaultLockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result addTagsToVault(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addTagsToVaultAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result completeMultipartUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise completeMultipartUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result completeVaultLock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise completeVaultLockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createVault(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createVaultAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteArchive(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteArchiveAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteVault(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteVaultAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteVaultAccessPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteVaultAccessPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteVaultNotifications(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteVaultNotificationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeVault(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeVaultAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getDataRetrievalPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getDataRetrievalPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getJobOutput(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getJobOutputAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getVaultAccessPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getVaultAccessPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getVaultLock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getVaultLockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getVaultNotifications(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getVaultNotificationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result initiateJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise initiateJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result initiateMultipartUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise initiateMultipartUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result initiateVaultLock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise initiateVaultLockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listMultipartUploads(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listMultipartUploadsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listParts(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPartsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listProvisionedCapacity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listProvisionedCapacityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForVault(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForVaultAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listVaults(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listVaultsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result purchaseProvisionedCapacity(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise purchaseProvisionedCapacityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeTagsFromVault(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeTagsFromVaultAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setDataRetrievalPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setDataRetrievalPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setVaultAccessPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setVaultAccessPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setVaultNotifications(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setVaultNotificationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result uploadArchive(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise uploadArchiveAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result uploadMultipartPart(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise uploadMultipartPartAsync(array $args = [])
 */
class GlacierClient extends AwsClient
{
    public function __construct(array $args)
    {
        parent::__construct($args);

        // Setup middleware.
        $stack = $this->getHandlerList();
        $stack->appendBuild($this->getApiVersionMiddleware(), 'glacier.api_version');
        $stack->appendBuild($this->getChecksumsMiddleware(), 'glacier.checksum');
        $stack->appendBuild(
            Middleware::contentType(['UploadArchive', 'UploadPart']),
            'glacier.content_type'
        );
        $stack->appendInit(
            Middleware::sourceFile($this->getApi(), 'body', 'sourceFile'),
            'glacier.source_file'
        );
    }

    /**
     * {@inheritdoc}
     *
     * Sets the default accountId to "-" for all operations.
     */
    public function getCommand($name, array $args = [])
    {
        return parent::getCommand($name, $args + ['accountId' => '-']);
    }

    /**
     * Creates a middleware that updates a command with the content and tree
     * hash headers for upload operations.
     *
     * @return callable
     * @throws CouldNotCreateChecksumException if the body is not seekable.
     */
    private function getChecksumsMiddleware()
    {
        return function (callable $handler) {
            return function (
                CommandInterface $command,
                RequestInterface $request = null
            ) use ($handler) {
                // Accept "ContentSHA256" with a lowercase "c" to match other Glacier params.
                if (!$command['ContentSHA256'] && $command['contentSHA256']) {
                    $command['ContentSHA256'] = $command['contentSHA256'];
                    unset($command['contentSHA256']);
                }

                // If uploading, then make sure checksums are added.
                $name = $command->getName();
                if (($name === 'UploadArchive' || $name === 'UploadMultipartPart')
                    && (!$command['checksum'] || !$command['ContentSHA256'])
                ) {
                    $body = $request->getBody();
                    if (!$body->isSeekable()) {
                        throw new CouldNotCreateChecksumException('sha256');
                    }

                    // Add a tree hash if not provided.
                    if (!$command['checksum']) {
                        $body = new HashingStream(
                            $body, new TreeHash(),
                            function ($result) use ($command, &$request) {
                                $request = $request->withHeader(
                                    'x-amz-sha256-tree-hash',
                                    bin2hex($result)
                                );
                            }
                        );
                    }

                    // Add a linear content hash if not provided.
                    if (!$command['ContentSHA256']) {
                        $body = new HashingStream(
                            $body, new PhpHash('sha256'),
                            function ($result) use ($command) {
                                $command['ContentSHA256'] = bin2hex($result);
                            }
                        );
                    }

                    // Read the stream in order to calculate the hashes.
                    while (!$body->eof()) {
                        $body->read(1048576);
                    }
                    $body->seek(0);
                }

                // Set the content hash header if a value is in the command.
                if ($command['ContentSHA256']) {
                    $request = $request->withHeader(
                        'x-amz-content-sha256',
                        $command['ContentSHA256']
                    );
                }

                return $handler($command, $request);
            };
        };
    }

    /**
     * Creates a middleware that adds the API version header for all requests.
     *
     * @return callable
     */
    private function getApiVersionMiddleware()
    {
        return function (callable $handler) {
            return function (
                CommandInterface $command,
                RequestInterface $request = null
            ) use ($handler) {
                return $handler($command, $request->withHeader(
                    'x-amz-glacier-version',
                    $this->getApi()->getMetadata('apiVersion')
                ));
            };
        };
    }

    /**
     * @internal
     * @codeCoverageIgnore
     */
    public static function applyDocFilters(array $api, array $docs)
    {
        // Add the SourceFile parameter.
        $docs['shapes']['SourceFile']['base'] = 'The path to a file on disk to use instead of the body parameter.';
        $api['shapes']['SourceFile'] = ['type' => 'string'];
        $api['shapes']['UploadArchiveInput']['members']['sourceFile'] = ['shape' => 'SourceFile'];
        $api['shapes']['UploadMultipartPartInput']['members']['sourceFile'] = ['shape' => 'SourceFile'];

        // Add the ContentSHA256 parameter.
        $docs['shapes']['ContentSHA256']['base'] = 'A SHA256 hash of the content of the request body';
        $api['shapes']['ContentSHA256'] = ['type' => 'string'];
        $api['shapes']['UploadArchiveInput']['members']['contentSHA256'] = ['shape' => 'ContentSHA256'];
        $api['shapes']['UploadMultipartPartInput']['members']['contentSHA256'] = ['shape' => 'ContentSHA256'];

        // Add information about "checksum" and "ContentSHA256" being optional.
        $optional = '<div class="alert alert-info">The SDK will compute this value '
            . 'for you on your behalf if it is not supplied.</div>';
        $docs['shapes']['checksum']['append'] = $optional;
        $docs['shapes']['ContentSHA256']['append'] = $optional;

        // Make "accountId" optional for all operations.
        foreach ($api['operations'] as $operation) {
            $inputShape =& $api['shapes'][$operation['input']['shape']];
            $accountIdIndex = array_search('accountId', $inputShape['required']);
            unset($inputShape['required'][$accountIdIndex]);
        }
        // Add information about the default value for "accountId".
        $optional = '<div class="alert alert-info">The SDK will set this value to "-" by default.</div>';
        foreach ($docs['shapes']['string']['refs'] as $name => &$ref) {
            if (strpos($name, 'accountId')) {
                $ref .= $optional;
            }
        }

        return [
            new Service($api, ApiProvider::defaultProvider()),
            new DocModel($docs)
        ];
    }
}
