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

    /**
     * Validate an incident and automatically create a scheduled intervention.
     */
    public function validateAndCreateIntervention(int $incidentId, array $interventionData)
    {
        $incident = $this->findOrFail($incidentId);
        
        // Update incident status
        $incident->update(['statut' => 'valide']);

        // Create intervention
        return Intervention::create(array_merge($interventionData, [
            'incident_id' => $incident->id,
            'statut' => 'planifie',
        ]));
    }
}
