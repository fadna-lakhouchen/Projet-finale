<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Immeuble;
use App\Services\DepenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Contrôleur DepenseController
 * Gère l'enregistrement, la validation et la suppression des dépenses de fonctionnement (charges communes, travaux, entretien)
 * pour les espaces d'administration (Administrateur global et Syndic principal).
 */
class DepenseController extends Controller
{
    // Service injecté encapsulant la logique métier liée aux dépenses
    protected $depenseService;

    /**
     * Constructeur
     */
    public function __construct(DepenseService $depenseService)
    {
        $this->depenseService = $depenseService;
    }

    /**
     * Enregistrer une dépense par l'Administrateur
     * - Valide les données saisies ainsi que le fichier de justificatif (reçu/facture).
     * - Utilise le DepenseService pour stocker l'enregistrement physique et en base de données.
     */
    public function storeByAdmin(Request $request)
    {
        $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'montant'      => 'required|numeric|min:0.01',
            'date_depense' => 'required|date',
            'immeuble_id'  => 'required|exists:immeubles,id',
            'justificatif' => 'nullable|file|max:20480|mimes:pdf,png,jpg,jpeg,doc,docx',
        ]);

        $file = $request->file('justificatif');

        $data = [
            'titre'        => $request->titre,
            'description'  => $request->description,
            'montant'      => $request->montant,
            'date_depense' => $request->date_depense,
            'immeuble_id'  => $request->immeuble_id,
        ];

        // Délégation de la création physique et de la base de données au service
        $this->depenseService->storeExpense($data, $file);

        return back()->with('success', 'Dépense enregistrée avec succès.');
    }

    /**
     * Supprimer une dépense par l'Administrateur
     * - Supprime le fichier justificatif du disque de stockage public s'il existe.
     * - Retire définitivement l'enregistrement de la dépense.
     */
    public function destroyByAdmin($id)
    {
        $depense = Depense::findOrFail($id);

        // Nettoyage du fichier justificatif associé sur le disque de stockage
        if ($depense->justificatif_path && Storage::disk('public')->exists($depense->justificatif_path)) {
            Storage::disk('public')->delete($depense->justificatif_path);
        }

        $depense->delete();

        return back()->with('success', 'Dépense supprimée avec succès.');
    }

    /**
     * Enregistrer une dépense par le Syndic connecté
     * - Valide les données fournies et vérifie que l'immeuble ciblé est sous la gestion du syndic.
     * - Enregistre la dépense via le DepenseService.
     */
    public function storeBySyndic(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'montant'      => 'required|numeric|min:0.01',
            'date_depense' => 'required|date',
            'immeuble_id'  => 'required|exists:immeubles,id',
            'justificatif' => 'nullable|file|max:20480|mimes:pdf,png,jpg,jpeg,doc,docx',
        ]);

        // Sécurité : Vérifier que l'immeuble est géré par le syndic connecté
        $immeuble = $user->managedImmeubles()
            ->where('immeubles.id', $request->immeuble_id)
            ->firstOrFail();

        $file = $request->file('justificatif');

        $data = [
            'titre'        => $request->titre,
            'description'  => $request->description,
            'montant'      => $request->montant,
            'date_depense' => $request->date_depense,
            'immeuble_id'  => $immeuble->id,
        ];

        $this->depenseService->storeExpense($data, $file);

        return back()->with('success', 'Dépense enregistrée avec succès pour l\'immeuble ' . $immeuble->nom . '.');
    }

    /**
     * Supprimer une dépense par le Syndic connecté
     * - Vérifie l'habilitation du syndic sur l'immeuble lié à cette dépense.
     * - Supprime le fichier physique ainsi que l'enregistrement.
     */
    public function destroyBySyndic($id)
    {
        $user = Auth::user();

        // Récupération de la dépense concernée
        $depense = Depense::findOrFail($id);
        
        // Sécurité : Vérifier que le syndic gère effectivement l'immeuble de cette dépense
        if (!$user->managedImmeubles()->where('immeubles.id', $depense->immeuble_id)->exists()) {
            abort(403, 'Accès non autorisé');
        }

        // Suppression du fichier du disque de stockage public
        if ($depense->justificatif_path && Storage::disk('public')->exists($depense->justificatif_path)) {
            Storage::disk('public')->delete($depense->justificatif_path);
        }

        $depense->delete();

        return back()->with('success', 'Dépense supprimée avec succès.');
    }
}

