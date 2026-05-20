<?php

namespace App\Services;

use App\Models\Paiement;

class PaiementService extends BaseService
{
    public function __construct(Paiement $paiement)
    {
        $this->model = $paiement;
    }

    
    public function processPayment(array $data)
    {
        $paiement = $this->create($data);

        
        if ($paiement->statut === 'complete') {
            $paiement->charge->update(['statut' => 'paye']);
        }

        return $paiement;
    }

    public function getResidentStats($user)
    {
        $appartementIds = $user->appartements()->pluck('appartements.id');
        
        $a_payer_mois = \App\Models\Charge::whereIn('appartement_id', $appartementIds)
            ->whereIn('statut', ['non payé', 'en retard'])
            ->sum('montant');
            
        $total_paye_annee = $this->model->where('user_id', $user->id)
            ->where('statut', 'validé')
            ->whereYear('date_paiement', now()->year)
            ->sum('montant');

        return [
            'a_payer_mois' => $a_payer_mois,
            'total_paye_annee' => $total_paye_annee,
        ];
    }

    public function getUserPaiements($user)
    {
        return $this->model->where('user_id', $user->id)
            ->with(['charge.appartement.immeuble'])
            ->latest()
            ->get();
    }
}
