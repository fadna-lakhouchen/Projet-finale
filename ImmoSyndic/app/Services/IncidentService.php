<?php

namespace App\Services;

use App\Models\Incident;

use App\Models\Intervention;

class IncidentService extends BaseService
{
    public function __construct(Incident $incident)
    {
        $this->model = $incident;
    }

    
    public function validateAndCreateIntervention(int $incidentId, array $interventionData)
    {
        $incident = $this->findOrFail($incidentId);
        
        $incident->update(['statut' => 'valide']);

        return Intervention::create(array_merge($interventionData, [
            'incident_id' => $incident->id,
            'statut' => 'planifie',
        ]));
    }
}
