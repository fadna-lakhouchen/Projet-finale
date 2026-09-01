<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur IncidentController
 * Gère le signalement, la modification du statut de traitement (Ouvert, En cours, Résolu)
 * et la suppression des incidents de copropriété (ascenseur en panne, fuite d'eau, etc.)
 * déclarés par les syndics, l'administration ou directement par les résidents.
 */
class IncidentController extends Controller
{
    /**
     * Signaler un nouvel incident (Store - Admin/Syndic)
     * - Valide les données (titre, immeuble ciblé, description, statut optionnel).
     * - Associe l'incident à l'utilisateur connecté (créateur).
     */
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

    /**
     * Modifier un incident (Update - Admin/Syndic)
     * - Valide les modifications du titre et du statut de traitement (in: Ouvert, En cours, Résolu).
     * - Met à jour l'enregistrement.
     */
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

    /**
     * Supprimer un incident de la base de données (Destroy - Admin/Syndic)
     */
    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);
        $incident->delete();
        return back()->with('success', 'Incident supprimé avec succès.');
    }

    /**
     * Déclarer un incident par un Résident connecté
     * - Valide le niveau de priorité (basse, moyenne, haute, urgente).
     * - Récupère l'immeuble du résident connecté via son appartement.
     * - Bloque l'action si le résident n'est rattaché à aucun appartement.
     */
    public function storeResidentIncident(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'priorite' => 'required|string|in:basse,moyenne,haute,urgente',
            'description' => 'required|string',
        ]);

        $user = Auth::user();
        // Récupération automatique de l'appartement du résident connecté
        $appartement = $user->appartements()->first();
        $immeubleId = $appartement ? $appartement->immeuble_id : null;

        // Validation de l'affectation à une copropriété
        if (!$immeubleId) {
            return back()->with('error', 'Vous devez être assigné à un appartement pour signaler un problème.');
        }

        // Création de l'incident avec les attributs correspondants
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

