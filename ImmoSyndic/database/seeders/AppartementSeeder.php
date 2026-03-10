<?php

namespace Database\Seeders;

use App\Models\Appartement;
use Illuminate\Database\Seeder;

class AppartementSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/appartements.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                }
            }

            Appartement::unguarded(function () use ($data) {
                Appartement::firstOrCreate(
                    ['id' => $data['id']],
                    $data
                );
            });
        }
    }
}
