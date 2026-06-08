<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IncidentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    protected $incidentService;

    public function __construct(IncidentService $incidentService)
    {
        $this->incidentService = $incidentService;
    }

    public function index(): JsonResponse
    {
        // On récupère les données via le service existant
        $incidents = $this->incidentService->all();

        // On renvoie une réponse JSON propre
        return response()->json([
            'status' => 'success',
            'data' => $incidents
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $incident = $this->incidentService->find($id);

        if (!$incident) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incident introuvable'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $incident
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'immeuble_id' => 'required|exists:immeubles,id',
            'user_id' => 'required|exists:users,id',
            'priorite' => 'required|string|in:basse,moyenne,haute,urgente',
        ]);

        $incident = $this->incidentService->create(array_merge($validatedData, [
            'statut' => 'Ouvert',
        ]));

        return response()->json([
            'status' => 'success',
            'data' => $incident
        ], 201);
    }
}
