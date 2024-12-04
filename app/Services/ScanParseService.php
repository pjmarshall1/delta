<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ScanParseService
{
    /**
     * @throws Exception
     */
    public function parseFile(string $filePath): Collection
    {
        if (! is_readable($filePath)) {
            throw new Exception('File not found or not readable');
        }

        $file = fopen((string) $filePath, 'r');
        $headers = [];
        $rows = [];

        while (($row = fgetcsv($file)) !== false) {
            if (empty($headers)) {
                if (isset($row[0]) && str_starts_with($row[0], "\xEF\xBB\xBF")) {
                    $row[0] = substr($row[0], 3);
                }

                $headers = $row;

                continue;
            }

            if (empty($row) || empty($row[0])) {
                continue;
            }

            if (count($row) !== count($headers)) {
                continue; // Skip malformed rows
            }

            $rows[] = array_combine($headers, $row);
        }

        fclose($file);

        return $this->extractData($rows);
    }

    public function extractData($rows): Collection
    {
        return collect($rows)->map(function ($row) {

            $symbol = Str::of($row['Symbol / News'])->after('https://www.warriortrading.com/quote/')->before('/');

            return [
                'time' => $row['Time'],
                'symbol' => $symbol->toString(),
                'price' => (float) $row['Price'],
                'volume' => (int) $row['Volume'],
                'float' => (int) $row['Float'],
                'relative_volume_daily' => (int) $row['Relative Volume(Daily Rate)'],
                'relative_volume_five' => (int) $row['Relative Volume(5 min %)'],
                'gap_percent' => (float) $row['Gap(%)'],
                'change_percent' => (float) $row['Change From Close(%)'],
                'short_interest' => (float) $row['Short Interest'],
                'strategy_name' => $row['Strategy Name'],
            ];
        });
    }
}
