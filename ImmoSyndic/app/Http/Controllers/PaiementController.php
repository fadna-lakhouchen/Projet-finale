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

    /**
     * Mettre à jour un paiement existant (gestion des erreurs du syndic).
     * Permet de modifier le montant, la date, le statut ou réassigner le versement à une autre cotisation.
     */
    public function update(Request $request, $id)
    {
        // 1. Récupérer le paiement avec sa cotisation associée
        $paiement = Paiement::with('charge.appartement.immeuble')->findOrFail($id);

        // 2. Vérifier que ce paiement appartient à un immeuble géré par ce syndic
        $user = Auth::user();
        if ($paiement->charge->appartement->immeuble->syndic_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        // 3. Valider les données reçues du formulaire de modification
        $request->validate([
            'charge_id' => 'required|exists:charges,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'statut' => 'required|in:validé,en attente',
            'piece_jointe' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:4096'
        ]);

        $oldCharge = $paiement->charge;
        $newCharge = Charge::findOrFail($request->charge_id);

        // 4. Vérifier que la nouvelle cotisation sélectionnée appartient bien à un immeuble de ce syndic
        if ($newCharge->appartement->immeuble->syndic_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        // 5. Gérer le fichier de pièce jointe (justificatif de paiement)
        $recuPath = $paiement->recu_path;
        if ($request->hasFile('piece_jointe')) {
            // Supprimer l'ancien justificatif s'il existe
            if ($paiement->recu_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($paiement->recu_path);
            }
            // Enregistrer le nouveau justificatif
            $file = $request->file('piece_jointe');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $recuPath = $file->storeAs('recus', $filename, 'public');
        }

        // 6. Récupérer l'ID du résident assigné à l'appartement concerné
        $residentId = $newCharge->appartement->residents()->first()->id ?? Auth::id();

        // 7. Enregistrer les modifications du paiement
        $paiement->update([
            'charge_id' => $newCharge->id,
            'user_id' => $residentId,
            'montant' => $request->montant,
            'date_paiement' => $request->date_paiement,
            'statut' => $request->statut,
            'recu_path' => $recuPath,
        ]);

        // 8. Recalculer le statut de la nouvelle cotisation
        $totalPayeNew = $newCharge->paiements()->where('statut', 'validé')->sum('montant');
        if ($totalPayeNew >= $newCharge->montant) {
            $newCharge->update(['statut' => 'payé']);
        } elseif ($totalPayeNew > 0) {
            $newCharge->update(['statut' => 'partiel']);
        } else {
            $newCharge->update(['statut' => 'impayé']);
        }

        // 9. Si la cotisation a changé, recalculer également le statut de l'ancienne cotisation
        if ($oldCharge->id !== $newCharge->id) {
            $totalPayeOld = $oldCharge->paiements()->where('statut', 'validé')->sum('montant');
            if ($totalPayeOld >= $oldCharge->montant) {
                $oldCharge->update(['statut' => 'payé']);
            } elseif ($totalPayeOld > 0) {
                $oldCharge->update(['statut' => 'partiel']);
            } else {
                $oldCharge->update(['statut' => 'impayé']);
            }
        }

        return back()->with('success', 'Paiement modifié avec succès.');
    }

    /**
     * Supprimer un paiement existant (annulation de transaction).
     * Remet à jour le statut de la cotisation correspondante en conséquence.
     */
    public function destroy($id)
    {
        // 1. Récupérer le paiement avec sa cotisation associée
        $paiement = Paiement::with('charge.appartement.immeuble')->findOrFail($id);

        // 2. Vérifier la sécurité (le syndic connecté doit gérer cet immeuble)
        $user = Auth::user();
        if ($paiement->charge->appartement->immeuble->syndic_id !== $user->id) {
            abort(403, 'Accès non autorisé');
        }

        // 3. Supprimer le fichier physique du justificatif s'il existe
        if ($paiement->recu_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($paiement->recu_path);
        }

        // 4. Supprimer le paiement en base de données
        $charge = $paiement->charge;
        $paiement->delete();

        // 5. Recalculer et mettre à jour le statut de la cotisation correspondante
        $totalPaye = $charge->paiements()->where('statut', 'validé')->sum('montant');
        if ($totalPaye >= $charge->montant) {
            $charge->update(['statut' => 'payé']);
        } elseif ($totalPaye > 0) {
            $charge->update(['statut' => 'partiel']);
        } else {
            $charge->update(['statut' => 'impayé']);
        }

        return back()->with('success', 'Paiement supprimé avec succès.');
    }
}
