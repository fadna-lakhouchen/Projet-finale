<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    public function index(int $userId): JsonResponse
    {
        $user = User::with('appartements.immeuble')->findOrFail($userId);
        
        $immeubleIds = $user->appartements->pluck('immeuble_id')->unique();
        
        $documents = Document::whereIn('immeuble_id', $immeubleIds)->get();

        return response()->json([
            'status' => 'success',
            'data' => $documents
        ]);
    }
}
