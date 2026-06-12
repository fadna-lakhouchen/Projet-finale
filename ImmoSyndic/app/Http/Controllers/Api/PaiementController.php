<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PaiementController extends Controller
{
    public function index(int $userId): JsonResponse
    {
        $user = User::with(['paiements' => function ($q) {
            $q->latest('date_paiement');
        }])->findOrFail($userId);

        $paiements = $user->paiements->map(function ($paiement) {
            return [
                'id'            => $paiement->id,
                'reference'     => 'REF-' . str_pad($paiement->id, 6, '0', STR_PAD_LEFT),
                'montant'       => (float) $paiement->montant,
                'date_paiement' => $paiement->date_paiement
                    ? $paiement->date_paiement->format('Y-m-d')
                    : null,
                'statut'        => $paiement->statut,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $paiements,
        ]);
    }
}
