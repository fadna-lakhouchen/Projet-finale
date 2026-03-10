<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/users.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            
            // Handle JSON and NULLs
            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                } elseif (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
                    $data[$key] = json_decode($value, true);
                }
            }

            User::unguarded(function () use ($data) {
                User::firstOrCreate(
                    ['id' => $data['id']],
                    $data
                );
            });
        }
    }
}
