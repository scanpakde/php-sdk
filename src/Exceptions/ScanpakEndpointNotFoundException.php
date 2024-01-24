<?php
namespace Scanpak\Exceptions;

use Exception;

class ScanpakEndpointNotFoundException extends Exception
{
    /**
     * ScanpakEndpointNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct(string $message = 'Endpoint not found.', $code = 1000, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}