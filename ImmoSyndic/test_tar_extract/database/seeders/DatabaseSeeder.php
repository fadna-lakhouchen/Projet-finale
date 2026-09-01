<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Rôles et Permissions indispensables en production et en local
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Données de test uniquement pour le développement local
        if (app()->environment('local', 'testing')) {
            $this->call([
                CsvUserSeeder::class,
                UserSeeder::class,
                RoleUserSeeder::class,
                ImmeubleSeeder::class,
                AppartementSeeder::class,
                AppartementUserSeeder::class,
                ChargeSeeder::class,
                PaiementSeeder::class,
                IncidentSeeder::class,
                InterventionSeeder::class,
                AnnonceSeeder::class,
                DocumentSeeder::class,
                NotificationSeeder::class,
                AuditLogSeeder::class,
            ]);
        }
    }
}
