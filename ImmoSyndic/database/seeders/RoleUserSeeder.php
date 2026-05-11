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
        User::create([
            'nom' => 'Rifi',
            'prenom' => 'Mohamed',
            'email' => 'admin@immosyndic.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Syndic
        User::create([
            'nom' => 'Afaiz',
            'prenom' => 'Hassan',
            'email' => 'syndic@immosyndic.ma',
            'password' => Hash::make('password'),
            'role' => 'syndic',
        ]);

        // Resident
        User::create([
            'nom' => 'Chrifi',
            'prenom' => 'Hassnae',
            'email' => 'resident@immosyndic.ma',
            'password' => Hash::make('password'),
            'role' => 'resident',
        ]);
    }
}
