<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Immeuble;
use App\Services\DepenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DepenseController extends Controller
{
    protected $depenseService;

    public function __construct(DepenseService $depenseService)
    {
        $this->depenseService = $depenseService;
    }

    /**
     * Store an expense by Admin.
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

        $this->depenseService->storeExpense($data, $file);

        return back()->with('success', 'Dépense enregistrée avec succès.');
    }

    /**
     * Delete an expense by Admin.
     */
    public function destroyByAdmin($id)
    {
        $depense = Depense::findOrFail($id);

        if ($depense->justificatif_path && Storage::disk('public')->exists($depense->justificatif_path)) {
            Storage::disk('public')->delete($depense->justificatif_path);
        }

        $depense->delete();

        return back()->with('success', 'Dépense supprimée avec succès.');
    }

    /**
     * Store an expense by Syndic.
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

        // Verify the syndic manages this building
        $immeuble = Immeuble::where('id', $request->immeuble_id)
            ->where('syndic_id', $user->id)
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
     * Delete an expense by Syndic.
     */
    public function destroyBySyndic($id)
    {
        $user = Auth::user();

        // Find expense only if it belongs to an immeuble managed by this syndic
        $depense = Depense::where('id', $id)
            ->whereHas('immeuble', function ($q) use ($user) {
                $q->where('syndic_id', $user->id);
            })->firstOrFail();

        if ($depense->justificatif_path && Storage::disk('public')->exists($depense->justificatif_path)) {
            Storage::disk('public')->delete($depense->justificatif_path);
        }

        $depense->delete();

        return back()->with('success', 'Dépense supprimée avec succès.');
    }
}
