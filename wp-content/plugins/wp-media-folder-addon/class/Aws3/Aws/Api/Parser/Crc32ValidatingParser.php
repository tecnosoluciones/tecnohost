<?php
namespace WP_Media_Folder\Aws\Api\Parser;

use WP_Media_Folder\Aws\Api\StructureShape;
use WP_Media_Folder\Aws\CommandInterface;
use WP_Media_Folder\Aws\Exception\AwsException;
use WP_Media_Folder\Psr\Http\Message\ResponseInterface;
use WP_Media_Folder\Psr\Http\Message\StreamInterface;
use WP_Media_Folder\GuzzleHttp\Psr7;

/**
 * @internal Decorates a parser and validates the x-amz-crc32 header.
 */
class Crc32ValidatingParser extends AbstractParser
{
    /**
     * @param callable $parser Parser to wrap.
     */
    public function __construct(callable $parser)
    {
        $this->parser = $parser;
    }

    public function __invoke(
        CommandInterface $command,
        ResponseInterface $response
    ) {
        if ($expected = $response->getHeaderLine('x-amz-crc32')) {
            $hash = hexdec(Psr7\hash($response->getBody(), 'crc32b'));
            if ($expected != $hash) {
                throw new AwsException(
                    "crc32 mismatch. Expected {$expected}, found {$hash}.",
                    $command,
                    [
                        'code'             => 'ClientChecksumMismatch',
                        'connection_error' => true,
                        'response'         => $response
                    ]
                );
            }
        }

        $fn = $this->parser;
        return $fn($command, $response);
    }

    public function parseMemberFromStream(
        StreamInterface $stream,
        StructureShape $member,
        $response
    ) {
        return $this->parser->parseMemberFromStream($stream, $member, $response);
    }
}
