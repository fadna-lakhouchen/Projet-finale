<?php

namespace Database\Seeders;

use App\Models\Immeuble;
use Illuminate\Database\Seeder;

class ImmeubleSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/immeubles.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                }
            }

            Immeuble::unguarded(function () use ($data) {
                Immeuble::firstOrCreate(
                    ['id' => $data['id']],
                    $data
                );
            });
        }
    }
}
