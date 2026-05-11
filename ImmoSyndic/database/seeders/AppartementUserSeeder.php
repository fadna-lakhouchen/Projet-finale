<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppartementUserSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/appartement_user.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                }
            }

            DB::table('appartement_user')->updateOrInsert(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
