<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Immeuble;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Store a document uploaded by Admin.
     */
    public function storeByAdmin(Request $request)
    {
        $request->validate([
            'titre'       => 'required|string|max:255',
            'categorie'   => 'required|string|in:Facture,Contrat,PV,Autre',
            'immeuble_id' => 'required|exists:immeubles,id',
            'fichier'     => 'required|file|max:20480|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx',
        ]);

        $file = $request->file('fichier');
        
        $data = [
            'titre'       => $request->titre,
            'categorie'   => $request->categorie,
            'immeuble_id' => $request->immeuble_id,
            'charge_id'   => $request->charge_id ?? null,
        ];

        $this->documentService->uploadDocument($file, $data);

        return back()->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Delete a document by Admin.
     */
    public function destroyByAdmin($id)
    {
        $document = Document::findOrFail($id);

        if ($document->fichier_path && Storage::disk('public')->exists($document->fichier_path)) {
            Storage::disk('public')->delete($document->fichier_path);
        }

        $document->delete();

        return back()->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Store a document uploaded by Syndic.
     */
    public function storeBySyndic(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'titre'       => 'required|string|max:255',
            'categorie'   => 'required|string|in:Facture,Contrat,PV,Autre',
            'immeuble_id' => 'required|exists:immeubles,id',
            'fichier'     => 'required|file|max:20480|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx',
        ]);

        // Verify the syndic manages this building
        $immeuble = Immeuble::where('id', $request->immeuble_id)
            ->where('syndic_id', $user->id)
            ->firstOrFail();

        $file = $request->file('fichier');

        $data = [
            'titre'       => $request->titre,
            'categorie'   => $request->categorie,
            'immeuble_id' => $immeuble->id,
            'charge_id'   => $request->charge_id ?? null,
        ];

        $this->documentService->uploadDocument($file, $data);

        return back()->with('success', 'Document ajouté avec succès pour l\'immeuble ' . $immeuble->nom . '.');
    }

    /**
     * Delete a document by Syndic.
     */
    public function destroyBySyndic($id)
    {
        $user = Auth::user();

        // Get document only if it belongs to an immeuble managed by this syndic
        $document = Document::where('id', $id)
            ->whereHas('immeuble', function ($q) use ($user) {
                $q->where('syndic_id', $user->id);
            })->firstOrFail();

        if ($document->fichier_path && Storage::disk('public')->exists($document->fichier_path)) {
            Storage::disk('public')->delete($document->fichier_path);
        }

        $document->delete();

        return back()->with('success', 'Document supprimé avec succès.');
    }
}
