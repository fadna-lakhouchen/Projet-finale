<?php

namespace App\Services;

use App\Models\Charge;

use App\Models\Immeuble;

class ChargeService extends BaseService
{
    public function __construct(Charge $charge)
    {
        $this->model = $charge;
    }

    /**
     * Generate monthly charges for all apartments in an building.
     */
    public function generateMonthlyChargesForImmeuble(int $immeubleId, array $chargeData)
    {
        $immeuble = Immeuble::findOrFail($immeubleId);
        $charges = [];

        foreach ($immeuble->appartements as $appartement) {
            $charges[] = $this->create(array_merge($chargeData, [
                'appartement_id' => $appartement->id,
                'statut' => 'en_attente',
            ]));
        }

        return $charges;
    }
}
