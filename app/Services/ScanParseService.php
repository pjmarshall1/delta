<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class ScanParseService
{
    private $date;

    /**
     * @throws Exception
     */
    public function parseFile(string $filePath, string $date): Collection
    {
        $this->date = $date;

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

        if (empty($rows)) {
            throw new Exception('No alerts were found.');
        }

        return $this->extractData($rows);
    }

    public function extractData($rows): Collection
    {
        return collect($rows)->map(function ($row) {
            $symbol = Str::of($row['Symbol / News'])->after('https://www.warriortrading.com/quote/')->before('/');

            $timestamp = Date::parse($this->date)
                ->setTimeFromTimeString($row['Time'])
                ->shiftTimezone('America/New_York')
                ->utc()->toDateTimeString();

            return [
                'timestamp' => $timestamp,
                'symbol' => $symbol->toString(),
                'price' => $row['Price'] * 10000,
                'volume' => $row['Volume'],
                'float' => $row['Float'],
                'relative_volume_daily' => (int) round((float) $row['Relative Volume(Daily Rate)'], 2) * 100,
                'relative_volume_five' => (int) round((float) $row['Relative Volume(5 min %)'], 2) * 100,
                'gap_percent' => (int) round($row['Gap(%)'] * 100),
                'change_percent' => (int) round($row['Change From Close(%)'] * 100),
                'short_interest' => $row['Short Interest'],
                'strategy_name' => $row['Strategy Name'],
            ];
        });
    }
}
