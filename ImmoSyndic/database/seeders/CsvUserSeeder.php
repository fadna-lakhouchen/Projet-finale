<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CsvUserSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/users.csv');
        if (!file_exists($file)) {
            return;
        }

        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            $role = 'resident';
            $username = explode('@', $data['email'])[0];
            if (str_contains($username, 'admin')) {
                $role = 'administrateur';
            } elseif (str_contains($username, 'syndic')) {
                $role = 'syndic';
            }

            User::unguarded(function () use ($data, $role) {
                User::updateOrCreate(
                    ['id' => $data['id']],
                    [
                        'nom' => $data['nom'],
                        'prenom' => $data['prenom'],
                        'email' => $data['email'],
                        'password' => Hash::make('password'),
                        'telephone' => $data['telephone'] ?? null,
                        'is_active' => (bool)($data['is_active'] ?? true),
                        'role' => $role,
                        'email_verified_at' => $data['email_verified_at'] ?? now(),
                    ]
                );
            });
        }
    }
}
