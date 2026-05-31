<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Services\AnnonceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    protected $annonceService;

    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'immeuble_id' => 'required|exists:immeubles,id',
        ]);

        $this->annonceService->publishToImmeuble($request->immeuble_id, [
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Annonce publiée avec succès et résidents notifiés.');
    }

    public function update(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);
        
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'immeuble_id' => 'required|exists:immeubles,id',
        ]);

        $annonce->update([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'immeuble_id' => $request->immeuble_id,
        ]);

        return back()->with('success', 'Annonce mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->delete();

        return back()->with('success', 'Annonce supprimée avec succès.');
    }
}
