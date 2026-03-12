<?php

namespace App\Services;

use App\Models\Intervention;

class InterventionService extends BaseService
{
    public function __construct(Intervention $intervention)
    {
        $this->model = $intervention;
    }

    /**
     * Mark intervention as completed and update the related incident.
     */
    public function finalizeIntervention(int $interventionId, array $data)
    {
        $intervention = $this->findOrFail($interventionId);
        $intervention->update(array_merge($data, [
            'statut' => 'complete',
            'date_realisation' => now(),
        ]));

        // Update incident status to resolved
        if ($intervention->incident) {
            $intervention->incident->update(['statut' => 'resolu']);
        }

        return $intervention;
    }
}
