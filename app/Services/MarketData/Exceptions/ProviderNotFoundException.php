<?php

namespace App\Services\MarketData\Exceptions;

use Exception;

class ProviderNotFoundException extends Exception
{
    protected $message = 'Provider class not found.';

    public function __construct(?string $message = null, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message ?? $this->message, $code, $previous);
    }
}
