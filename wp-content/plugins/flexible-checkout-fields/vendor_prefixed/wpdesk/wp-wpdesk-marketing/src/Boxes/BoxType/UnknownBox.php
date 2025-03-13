<?php

/**
 * Simple box.
 *
 * @package WPDesk\Library\Marketing\Abstracts
 */
namespace FcfVendor\WPDesk\Library\Marketing\Boxes\BoxType;

class UnknownBox extends Box
{
    const TYPE = 'unknown';
    /**
     * @param array $args
     *
     * @return string
     */
    public function render(array $args = []): string
    {
        return '<!-- unknown marketing box -->';
    }
}
