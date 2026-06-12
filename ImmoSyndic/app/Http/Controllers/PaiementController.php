<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Charge;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur PaiementController
 * Gère l'encaissement des cotisations de copropriété, la génération de reçus de paiement en PDF,
 * l'exportation des rapports financiers (Excel et PDF) et le recalcul automatique des statuts de cotisations
 * (impayé, partiel, payé) lors des ajouts, modifications ou annulations de paiements.
 */
class PaiementController extends Controller
{
    /**
     * Enregistrer un nouveau paiement (Store - Saisi par le syndic)
     * - Valide les données (cotisation associée, montant, date, justificatif de paiement).
     * - Associe le paiement au premier résident de l'appartement concerné.
     * - Enregistre la transaction et génère un log d'audit de sécurité.
     * - Recalcule dynamiquement le statut de la cotisation (payé, partiel).
     */
    public function store(Request $request)
    {
        $request->validate([
            'charge_id' => 'required|exists:charges,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'piece_jointe' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:4096'
        ]);

        $charge = Charge::findOrFail($request->charge_id);

        // Enregistrement physique de la pièce jointe (reçu bancaire, chèque) s'il y en a une
        $recuPath = null;
        if ($request->hasFile('piece_jointe')) {
            $file = $request->file('piece_jointe');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $recuPath = $file->storeAs('recus', $filename, 'public');
        }

        // Associe le paiement au résident officiel rattaché à l'appartement
        $residentId = $charge->appartement->residents()->first()->id ?? Auth::id();

        $paiement = Paiement::create([
            'charge_id' => $charge->id,
            'user_id' => $residentId,
            'montant' => $request->montant,
            'date_paiement' => $request->date_paiement,
            'mode_paiement' => 'Espèces', // Mode par défaut (Espèces) configuré pour la gestion locale
            'statut' => 'validé', // Par défaut validé si saisi par le syndic
            'recu_path' => $recuPath,
        ]);

        // Journalisation de l'action dans le système de logs d'audit
        if (class_exists(\App\Services\AuditLogService::class)) {
            try {
                app(\App\Services\AuditLogService::class)->logAction(
                    Auth::id(),
                    'created',
                    Paiement::class,
                    $paiement->id,
                    ['after' => ['montant' => $request->montant, 'charge_id' => $charge->id]]
                );
            } catch (\Exception $e) {}
        }

        // Calcul des paiements validés pour cette cotisation
        $totalPaye = $charge->paiements()->where('statut', 'validé')->sum('montant');

        // Si le total versé atteint ou dépasse le montant initial de la charge, elle passe en statut "payé"
        if ($totalPaye >= $charge->montant) {
            $charge->update(['statut' => 'payé']);
        } else {
            // Sinon, elle est marquée comme "partielle" pour rester dans les sélecteurs de reste à payer
            $charge->update(['statut' => 'partiel']);
        }

        return back()->with('success', 'Paiement ajouté avec succès.');
    }

    /**
     * Générer un reçu de paiement
     * - Affiche la vue imprimable d'un reçu avec code-barres, tampons et détails de la transaction.
     * - Sécurité : Le syndic ne peut l'imprimer que s'il gère l'immeuble correspondant.
     */
    public function generateReceipt($id)
    {
        $paiement = Paiement::with(['charge.appartement.immeuble', 'user'])->findOrFail($id);
        
        // Sécurité syndic
        if (Auth::user()->role === 'syndic' && !Auth::user()->managedImmeubles()->where('immeubles.id', $paiement->charge->appartement->immeuble_id)->exists()) {
            abort(403, 'Accès non autorisé');
        }

        return view('admin.syndic.receipt', compact('paiement'));
    }

    /**
     * Exporter le rapport des paiements au format Excel (vnd.ms-excel)
     */
    public function exportExcel()
    {
        $user = Auth::user();
        $immeubleIds = $user->managedImmeubles()->pluck('id');
        
        // Récupération de l'ensemble des cotisations de ses immeubles
        $chargesList = \App\Models\Charge::whereHas('appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['appartement.immeuble', 'appartement.residents', 'paiements'])->latest()->get();

        $filename = "Rapport_Paiements_" . date('Y-m-d') . ".xls";
        
        // Déclaration des headers HTTP pour forcer le téléchargement du fichier Excel
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        return view('exports.paiements_excel', compact('chargesList'));
    }

    /**
     * Exporter le rapport des paiements au format PDF imprimable
     */
    public function exportPdf()
    {
        $user = Auth::user();
        $immeubleIds = $user->managedImmeubles()->pluck('id');
        
        $chargesList = \App\Models\Charge::whereHas('appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['appartement.immeuble', 'appartement.residents', 'paiements'])->latest()->get();

        return view('exports.paiements_pdf', compact('chargesList'));
    }

    /**
     * Mettre à jour un paiement existant (gestion des corrections du syndic)
     * - Permet de corriger le montant, la date, le statut ou de réaffecter la transaction à une autre cotisation.
     * - Sécurité : Le syndic connecté doit gérer les immeubles d'origine et de destination.
     * - Recalcule les statuts de paiement des deux charges impliquées (l'ancienne et la nouvelle).
     */
    public function update(Request $request, $id)
    {
        // 1. Récupérer le paiement avec sa cotisation associée
        $paiement = Paiement::with('charge.appartement.immeuble')->findOrFail($id);

        // 2. Vérifier que ce paiement appartient à un immeuble géré par ce syndic
        $user = Auth::user();
        if (!$user->managedImmeubles()->where('immeubles.id', $paiement->charge->appartement->immeuble_id)->exists()) {
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
        if (!$user->managedImmeubles()->where('immeubles.id', $newCharge->appartement->immeuble_id)->exists()) {
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

        if (class_exists(\App\Services\AuditLogService::class)) {
            try {
                app(\App\Services\AuditLogService::class)->logAction(
                    Auth::id(),
                    'updated',
                    Paiement::class,
                    $paiement->id,
                    ['after' => ['montant' => $request->montant, 'charge_id' => $newCharge->id]]
                );
            } catch (\Exception $e) {}
        }

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
     * Supprimer un paiement existant (annulation de transaction)
     * - Supprime la transaction de la base et son justificatif sur le disque.
     * - Remet à jour et recalcule le statut de la cotisation correspondante en conséquence (payé, partiel, impayé).
     */
    public function destroy($id)
    {
        // 1. Récupérer le paiement avec sa cotisation associée
        $paiement = Paiement::with('charge.appartement.immeuble')->findOrFail($id);

        // 2. Vérifier la sécurité (le syndic connecté doit gérer cet immeuble)
        $user = Auth::user();
        if (!$user->managedImmeubles()->where('immeubles.id', $paiement->charge->appartement->immeuble_id)->exists()) {
            abort(403, 'Accès non autorisé');
        }

        // 3. Supprimer le fichier physique du justificatif s'il existe
        if ($paiement->recu_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($paiement->recu_path);
        }

        // 4. Supprimer le paiement en base de données
        $charge = $paiement->charge;
        if (class_exists(\App\Services\AuditLogService::class)) {
            try {
                app(\App\Services\AuditLogService::class)->logAction(
                    Auth::id(),
                    'deleted',
                    Paiement::class,
                    $paiement->id,
                    ['before' => ['montant' => $paiement->montant, 'charge_id' => $paiement->charge_id]]
                );
            } catch (\Exception $e) {}
        }
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

