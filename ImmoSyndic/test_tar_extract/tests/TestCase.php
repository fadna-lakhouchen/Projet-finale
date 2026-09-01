<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Read CSV data from database/seeders/data.
     *
     * @param string $filename
     * @return array
     */
    protected static function getSeedData(string $filename): array
    {
        $path = dirname(__DIR__) . '/database/seeders/data/' . $filename;
        if (!file_exists($path)) {
            return [];
        }

        $items = [];
        if (($handle = fopen($path, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ",");
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($header && $data && count($header) === count($data)) {
                    $items[] = array_combine($header, $data);
                }
            }
            fclose($handle);
        }

        return $items;
    }
}
