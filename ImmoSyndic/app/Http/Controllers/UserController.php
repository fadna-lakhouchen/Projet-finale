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
        ]);

        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make('password'), // Mot de passe par défaut
            'role' => 'resident',
            'is_active' => true,
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
        ]);

        $user->update($request->only(['prenom', 'nom', 'email']));

        return back()->with('success', 'Résident mis à jour avec succès.');
    }

    public function storeSyndic(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);

        return back()->with('success', 'Syndic ajouté avec succès.');
    }

    public function updateSyndic(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $user->update($request->only(['prenom', 'nom', 'email']));

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
