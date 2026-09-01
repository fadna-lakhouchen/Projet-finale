<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@immosyndic.com'],
            [
                'nom' => 'Rifi',
                'prenom' => 'Mohamed',
                'telephone' => '0600000001',
                'role' => 'administrateur',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Syndic
        User::updateOrCreate(
            ['email' => 'syndic@immosyndic.com'],
            [
                'nom' => 'El Khadir',
                'prenom' => 'Youssef',
                'telephone' => '0600000002',
                'role' => 'syndic',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Résident
        User::updateOrCreate(
            ['email' => 'resident@immosyndic.com'],
            [
                'nom' => 'Afaiz',
                'prenom' => 'Hassan',
                'telephone' => '0600000003',
                'role' => 'resident',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
