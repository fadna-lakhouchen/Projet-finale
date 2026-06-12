<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function show(int $userId): JsonResponse
    {
        $user = User::with('appartements.immeuble')->findOrFail($userId);
        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id'         => $user->id,
                'prenom'     => $user->prenom,
                'nom'        => $user->nom,
                'email'      => $user->email,
                'telephone'  => $user->telephone,
                'cin'        => $user->cin,
                'role'       => $user->role,
                'appartement' => $appartement ? [
                    'numero'   => $appartement->numero,
                    'immeuble' => $immeuble ? $immeuble->nom : null,
                    'ville'    => $immeuble ? $immeuble->ville : null,
                ] : null,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->prenom . ' ' . $user->nom) . '&background=3b66f5&color=fff&size=128',
            ],
        ]);
    }
}
