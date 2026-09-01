<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Annonce;
use Illuminate\Http\JsonResponse;

class AnnonceController extends Controller
{
    public function index(int $userId): JsonResponse
    {
        $user = User::with('appartements.immeuble')->findOrFail($userId);
        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;

        if (!$immeuble) {
            return response()->json([
                'status' => 'success',
                'data'   => [],
                'immeuble' => null,
            ]);
        }

        $annonces = Annonce::where('immeuble_id', $immeuble->id)
            ->with('syndic')
            ->latest()
            ->get()
            ->map(function ($annonce) {
                return [
                    'id'          => $annonce->id,
                    'titre'       => $annonce->titre,
                    'contenu'     => $annonce->contenu,
                    'created_at'  => $annonce->created_at->format('Y-m-d H:i:s'),
                    'syndic_nom'  => $annonce->syndic ? $annonce->syndic->prenom . ' ' . $annonce->syndic->nom : 'Syndic',
                    'syndic_avatar' => $annonce->syndic
                        ? 'https://ui-avatars.com/api/?name=' . urlencode($annonce->syndic->prenom . ' ' . $annonce->syndic->nom) . '&background=3b66f5&color=fff&font-size=0.4'
                        : null,
                ];
            });

        return response()->json([
            'status'   => 'success',
            'data'     => $annonces,
            'immeuble' => $immeuble->nom,
        ]);
    }
}
