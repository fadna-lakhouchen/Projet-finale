<?php

namespace Database\Seeders;

use App\Models\Paiement;
use Illuminate\Database\Seeder;

class PaiementSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/paiements.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                }
            }

            Paiement::unguarded(function () use ($data) {
                Paiement::firstOrCreate(
                    ['id' => $data['id']],
                    $data
                );
            });
        }
    }
}
