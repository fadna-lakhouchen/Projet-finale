<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IncidentService;
use Illuminate\Http\JsonResponse;

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
}
