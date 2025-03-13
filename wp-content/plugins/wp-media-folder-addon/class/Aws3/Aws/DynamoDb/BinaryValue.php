<?php
namespace WP_Media_Folder\Aws\DynamoDb;

use WP_Media_Folder\GuzzleHttp\Psr7;

/**
 * Special object to represent a DynamoDB binary (B) value.
 */
class BinaryValue implements \JsonSerializable
{
    /** @var string Binary value. */
    private $value;

    /**
     * @param mixed $value A binary value compatible with Guzzle streams.
     *
     * @see WP_Media_Folder\GuzzleHttp\Stream\Stream::factory
     */
    public function __construct($value)
    {
        if (!is_string($value)) {
            $value = Psr7\stream_for($value);
        }
        $this->value = (string) $value;
    }

    public function jsonSerialize()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
