<?php

namespace App\Services\MarketData\Exceptions;

use Exception;

class InvalidResponseException extends Exception
{
    protected $message = 'Invalid response from provider.';

    public function __construct(?string $message = null, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message ?? $this->message, $code, $previous);
    }
}
