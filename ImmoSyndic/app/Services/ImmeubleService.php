<?php

namespace App\Services;

use App\Models\Immeuble;

class ImmeubleService extends BaseService
{
    public function __construct(Immeuble $immeuble)
    {
        $this->model = $immeuble;
    }

    /**
     * Get financial and occupancy statistics for an building.
     */
    public function getImmeubleStats(int $immeubleId)
    {
        $immeuble = $this->findOrFail($immeubleId);
        
        return [
            'total_appartements' => $immeuble->appartements()->count(),
            'total_residents' => $immeuble->appartements()->whereHas('residents')->count(),
            'total_incidents_ouverts' => $immeuble->incidents()->where('statut', 'ouvert')->count(),
        ];
    }
}
