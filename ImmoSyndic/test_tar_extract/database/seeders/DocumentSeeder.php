<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/documents.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                }
            }

            Document::unguarded(function () use ($data) {
                Document::firstOrCreate(
                    ['id' => $data['id']],
                    $data
                );
            });
        }
    }
}
