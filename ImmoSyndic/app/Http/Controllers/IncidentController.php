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
        ]);

        Incident::create([
            'titre' => $request->titre,
            'immeuble_id' => $request->immeuble_id,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'statut' => 'Ouvert',
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
}
