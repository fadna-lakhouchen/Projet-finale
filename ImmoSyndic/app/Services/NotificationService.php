<?php

namespace App\Services;

use App\Models\Notification;

use App\Models\Immeuble;

class NotificationService extends BaseService
{
    public function __construct(Notification $notification)
    {
        $this->model = $notification;
    }

    
    public function notifyImmeubleResidents(int $immeubleId, string $titre, string $message)
    {
        $immeuble = Immeuble::findOrFail($immeubleId);
        $notifications = [];

        foreach ($immeuble->appartements as $appartement) {
            foreach ($appartement->residents as $resident) {
                $notifications[] = $this->create([
                    'user_id' => $resident->id,
                    'titre' => $titre,
                    'message' => $message,
                    'type' => 'info',
                    'lu' => false,
                    'date_envoi' => now(),
                ]);
            }
        }

        return $notifications;
    }
}
