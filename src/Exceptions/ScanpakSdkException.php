<?php

namespace Scanpak\Exceptions;

use Exception;

class ScanpakSdkException extends Exception
{
    /**
     * ScanpakEndpointNotFoundException constructor.
     * @param  string  $message
     * @param  int  $code
     * @param  Exception  $previous
     */
    public function __construct(string $message = 'Error', $code = 1000, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}