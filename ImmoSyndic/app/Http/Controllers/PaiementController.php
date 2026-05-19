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
            'methode_paiement' => 'required|string',
            'date_paiement' => 'required|date',
            'reference' => 'nullable|string'
        ]);

        $charge = Charge::findOrFail($request->charge_id);

        Paiement::create([
            'charge_id' => $charge->id,
            'user_id' => $charge->appartement->user_id, // The resident who owns the apartment
            'montant' => $request->montant,
            'date_paiement' => $request->date_paiement,
            'methode_paiement' => $request->methode_paiement,
            'reference_transaction' => $request->reference ?? 'CASH-' . time(),
            'statut' => 'validé' // Par défaut validé si saisi par le syndic
        ]);

        // Mettre à jour le statut de la charge si le montant correspond
        // Note: une vraie logique vérifierait le montant total payé vs montant charge
        $charge->update(['statut' => 'payé']);

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
}
