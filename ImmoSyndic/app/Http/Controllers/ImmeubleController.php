<?php

namespace App\Http\Controllers;

use App\Models\Immeuble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur ImmeubleController
 * Gère la création, la modification et la suppression des immeubles (copropriétés)
 * au niveau de l'espace Administrateur global, avec des redirections sécurisées pour les syndics connectés.
 */
class ImmeubleController extends Controller
{
    /**
     * Enregistrer un nouvel immeuble (Store - Administrateur)
     * - Valide les champs obligatoires (nom, adresse, ville, étages, appartements).
     * - Associe éventuellement un syndic (syndic_id) lors de la création.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'nombre_etages' => 'required|integer|min:0',
            'nombre_appartements' => 'required|integer|min:0',
            'syndic_id' => 'nullable|exists:users,id',
        ]);

        Immeuble::create($request->all());

        return back()->with('success', 'Immeuble ajouté avec succès.');
    }

    /**
     * Modifier un immeuble existant (Update - Administrateur)
     * - Valide les nouvelles informations.
     * - Met à jour l'immeuble ciblé.
     */
    public function update(Request $request, $id)
    {
        $immeuble = Immeuble::findOrFail($id);
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'nombre_etages' => 'required|integer|min:0',
            'nombre_appartements' => 'required|integer|min:0',
            'syndic_id' => 'nullable|exists:users,id',
        ]);

        $immeuble->update($request->all());

        return back()->with('success', 'Immeuble mis à jour avec succès.');
    }

    /**
     * Supprimer définitivement un immeuble de la base (Destroy - Administrateur)
     */
    public function destroy($id)
    {
        $immeuble = Immeuble::findOrFail($id);
        $immeuble->delete();
        return back()->with('success', 'Immeuble supprimé avec succès.');
    }

    /**
     * Créer un immeuble par un Syndic connecté
     * - Force l'ID du syndic à celui du syndic connecté par fusion de requête.
     * - Redirige la logique sur la méthode store générale.
     */
    public function storeBySyndic(Request $request)
    {
        $request->merge(['syndic_id' => Auth::id()]);
        return $this->store($request);
    }

    /**
     * Modifier un immeuble par le Syndic connecté
     * - Sécurité : Récupère l'immeuble uniquement s'il est affecté au syndic connecté.
     * - Applique les modifications d'immeuble.
     */
    public function updateBySyndic(Request $request, $id)
    {
        $immeuble = Immeuble::where('id', $id)->where('syndic_id', Auth::id())->firstOrFail();
        return $this->update($request, $id);
    }

    /**
     * Supprimer un immeuble par le Syndic connecté
     * - Sécurité : L'immeuble doit appartenir au périmètre de gestion du syndic.
     * - Applique la suppression.
     */
    public function destroyBySyndic($id)
    {
        $immeuble = Immeuble::where('id', $id)->where('syndic_id', Auth::id())->firstOrFail();
        return $this->destroy($id);
    }
}

