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
}
