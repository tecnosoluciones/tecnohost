<?php

namespace NobunaPlugins\Exceptions;

use Exception;
use Throwable;

class UnableToDetectPackageException extends Exception {
    
    public $package;
    
    public function __construct($package, $message = "", $code = 0, Throwable $previous = null) {
        $this->package = $package;
        parent::__construct($message, $code, $previous);
    }
}
