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
        $this->call([
            UserSeeder::class,
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
