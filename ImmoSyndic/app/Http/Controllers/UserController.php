<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // --- ADMIN ACTIONS ---

    public function storeResident(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'type_resident' => 'required|string|in:locataire,propriétaire',
            'appartement_id' => 'required|exists:appartements,id',
            'date_entree' => 'required|date',
        ]);

        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'cin' => $request->cin,
            'notes' => $request->notes,
            'password' => Hash::make('password'), // Mot de passe par défaut
            'role' => 'resident',
            'is_active' => true,
        ]);

        $user->appartements()->attach($request->appartement_id, [
            'type_resident' => ucfirst($request->type_resident),
            'date_entree' => $request->date_entree,
        ]);

        return back()->with('success', 'Résident ajouté avec succès.');
    }

    public function updateResident(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'type_resident' => 'required|string|in:locataire,propriétaire',
            'appartement_id' => 'required|exists:appartements,id',
            'date_entree' => 'required|date',
        ]);

        $user->update($request->only(['prenom', 'nom', 'email', 'telephone', 'cin', 'notes']));

        $user->appartements()->sync([
            $request->appartement_id => [
                'type_resident' => ucfirst($request->type_resident),
                'date_entree' => $request->date_entree,
            ]
        ]);

        return back()->with('success', 'Résident mis à jour avec succès.');
    }

    public function storeSyndic(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:255',
            'date_entree' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $syndic = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'cin' => $request->cin,
            'ville' => $request->ville,
            'date_entree' => $request->date_entree,
            'notes' => $request->notes,
            'password' => Hash::make('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);

        if ($request->has('immeubles') && is_array($request->immeubles)) {
            \App\Models\Immeuble::whereIn('id', $request->immeubles)->update(['syndic_id' => $syndic->id]);
        }

        return back()->with('success', 'Syndic ajouté avec succès.');
    }

    public function updateSyndic(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:255',
            'date_entree' => 'nullable|date',
            'date_sortie' => 'nullable|date|after_or_equal:date_entree',
            'notes' => 'nullable|string',
        ]);

        $user->update($request->only(['prenom', 'nom', 'email', 'telephone', 'cin', 'ville', 'date_entree', 'date_sortie', 'notes']));

        // Réinitialiser les immeubles assignés à ce syndic
        \App\Models\Immeuble::where('syndic_id', $user->id)->update(['syndic_id' => null]);
        
        // Assigner les nouveaux immeubles sélectionnés
        if ($request->has('immeubles') && is_array($request->immeubles)) {
            \App\Models\Immeuble::whereIn('id', $request->immeubles)->update(['syndic_id' => $user->id]);
        }

        return back()->with('success', 'Syndic mis à jour avec succès.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    // --- SYNDIC ACTIONS ---

    public function storeResidentBySyndic(Request $request)
    {
        // Logique similaire mais peut être limitée aux immeubles du syndic
        return $this->storeResident($request);
    }

    public function updateResidentBySyndic(Request $request, $id)
    {
        return $this->updateResident($request, $id);
    }

    public function destroyUserBySyndic($id)
    {
        return $this->destroyUser($id);
    }
}
