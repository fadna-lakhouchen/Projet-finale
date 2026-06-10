<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Services\AnnonceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur AnnonceController
 * Gère la publication, la modification et la suppression des annonces diffusées par les syndics pour leurs immeubles.
 */
class AnnonceController extends Controller
{
    protected $annonceService;

    // Injection de dépendance du service AnnonceService pour encapsuler la logique métier
    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    /**
     * Enregistrer une nouvelle annonce (Store)
     * - Valide les données saisies par le syndic.
     * - Utilise AnnonceService pour l'enregistrement et l'envoi automatique de notifications de masse aux résidents.
     */
    public function store(Request $request)
    {
        // Validation des entrées obligatoires
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'immeuble_id' => 'required|exists:immeubles,id',
        ]);

        // Appel du service pour publier l'annonce dans l'immeuble ciblé
        $this->annonceService->publishToImmeuble($request->immeuble_id, [
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'user_id' => Auth::id(), // ID du syndic connecté
        ]);

        return back()->with('success', 'Annonce publiée avec succès et résidents notifiés.');
    }

    /**
     * Modifier une annonce existante (Update)
     * - Valide les modifications.
     * - Met à jour l'annonce dans la base de données.
     */
    public function update(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);
        
        // Validation des modifications
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'immeuble_id' => 'required|exists:immeubles,id',
        ]);

        // Mise à jour de l'enregistrement
        $annonce->update([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'immeuble_id' => $request->immeuble_id,
        ]);

        return back()->with('success', 'Annonce mise à jour avec succès.');
    }

    /**
     * Supprimer une annonce (Destroy)
     * - Retire définitivement l'annonce de la base de données.
     */
    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->delete();

        return back()->with('success', 'Annonce supprimée avec succès.');
    }
}

