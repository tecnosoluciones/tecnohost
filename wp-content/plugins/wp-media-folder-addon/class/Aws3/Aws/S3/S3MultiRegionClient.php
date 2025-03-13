<?php
namespace WP_Media_Folder\Aws\S3;

use WP_Media_Folder\Aws\CacheInterface;
use WP_Media_Folder\Aws\CommandInterface;
use WP_Media_Folder\Aws\LruArrayCache;
use WP_Media_Folder\Aws\MultiRegionClient as BaseClient;
use WP_Media_Folder\Aws\Exception\AwsException;
use WP_Media_Folder\Aws\S3\Exception\PermanentRedirectException;
use WP_Media_Folder\GuzzleHttp\Promise;

/**
 * **Amazon Simple Storage Service** multi-region client.
 *
 * @method \WP_Media_Folder\Aws\Result abortMultipartUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise abortMultipartUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result completeMultipartUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise completeMultipartUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result copyObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise copyObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createBucket(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createBucketAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createMultipartUpload(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createMultipartUploadAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucket(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketAnalyticsConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketAnalyticsConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketCors(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketCorsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketEncryption(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketEncryptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketInventoryConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketInventoryConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketLifecycle(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketLifecycleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketMetricsConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketMetricsConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketReplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketReplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketTagging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketTaggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBucketWebsite(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBucketWebsiteAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteObjectTagging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteObjectTaggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteObjects(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteObjectsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deletePublicAccessBlock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deletePublicAccessBlockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketAccelerateConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketAccelerateConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketAcl(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketAclAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketAnalyticsConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketAnalyticsConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketCors(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketCorsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketEncryption(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketEncryptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketInventoryConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketInventoryConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketLifecycle(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketLifecycleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketLifecycleConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketLifecycleConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketLocation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketLocationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketLogging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketLoggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketMetricsConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketMetricsConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketNotification(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketNotificationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketNotificationConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketNotificationConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketPolicyStatus(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketPolicyStatusAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketReplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketReplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketRequestPayment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketRequestPaymentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketTagging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketTaggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketVersioning(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketVersioningAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getBucketWebsite(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getBucketWebsiteAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObjectAcl(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectAclAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObjectLegalHold(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectLegalHoldAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObjectLockConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectLockConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObjectRetention(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectRetentionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObjectTagging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectTaggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getObjectTorrent(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getObjectTorrentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getPublicAccessBlock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getPublicAccessBlockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result headBucket(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise headBucketAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result headObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise headObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBucketAnalyticsConfigurations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBucketAnalyticsConfigurationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBucketInventoryConfigurations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBucketInventoryConfigurationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBucketMetricsConfigurations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBucketMetricsConfigurationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listBuckets(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listBucketsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listMultipartUploads(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listMultipartUploadsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listObjectVersions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listObjectVersionsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listObjects(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listObjectsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listObjectsV2(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listObjectsV2Async(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listParts(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPartsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketAccelerateConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketAccelerateConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketAcl(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketAclAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketAnalyticsConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketAnalyticsConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketCors(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketCorsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketEncryption(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketEncryptionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketInventoryConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketInventoryConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketLifecycle(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketLifecycleAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketLifecycleConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketLifecycleConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketLogging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketLoggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketMetricsConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketMetricsConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketNotification(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketNotificationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketNotificationConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketNotificationConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketPolicy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketPolicyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketReplication(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketReplicationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketRequestPayment(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketRequestPaymentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketTagging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketTaggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketVersioning(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketVersioningAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putBucketWebsite(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putBucketWebsiteAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putObjectAcl(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putObjectAclAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putObjectLegalHold(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putObjectLegalHoldAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putObjectLockConfiguration(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putObjectLockConfigurationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putObjectRetention(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putObjectRetentionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putObjectTagging(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putObjectTaggingAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result putPublicAccessBlock(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise putPublicAccessBlockAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result restoreObject(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreObjectAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result selectObjectContent(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise selectObjectContentAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result uploadPart(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise uploadPartAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result uploadPartCopy(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise uploadPartCopyAsync(array $args = [])
 */
class S3MultiRegionClient extends BaseClient implements S3ClientInterface
{
    use S3ClientTrait;

    /** @var CacheInterface */
    private $cache;

    public static function getArguments()
    {
        $args = parent::getArguments();
        $regionDef = $args['region'] + ['default' => function (array &$args) {
            $availableRegions = array_keys($args['partition']['regions']);
            return end($availableRegions);
        }];
        unset($args['region']);

        return $args + [
            'bucket_region_cache' => [
                'type' => 'config',
                'valid' => [CacheInterface::class],
                'doc' => 'Cache of regions in which given buckets are located.',
                'default' => function () { return new LruArrayCache; },
            ],
            'region' => $regionDef,
        ];
    }

    public function __construct(array $args)
    {
        parent::__construct($args);
        $this->cache = $this->getConfig('bucket_region_cache');

        $this->getHandlerList()->prependInit(
            $this->determineRegionMiddleware(),
            'determine_region'
        );
    }

    private function determineRegionMiddleware()
    {
        return function (callable $handler) {
            return function (CommandInterface $command) use ($handler) {
                $cacheKey = $this->getCacheKey($command['Bucket']);
                if (
                    empty($command['@region']) &&
                    $region = $this->cache->get($cacheKey)
                ) {
                    $command['@region'] = $region;
                }

                return Promise\coroutine(function () use (
                    $handler,
                    $command,
                    $cacheKey
                ) {
                    try {
                        yield $handler($command);
                    } catch (PermanentRedirectException $e) {
                        if (empty($command['Bucket'])) {
                            throw $e;
                        }
                        $result = $e->getResult();
                        $region = null;
                        if (isset($result['@metadata']['headers']['x-amz-bucket-region'])) {
                            $region = $result['@metadata']['headers']['x-amz-bucket-region'];
                            $this->cache->set($cacheKey, $region);
                        } else {
                            $region = (yield $this->determineBucketRegionAsync(
                                $command['Bucket']
                            ));
                        }

                        $command['@region'] = $region;
                        yield $handler($command);
                    } catch (AwsException $e) {
                        if ($e->getAwsErrorCode() === 'AuthorizationHeaderMalformed') {
                            $region = $this->determineBucketRegionFromExceptionBody(
                                $e->getResponse()
                            );
                            if (!empty($region)) {
                                $this->cache->set($cacheKey, $region);

                                $command['@region'] = $region;
                                yield $handler($command);
                            } else {
                                throw $e;
                            }
                        } else {
                            throw $e;
                        }
                    }
                });
            };
        };
    }

    public function createPresignedRequest(CommandInterface $command, $expires)
    {
        if (empty($command['Bucket'])) {
            throw new \InvalidArgumentException('The S3\\MultiRegionClient'
                . ' cannot create presigned requests for commands without a'
                . ' specified bucket.');
        }

        /** @var S3ClientInterface $client */
        $client = $this->getClientFromPool(
            $this->determineBucketRegion($command['Bucket'])
        );
        return $client->createPresignedRequest(
            $client->getCommand($command->getName(), $command->toArray()),
            $expires
        );
    }

    public function getObjectUrl($bucket, $key)
    {
        /** @var S3Client $regionalClient */
        $regionalClient = $this->getClientFromPool(
            $this->determineBucketRegion($bucket)
        );

        return $regionalClient->getObjectUrl($bucket, $key);
    }

    public function determineBucketRegionAsync($bucketName)
    {
        $cacheKey = $this->getCacheKey($bucketName);
        if ($cached = $this->cache->get($cacheKey)) {
            return Promise\promise_for($cached);
        }

        /** @var S3ClientInterface $regionalClient */
        $regionalClient = $this->getClientFromPool();
        return $regionalClient->determineBucketRegionAsync($bucketName)
            ->then(
                function ($region) use ($cacheKey) {
                    $this->cache->set($cacheKey, $region);

                    return $region;
                }
            );
    }

    private function getCacheKey($bucketName)
    {
        return "aws:s3:{$bucketName}:location";
    }
}
