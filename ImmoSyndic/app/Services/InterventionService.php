<?php

namespace App\Services;

use App\Models\Intervention;

class InterventionService extends BaseService
{
    public function __construct(Intervention $intervention)
    {
        $this->model = $intervention;
    }

    
    public function finalizeIntervention(int $interventionId, array $data)
    {
        $intervention = $this->findOrFail($interventionId);
        $intervention->update(array_merge($data, [
            'statut' => 'complete',
            'date_realisation' => now(),
        ]));

        
        if ($intervention->incident) {
            $intervention->incident->update(['statut' => 'resolu']);
        }

        return $intervention;
    }
}
