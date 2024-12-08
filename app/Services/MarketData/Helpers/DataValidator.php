<?php

namespace App\Services\MarketData\Helpers;

use Exception;

class DataValidator
{
    /**
     * Validate that the data contains the required keys.
     *
     * @throws Exception
     */
    public function validateKeys(array $data, array $requiredKeys, string $context): void
    {
        if (empty($data)) {
            throw new Exception("No data found for the given parameters in {$context}");
        }

        if (! empty(array_diff_key(array_flip($requiredKeys), $data[0] ?? $data))) {
            throw new Exception("Provider is missing required keys in {$context}");
        }
    }
}
