<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appartement;
use App\Models\Charge;
use Carbon\Carbon;

class GenerateMonthlyCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-charges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère automatiquement les charges mensuelles de copropriété pour tous les appartements occupés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Carbon::setLocale('fr');
        $this->info("Démarrage de la génération des charges de copropriété...");

        // Fetch all occupied apartments
        $appartements = Appartement::where('statut', 'occupé')->get();
        $count = 0;

        foreach ($appartements as $appt) {
            // Avoid creating duplicate charges for the same apartment in the current month
            $alreadyExists = Charge::where('appartement_id', $appt->id)
                ->whereYear('date_echeance', now()->year)
                ->whereMonth('date_echeance', now()->month)
                ->exists();

            if (!$alreadyExists) {
                Charge::create([
                    'appartement_id' => $appt->id,
                    'titre' => "Cotisation de " . ucfirst(now()->translatedFormat('F Y')),
                    'description' => "Cotisation mensuelle de copropriété pour l'appartement n° " . $appt->numero,
                    'montant' => $appt->cotisation_mensuelle ?? 0.00,
                    'date_echeance' => now()->setDate(now()->year, now()->month, 25), // Échéance le 25 du mois en cours
                    'statut' => 'impayé',
                ]);
                $count++;
            }
        }

        $this->info("Génération terminée avec succès. {$count} charges mensuelles créées.");
    }
}
