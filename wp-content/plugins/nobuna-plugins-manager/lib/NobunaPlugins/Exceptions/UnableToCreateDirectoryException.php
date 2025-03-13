<?php

namespace NobunaPlugins\Exceptions;

use Exception;
use Throwable;

class UnableToCreateDirectoryException extends Exception {
    
    public $directory;
    
    public function __construct($directory = "") {
        $this->directory = $directory;
        parent::__construct($directory, 0, NULL);
    }
}
