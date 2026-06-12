<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Immeuble;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Contrôleur DocumentController
 * Gère le téléversement, le stockage sécurisé et la suppression des documents administratifs
 * (contrats de maintenance, règlements de copropriété, factures de prestataires, procès-verbaux d'AG)
 * dans les espaces Administrateur et Syndic.
 */
class DocumentController extends Controller
{
    // Service injecté pour la logique de gestion documentaire
    protected $documentService;

    /**
     * Constructeur
     */
    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Ajouter un document par l'Administrateur
     * - Valide les métadonnées (titre, catégorie d'in: Facture, Contrat, PV, Autre).
     * - Téléverse le fichier sur le disque de stockage public.
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

        // Délégation du téléchargement physique et de la création de la base de données au service
        $this->documentService->uploadDocument($file, $data);

        return back()->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Supprimer un document par l'Administrateur
     * - Supprime le fichier associé sur le disque de stockage.
     * - Nettoie l'enregistrement en base de données.
     */
    public function destroyByAdmin($id)
    {
        $document = Document::findOrFail($id);

        // Nettoyage physique
        if ($document->fichier_path && Storage::disk('public')->exists($document->fichier_path)) {
            Storage::disk('public')->delete($document->fichier_path);
        }

        $document->delete();

        return back()->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Ajouter un document par le Syndic
     * - Valide les données saisies.
     * - Vérifie l'habilitation du syndic sur l'immeuble.
     * - Enregistre l'acte ou la facture d'immeuble.
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

        // Sécurité : Vérifier que le syndic gère effectivement cet immeuble
        $immeuble = $user->managedImmeubles()
            ->where('immeubles.id', $request->immeuble_id)
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
     * Supprimer un document par le Syndic
     * - Vérifie que le syndic connecté gère l'immeuble lié au document.
     * - Supprime le fichier physique et l'enregistrement.
     */
    public function destroyBySyndic($id)
    {
        $user = Auth::user();

        // Récupération du document ciblé
        $document = Document::findOrFail($id);
        
        // Sécurité : Le syndic ne peut détruire que les pièces de ses immeubles sous gestion
        if (!$user->managedImmeubles()->where('immeubles.id', $document->immeuble_id)->exists()) {
            abort(403, 'Accès non autorisé');
        }

        // Nettoyage sur le disque de stockage
        if ($document->fichier_path && Storage::disk('public')->exists($document->fichier_path)) {
            Storage::disk('public')->delete($document->fichier_path);
        }

        $document->delete();

        return back()->with('success', 'Document supprimé avec succès.');
    }
}

