<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Incident;
use App\Models\Paiement;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function resident(int $userId): JsonResponse
    {
        $user = User::with('appartements.immeuble')->findOrFail($userId);
        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;

        // Charges du mois courant (reste à payer)
        $chargesMois = 0;
        if ($appartement) {
            $chargesMois = $appartement->charges()
                ->whereMonth('date_echeance', now()->month)
                ->whereYear('date_echeance', now()->year)
                ->whereIn('statut', ['impayé', 'partiel'])
                ->sum('montant');
        }

        // Total payé cette année
        $totalPayeAnnee = Paiement::where('user_id', $userId)
            ->whereYear('date_paiement', now()->year)
            ->whereIn('statut', ['validé', 'payé'])
            ->sum('montant');

        // Incidents ouverts
        $incidentsOuverts = Incident::where('user_id', $userId)
            ->whereNotIn('statut', ['résolu', 'Résolu', 'terminé', 'Terminé'])
            ->count();

        // Activité récente (5 dernières entrées)
        $activites = collect();

        $mesPaiements = Paiement::where('user_id', $userId)
            ->latest('date_paiement')->take(3)->get();
        foreach ($mesPaiements as $p) {
            $activites->push([
                'date'        => $p->date_paiement ? $p->date_paiement->format('Y-m-d') : null,
                'type'        => 'Paiement',
                'description' => 'Règlement REF-' . str_pad($p->id, 6, '0', STR_PAD_LEFT),
                'statut'      => $p->statut,
                'color'       => in_array(strtolower($p->statut), ['payé', 'validé']) ? 'green' : 'orange',
            ]);
        }

        $mesIncidents = Incident::where('user_id', $userId)->latest()->take(3)->get();
        foreach ($mesIncidents as $i) {
            $s = strtolower($i->statut);
            $activites->push([
                'date'        => $i->created_at->format('Y-m-d'),
                'type'        => 'Signalement',
                'description' => $i->titre,
                'statut'      => $i->statut,
                'color'       => in_array($s, ['nouveau', 'ouvert', 'à traiter']) ? 'blue'
                               : (in_array($s, ['en cours']) ? 'orange' : 'green'),
            ]);
        }

        $activites = $activites->sortByDesc('date')->take(5)->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'prenom'    => $user->prenom,
                    'nom'       => $user->nom,
                    'immeuble'  => $immeuble ? $immeuble->nom : null,
                    'appartement' => $appartement ? $appartement->numero : null,
                ],
                'stats' => [
                    'charges_mois'      => (float) $chargesMois,
                    'total_paye_annee'  => (float) $totalPayeAnnee,
                    'incidents_ouverts' => $incidentsOuverts,
                ],
                'activites' => $activites,
            ],
        ]);
    }
}
