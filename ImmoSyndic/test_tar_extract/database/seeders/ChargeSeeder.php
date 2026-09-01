<?php

namespace Database\Seeders;

use App\Models\Charge;
use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/charges.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                }
            }

            Charge::unguarded(function () use ($data) {
                Charge::firstOrCreate(
                    ['id' => $data['id']],
                    $data
                );
            });
        }
    }
}
