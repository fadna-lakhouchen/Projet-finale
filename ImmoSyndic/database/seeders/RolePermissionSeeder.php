<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des rôles
        $adminRole = Role::firstOrCreate(['name' => 'administrateur']);
        $syndicRole = Role::firstOrCreate(['name' => 'syndic']);
        $residentRole = Role::firstOrCreate(['name' => 'resident']);

        // Migration des utilisateurs existants
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'administrateur') {
                $user->assignRole($adminRole);
            } elseif ($user->role === 'syndic') {
                $user->assignRole($syndicRole);
            } elseif ($user->role === 'resident') {
                $user->assignRole($residentRole);
            }
        }
    }
}
