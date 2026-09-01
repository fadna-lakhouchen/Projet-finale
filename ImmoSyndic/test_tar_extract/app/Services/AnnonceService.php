<?php

namespace App\Services;

use App\Models\Annonce;

use App\Services\NotificationService;

class AnnonceService extends BaseService
{
    public function __construct(Annonce $annonce)
    {
        $this->model = $annonce;
    }

   
    public function publishToImmeuble(int $immeubleId, array $data)
    {
        $annonce = $this->create(array_merge($data, [
            'immeuble_id' => $immeubleId,
            'date_publication' => now(),
        ]));

    
        $notifService = app(NotificationService::class);
        $notifService->notifyImmeubleResidents($immeubleId, "Nouvelle Annonce: " . $annonce->titre, "Une nouvelle annonce a été publiée.");

        return $annonce;
    }
}
