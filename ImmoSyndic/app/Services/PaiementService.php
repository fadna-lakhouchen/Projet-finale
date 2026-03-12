<?php

namespace App\Services;

use App\Models\Paiement;

class PaiementService extends BaseService
{
    public function __construct(Paiement $paiement)
    {
        $this->model = $paiement;
    }

    /**
     * Process a payment and update the associated charge status.
     */
    public function processPayment(array $data)
    {
        $paiement = $this->create($data);

        // If payment is completed, update the charge status
        if ($paiement->statut === 'complete') {
            $paiement->charge->update(['statut' => 'paye']);
        }

        return $paiement;
    }
}
