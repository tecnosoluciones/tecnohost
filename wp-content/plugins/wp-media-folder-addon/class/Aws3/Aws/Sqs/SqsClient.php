<?php
namespace WP_Media_Folder\Aws\Sqs;

use WP_Media_Folder\Aws\AwsClient;
use WP_Media_Folder\Aws\CommandInterface;
use WP_Media_Folder\Aws\Sqs\Exception\SqsException;
use WP_Media_Folder\GuzzleHttp\Psr7\Uri;
use WP_Media_Folder\GuzzleHttp\Psr7\UriResolver;
use WP_Media_Folder\Psr\Http\Message\RequestInterface;

/**
 * Client used to interact Amazon Simple Queue Service (Amazon SQS)
 *
 * @method \WP_Media_Folder\Aws\Result addPermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addPermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result changeMessageVisibility(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise changeMessageVisibilityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result changeMessageVisibilityBatch(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise changeMessageVisibilityBatchAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createQueueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteMessage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteMessageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteMessageBatch(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteMessageBatchAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteQueueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getQueueAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getQueueAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getQueueUrl(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getQueueUrlAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listDeadLetterSourceQueues(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listDeadLetterSourceQueuesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listQueueTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listQueueTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listQueues(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listQueuesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result purgeQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise purgeQueueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result receiveMessage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise receiveMessageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removePermission(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removePermissionAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendMessage(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendMessageAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result sendMessageBatch(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise sendMessageBatchAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result setQueueAttributes(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise setQueueAttributesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagQueueAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagQueue(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagQueueAsync(array $args = [])
 */
class SqsClient extends AwsClient
{
    public function __construct(array $config)
    {
        parent::__construct($config);
        $list = $this->getHandlerList();
        $list->appendBuild($this->queueUrl(), 'sqs.queue_url');
        $list->appendSign($this->validateMd5(), 'sqs.md5');
    }

    /**
     * Converts a queue URL into a queue ARN.
     *
     * @param string $queueUrl The queue URL to perform the action on.
     *                         Retrieved when the queue is first created.
     *
     * @return string An ARN representation of the queue URL.
     */
    public function getQueueArn($queueUrl)
    {
        $queueArn = strtr($queueUrl, array(
            'http://'        => 'arn:aws:',
            'https://'       => 'arn:aws:',
            '.amazonaws.com' => '',
            '/'              => ':',
            '.'              => ':',
        ));

        // Cope with SQS' .fifo / :fifo arn inconsistency
        if (substr($queueArn, -5) === ':fifo') {
            $queueArn = substr_replace($queueArn, '.fifo', -5);
        }
        return $queueArn;
    }

    /**
     * Moves the URI of the queue to the URI in the input parameter.
     *
     * @return callable
     */
    private function queueUrl()
    {
        return static function (callable $handler) {
            return function (
                CommandInterface $c,
                RequestInterface $r = null
            ) use ($handler) {
                if ($c->hasParam('QueueUrl')) {
                    $r = $r->withUri(UriResolver::resolve(
                        $r->getUri(),
                        new Uri($c['QueueUrl'])
                    ));
                }
                return $handler($c, $r);
            };
        };
    }

    /**
     * Calculates the expected md5 hash of message attributes according to the encoding
     * scheme detailed in SQS documentation.
     *
     * @param array $message Message containing attributes for validation.
     *                       Retrieved when using MessageAttributeNames on
     *                       ReceiveMessage.
     *
     * @return string|null The md5 hash of the message attributes according to
     *                     the encoding scheme. Returns null when there are no
     *                     attributes.
     * @link http://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-attributes.html#message-attributes-items-validation
     */
    private static function calculateMessageAttributesMd5($message)
    {
        if (empty($message['MessageAttributes'])
            || !is_array($message['MessageAttributes'])
        ) {
            return null;
        }

        ksort($message['MessageAttributes']);
        $attributeValues = "";
        foreach ($message['MessageAttributes'] as $name => $details) {
            $attributeValues .= self::getEncodedStringPiece($name);
            $attributeValues .= self::getEncodedStringPiece($details['DataType']);
            if (substr($details['DataType'], 0, 6) === 'Binary') {
                $attributeValues .= pack('c', 0x02);
                $attributeValues .= self::getEncodedBinaryPiece(
                    $details['BinaryValue']
                );
            } else {
                $attributeValues .= pack('c', 0x01);
                $attributeValues .= self::getEncodedStringPiece(
                    $details['StringValue']
                );
            }
        }

        return md5($attributeValues);
    }

    private static function calculateBodyMd5($message)
    {
        return md5($message['Body']);
    }

    private static function getEncodedStringPiece($piece)
    {
        $utf8Piece = iconv(
            mb_detect_encoding($piece, mb_detect_order(), true),
            "UTF-8",
            $piece
        );
        return self::getFourBytePieceLength($utf8Piece) . $utf8Piece;
    }

    private static function getEncodedBinaryPiece($piece)
    {
        return self::getFourBytePieceLength($piece) . $piece;
    }

    private static function getFourBytePieceLength($piece)
    {
        return pack('N', (int)strlen($piece));
    }

    /**
     * Validates ReceiveMessage body and message attribute MD5s.
     *
     * @return callable
     */
    private function validateMd5()
    {
        return static function (callable $handler) {
            return function (
                CommandInterface $c,
                RequestInterface $r = null
            ) use ($handler) {
                if ($c->getName() !== 'ReceiveMessage') {
                    return $handler($c, $r);
                }

                return $handler($c, $r)
                    ->then(
                        function ($result) use ($c, $r) {
                            foreach ((array) $result['Messages'] as $msg) {
                                $bodyMd5 = self::calculateBodyMd5($msg);
                                if (isset($msg['MD5OfBody'])
                                    && $bodyMd5 !== $msg['MD5OfBody']
                                ) {
                                    throw new SqsException(
                                        sprintf(
                                            'MD5 mismatch. Expected %s, found %s',
                                            $msg['MD5OfBody'],
                                            $bodyMd5
                                        ),
                                        $c,
                                        [
                                            'code' => 'ClientChecksumMismatch',
                                            'request' => $r
                                        ]
                                    );
                                }

                                if (isset($msg['MD5OfMessageAttributes'])) {
                                    $messageAttributesMd5 = self::calculateMessageAttributesMd5($msg);
                                    if ($messageAttributesMd5 !== $msg['MD5OfMessageAttributes']) {
                                        throw new SqsException(
                                            sprintf(
                                                'Attribute MD5 mismatch. Expected %s, found %s',
                                                $msg['MD5OfMessageAttributes'],
                                                $messageAttributesMd5
                                                    ? $messageAttributesMd5
                                                    : 'No Attributes'
                                            ),
                                            $c,
                                            [
                                                'code' => 'ClientChecksumMismatch',
                                                'request' => $r
                                            ]
                                        );
                                    }
                                } else if (isset($msg['MessageAttributes'])) {
                                    throw new SqsException(
                                        sprintf(
                                            'No Attribute MD5 found. Expected %s',
                                            self::calculateMessageAttributesMd5($msg)
                                        ),
                                        $c,
                                        [
                                            'code' => 'ClientChecksumMismatch',
                                            'request' => $r
                                        ]
                                    );
                                }
                            }
                            return $result;
                        }
                    );
            };
        };
    }
}
