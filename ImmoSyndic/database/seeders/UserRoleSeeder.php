<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@immosyndic.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'password' => bcrypt('password'),
                'telephone' => '0600000001',
                'is_active' => true,
                'role' => 'administrateur',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'syndic@immosyndic.com'],
            [
                'nom' => 'Syndic',
                'prenom' => 'Principal',
                'password' => bcrypt('password'),
                'telephone' => '0600000002',
                'is_active' => true,
                'role' => 'syndic',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'resident@immosyndic.com'],
            [
                'nom' => 'Resident',
                'prenom' => 'Test',
                'password' => bcrypt('password'),
                'telephone' => '0600000003',
                'is_active' => true,
                'role' => 'resident',
            ]
        );
    }
}
