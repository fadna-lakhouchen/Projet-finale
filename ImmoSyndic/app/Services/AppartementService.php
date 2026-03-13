<?php

namespace App\Services;

use App\Models\Appartement;

class AppartementService extends BaseService
{
    public function __construct(Appartement $appartement)
    {
        $this->model = $appartement;
    }

    
    public function assignResident(int $appartementId, int $userId, array $pivotData)
    {
        $appartement = $this->findOrFail($appartementId);
        $appartement->residents()->attach($userId, $pivotData);
        
        $appartement->update(['statut' => 'occupe']);
        return $appartement;
    }

    
    public function removeResident(int $appartementId, int $userId)
    {
        $appartement = $this->findOrFail($appartementId);
        $appartement->residents()->detach($userId);

        if ($appartement->residents()->count() === 0) {
            $appartement->update(['statut' => 'disponible']);
        }

        return $appartement;
    }
}
