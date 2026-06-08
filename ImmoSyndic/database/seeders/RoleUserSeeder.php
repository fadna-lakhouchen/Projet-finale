<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@immosyndic.ma'],
            [
                'nom' => 'Rifi',
                'prenom' => 'Mohamed',
                'password' => Hash::make('password'),
                'role' => 'administrateur',
                'email_verified_at' => now(),
            ]
        );

        // Syndic
        User::updateOrCreate(
            ['email' => 'syndic@immosyndic.ma'],
            [
                'nom' => 'Afaiz',
                'prenom' => 'Hassan',
                'password' => Hash::make('password'),
                'role' => 'syndic',
                'email_verified_at' => now(),
            ]
        );

        // Resident
        User::updateOrCreate(
            ['email' => 'resident@immosyndic.ma'],
            [
                'nom' => 'Chrifi',
                'prenom' => 'Hassnae',
                'password' => Hash::make('password'),
                'role' => 'resident',
                'email_verified_at' => now(),
            ]
        );
    }
}
