<?php
namespace WP_Media_Folder\Aws\Handler\GuzzleV5;

use WP_Media_Folder\GuzzleHttp\Stream\StreamDecoratorTrait;
use WP_Media_Folder\GuzzleHttp\Stream\StreamInterface as GuzzleStreamInterface;
use WP_Media_Folder\Psr\Http\Message\StreamInterface as Psr7StreamInterface;

/**
 * Adapts a PSR-7 Stream to a Guzzle 5 Stream.
 *
 * @codeCoverageIgnore
 */
class GuzzleStream implements GuzzleStreamInterface
{
    use StreamDecoratorTrait;

    /** @var Psr7StreamInterface */
    private $stream;

    public function __construct(Psr7StreamInterface $stream)
    {
        $this->stream = $stream;
    }
}
