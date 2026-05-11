<?php

use App\Models\User;
use App\Models\Immeuble;
use App\Models\Appartement;
use App\Models\Charge;
use App\Models\Paiement;
use App\Models\Incident;
use App\Models\Intervention;
use App\Models\Annonce;
use App\Models\Document;
use App\Models\Notification;
use App\Models\AuditLog;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$models = [
    User::class,
    Immeuble::class,
    Appartement::class,
    Charge::class,
    Paiement::class,
    Incident::class,
    Intervention::class,
    Annonce::class,
    Document::class,
    Notification::class,
    AuditLog::class,
];

echo "Verifying Models...\n";

foreach ($models as $modelClass) {
    try {
        $instance = new $modelClass();
        echo "[OK] $modelClass instantiated.\n";
        
        // Check a relationship for each (spot check)
        if ($modelClass === User::class) {
            if (!method_exists($instance, 'immeubles')) throw new Exception("Missing 'immeubles' relationship in User");
        }
        if ($modelClass === Immeuble::class) {
            if (!method_exists($instance, 'syndic')) throw new Exception("Missing 'syndic' relationship in Immeuble");
        }
    } catch (\Throwable $e) {
        echo "[ERROR] $modelClass: " . $e->getMessage() . "\n";
    }
}

echo "Verification complete.\n";
