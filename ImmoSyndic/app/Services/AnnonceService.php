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

    /**
     * Publish an announcement and notify all building residents.
     */
    public function publishToImmeuble(int $immeubleId, array $data)
    {
        $annonce = $this->create(array_merge($data, [
            'immeuble_id' => $immeubleId,
            'date_publication' => now(),
        ]));

        // Auto-notify residents using NotificationService (simulated)
        // In a real app, you would use Dependency Injection or a Facade
        $notifService = app(NotificationService::class);
        $notifService->notifyImmeubleResidents($immeubleId, "Nouvelle Annonce: " . $annonce->titre, "Une nouvelle annonce a été publiée.");

        return $annonce;
    }
}
