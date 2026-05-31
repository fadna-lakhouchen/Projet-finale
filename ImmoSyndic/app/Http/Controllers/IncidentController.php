<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'immeuble_id' => 'required|exists:immeubles,id',
            'description' => 'required|string',
            'statut' => 'nullable|string|in:Ouvert,En cours,Résolu',
        ]);

        Incident::create([
            'titre' => $request->titre,
            'immeuble_id' => $request->immeuble_id,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'statut' => $request->statut ?? 'Ouvert',
        ]);

        return back()->with('success', 'Incident signalé avec succès.');
    }

    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $request->validate([
            'titre' => 'required|string|max:255',
            'statut' => 'required|in:Ouvert,En cours,Résolu',
        ]);

        $incident->update($request->all());

        return back()->with('success', 'Incident mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);
        $incident->delete();
        return back()->with('success', 'Incident supprimé avec succès.');
    }

    public function storeResidentIncident(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'priorite' => 'required|string|in:basse,moyenne,haute,urgente',
            'description' => 'required|string',
        ]);

        $user = Auth::user();
        $appartement = $user->appartements()->first();
        $immeubleId = $appartement ? $appartement->immeuble_id : null;

        if (!$immeubleId) {
            return back()->with('error', 'Vous devez être assigné à un appartement pour signaler un problème.');
        }

        Incident::create([
            'titre' => $request->titre,
            'immeuble_id' => $immeubleId,
            'description' => $request->description,
            'user_id' => $user->id,
            'statut' => 'Ouvert',
            'priorite' => $request->priorite,
        ]);

        return back()->with('success', 'Incident signalé avec succès.');
    }
}
