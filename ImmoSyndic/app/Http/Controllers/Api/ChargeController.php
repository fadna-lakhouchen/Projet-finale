<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ChargeController extends Controller
{
    public function index(int $userId): JsonResponse
    {
        $user = User::with('appartements.charges.documents')->findOrFail($userId);
        
        $charges = $user->appartements->flatMap(function ($appartement) {
            return $appartement->charges;
        });

        return response()->json([
            'status' => 'success',
            'data' => $charges
        ]);
    }
}
