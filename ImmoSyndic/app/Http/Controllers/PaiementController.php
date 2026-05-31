<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Charge;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'charge_id' => 'required|exists:charges,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'piece_jointe' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:4096'
        ]);

        $charge = Charge::findOrFail($request->charge_id);

        $recuPath = null;
        if ($request->hasFile('piece_jointe')) {
            $file = $request->file('piece_jointe');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $recuPath = $file->storeAs('recus', $filename, 'public');
        }

        // Link the payment to the actual resident belonging to the apartment
        $residentId = $charge->appartement->residents()->first()->id ?? Auth::id();

        Paiement::create([
            'charge_id' => $charge->id,
            'user_id' => $residentId,
            'montant' => $request->montant,
            'date_paiement' => $request->date_paiement,
            'mode_paiement' => 'Espèces', // Default payment method since we removed the input field
            'statut' => 'validé', // Par défaut validé si saisi par le syndic
            'recu_path' => $recuPath,
        ]);

        // Calculate total validated payments for this charge (including this new one)
        $totalPaye = $charge->paiements()->where('statut', 'validé')->sum('montant');

        // If the total validated payments cover or exceed the charge amount, mark as payé
        if ($totalPaye >= $charge->montant) {
            $charge->update(['statut' => 'payé']);
        } else {
            // Otherwise, mark as partiel so it remains in the select list for the rest
            $charge->update(['statut' => 'partiel']);
        }

        return back()->with('success', 'Paiement ajouté avec succès.');
    }

    public function generateReceipt($id)
    {
        $paiement = Paiement::with(['charge.appartement.immeuble', 'user'])->findOrFail($id);
        
        // Vérifier que le paiement appartient à un immeuble géré par ce syndic
        if (Auth::user()->role === 'syndic' && $paiement->charge->appartement->immeuble->syndic_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('admin.syndic.receipt', compact('paiement'));
    }

    /**
     * Export payments list to Excel spreadsheet (vnd.ms-excel format).
     */
    public function exportExcel()
    {
        $user = Auth::user();
        $immeubleIds = \App\Models\Immeuble::where('syndic_id', $user->id)->pluck('id');
        
        $chargesList = \App\Models\Charge::whereHas('appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['appartement.immeuble', 'appartement.residents', 'paiements'])->latest()->get();

        $filename = "Rapport_Paiements_" . date('Y-m-d') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        return view('exports.paiements_excel', compact('chargesList'));
    }

    /**
     * Export payments list to print-ready PDF layout.
     */
    public function exportPdf()
    {
        $user = Auth::user();
        $immeubleIds = \App\Models\Immeuble::where('syndic_id', $user->id)->pluck('id');
        
        $chargesList = \App\Models\Charge::whereHas('appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['appartement.immeuble', 'appartement.residents', 'paiements'])->latest()->get();

        return view('exports.paiements_pdf', compact('chargesList'));
    }
}
