<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Contrôleur UserController
 * Gère l'administration des utilisateurs de la plateforme (création, modification, activation/désactivation).
 * Il prend en charge l'enregistrement des résidents par l'admin ou le syndic, l'attribution automatique des appartements,
 * la désactivation temporaire des comptes syndic par l'administrateur global, la gestion des syndics secondaires,
 * et le processus de transfert de responsabilité d'immeuble.
 */
class UserController extends Controller
{
    // --- ACTIONS DE L'ADMINISTRATEUR ---

    /**
     * Enregistrer un résident (Store - Administrateur)
     * - Valide les données personnelles et les informations d'appartement.
     * - Recherche ou crée l'appartement correspondant.
     * - Associe le résident à cet appartement (table pivot) et génère la première cotisation mensuelle.
     */
    public function storeResident(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'immeuble_id' => 'required|exists:immeubles,id',
            'numero_appartement' => 'required|string|max:255',
            'date_entree' => 'required|date',
            'override_mois_retard' => 'nullable|integer|min:0',
        ]);

        // Création ou récupération de l'appartement dans la copropriété
        $appt = \App\Models\Appartement::firstOrCreate(
            [
                'immeuble_id' => $request->immeuble_id,
                'numero' => $request->numero_appartement,
            ],
            [
                'etage' => 1,
                'superficie' => 80.00,
                'type' => 'F3',
                'statut' => 'occupé',
            ]
        );

        // Mise à jour de la surcharge de retard (mois manuels si requis par le syndic)
        $appt->update([
            'override_mois_retard' => $request->override_mois_retard,
        ]);

        // Création de l'utilisateur avec le rôle "resident"
        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'cin' => $request->cin,
            'notes' => $request->notes,
            'password' => Hash::make('password'), // Mot de passe par défaut
            'role' => 'resident',
            'is_active' => true,
        ]);

        // Attachement du résident à son appartement via la relation Pivot
        $user->appartements()->attach($appt->id, [
            'date_entree' => $request->date_entree,
        ]);

        // Génération de la cotisation du mois courant pour cet appartement
        \App\Models\Charge::generateCurrentMonthCharge($appt->id);

        return back()->with('success', 'Résident ajouté avec succès.');
    }

    /**
     * Modifier les informations d'un résident (Update - Administrateur)
     * - Valide les champs requis.
     * - Met à jour l'utilisateur et synchronise son affectation d'appartement.
     */
    public function updateResident(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'immeuble_id' => 'required|exists:immeubles,id',
            'numero_appartement' => 'required|string|max:255',
            'date_entree' => 'required|date',
            'override_mois_retard' => 'nullable|integer|min:0',
        ]);

        $appt = \App\Models\Appartement::firstOrCreate(
            [
                'immeuble_id' => $request->immeuble_id,
                'numero' => $request->numero_appartement,
            ],
            [
                'etage' => 1,
                'superficie' => 80.00,
                'type' => 'F3',
                'statut' => 'occupé',
            ]
        );

        $appt->update([
            'override_mois_retard' => $request->override_mois_retard,
        ]);

        $user->update($request->only(['prenom', 'nom', 'email', 'telephone', 'cin', 'notes']));

        // Synchronisation de l'appartement du résident
        $user->appartements()->sync([
            $appt->id => [
                'date_entree' => $request->date_entree,
            ]
        ]);

        // Régénération ou validation de la charge courante de l'appartement
        \App\Models\Charge::generateCurrentMonthCharge($appt->id);

        return back()->with('success', 'Résident mis à jour avec succès.');
    }

    /**
     * Enregistrer un syndic (Store - Administrateur)
     * - Crée le compte syndic et l'active par défaut.
     * - Associe éventuellement ce syndic comme gestionnaire principal des immeubles sélectionnés.
     */
    public function storeSyndic(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:255',
            'date_entree' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $syndic = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'cin' => $request->cin,
            'ville' => $request->ville,
            'date_entree' => $request->date_entree,
            'notes' => $request->notes,
            'password' => Hash::make('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);

        // Assignation immédiate aux immeubles cochés
        if ($request->has('immeubles') && is_array($request->immeubles)) {
            \App\Models\Immeuble::whereIn('id', $request->immeubles)->update(['syndic_id' => $syndic->id]);
        }

        return back()->with('success', 'Syndic ajouté avec succès.');
    }

    /**
     * Modifier les informations d'un syndic (Update - Administrateur)
     * - Met à jour la fiche personnelle.
     * - Réinitialise puis réaffecte les immeubles sous sa gestion principale.
     */
    public function updateSyndic(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:255',
            'date_entree' => 'nullable|date',
            'date_sortie' => 'nullable|date|after_or_equal:date_entree',
            'notes' => 'nullable|string',
        ]);

        $user->update($request->only(['prenom', 'nom', 'email', 'telephone', 'cin', 'ville', 'date_entree', 'date_sortie', 'notes']));

        // Réinitialiser les immeubles précédemment assignés à ce syndic
        \App\Models\Immeuble::where('syndic_id', $user->id)->update(['syndic_id' => null]);
        
        // Assigner les nouveaux immeubles sélectionnés dans le formulaire
        if ($request->has('immeubles') && is_array($request->immeubles)) {
            \App\Models\Immeuble::whereIn('id', $request->immeubles)->update(['syndic_id' => $user->id]);
        }

        return back()->with('success', 'Syndic mis à jour avec succès.');
    }

    /**
     * Supprimer définitivement un utilisateur (Admin)
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Activer ou désactiver temporairement un compte syndic (Admin)
     * - Bloque l'accès à la plateforme si désactivé via le middleware de sécurité.
     * - Enregistre l'action dans le journal d'audit (Audit Log).
     */
    public function toggleSyndicStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $statusMessage = $user->is_active ? 'activé' : 'désactivé';

        // Journalisation de la mise hors-tension ou de l'activation
        if (class_exists(\App\Services\AuditLogService::class)) {
            try {
                app(\App\Services\AuditLogService::class)->logAction(
                    auth()->id(),
                    'updated',
                    User::class,
                    $user->id,
                    ['description' => "Statut du syndic {$user->name} changé en {$statusMessage}"]
                );
            } catch (\Exception $e) {
                // Ignore les exceptions de logs pour éviter de bloquer l'action
            }
        }

        return back()->with('success', "Le compte du syndic a été {$statusMessage} avec succès.");
    }

    // --- ACTIONS DU SYNDIC ---

    /**
     * Enregistrer un résident par le Syndic
     */
    public function storeResidentBySyndic(Request $request)
    {
        return $this->storeResident($request);
    }

    /**
     * Mettre à jour un résident par le Syndic
     */
    public function updateResidentBySyndic(Request $request, $id)
    {
        return $this->updateResident($request, $id);
    }

    /**
     * Supprimer un utilisateur par le Syndic
     */
    public function destroyUserBySyndic($id)
    {
        return $this->destroyUser($id);
    }

    /**
     * Valider et approuver l'inscription d'un résident (Syndic)
     * - Change l'état is_active de false à true pour lui donner l'accès à son tableau de bord.
     * - Sécurité : Le syndic connecté doit gérer la copropriété du résident ciblé.
     */
    public function activateResidentBySyndic($id)
    {
        $user = User::findOrFail($id);
        
        // Sécurité : Vérifier le chevauchement des immeubles gérés
        $userImmeubleIds = $user->appartements()->pluck('immeuble_id')->toArray();
        $syndicImmeubleIds = auth()->user()->managedImmeubles()->pluck('id')->toArray();
        
        if (empty(array_intersect($userImmeubleIds, $syndicImmeubleIds))) {
            abort(403, 'Accès non autorisé.');
        }

        $user->update(['is_active' => true]);

        return back()->with('success', 'Résident activé et approuvé avec succès.');
    }

    /**
     * Enregistrer et assigner un syndic secondaire (Syndic principal)
     * - Permet au syndic propriétaire de déléguer des tâches à un collègue syndic.
     * - Associe le syndic secondaire à l'immeuble via la table pivot secondary_syndics.
     */
    public function storeSecondarySyndicBySyndic(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'immeuble_id' => 'required|exists:immeubles,id',
        ]);

        $immeuble = \App\Models\Immeuble::findOrFail($request->immeuble_id);

        // Sécurité : Seul le syndic principal affecté à l'immeuble peut ajouter des adjoints/secondaires
        if ($immeuble->syndic_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $secondarySyndic = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'cin' => $request->cin,
            'password' => Hash::make('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);

        // Association de la relation de co-gestion dans la table de pivot
        $immeuble->secondarySyndics()->attach($secondarySyndic->id);

        if (class_exists(\App\Services\AuditLogService::class)) {
            try {
                app(\App\Services\AuditLogService::class)->logAction(
                    auth()->id(),
                    'created',
                    User::class,
                    $secondarySyndic->id,
                    ['after' => ['nom' => $secondarySyndic->name, 'email' => $secondarySyndic->email, 'role' => 'secondary_syndic']]
                );
            } catch (\Exception $e) {
                // Ignore
            }
        }

        return back()->with('success', 'Syndic secondaire ajouté et assigné avec succès.');
    }

    /**
     * Retirer un syndic secondaire d'une copropriété (Syndic principal)
     * - Supprime la liaison pivot.
     * - Si le compte secondaire n'est plus rattaché à aucun autre immeuble, son compte est détruit pour économiser de l'espace.
     */
    public function destroySecondarySyndicBySyndic($id)
    {
        $secondarySyndic = User::findOrFail($id);
        
        // Sélectionne les immeubles de ce syndic principal qui sont liés au syndic secondaire
        $linkedImmeubles = $secondarySyndic->secondaryImmeubles()
            ->where('syndic_id', auth()->id())
            ->get();

        if ($linkedImmeubles->isEmpty()) {
            abort(403, 'Accès non autorisé.');
        }

        // Détachement des droits de gestion du syndic secondaire sur ces copropriétés
        foreach ($linkedImmeubles as $immeuble) {
            $immeuble->secondarySyndics()->detach($secondarySyndic->id);
        }

        if (class_exists(\App\Services\AuditLogService::class)) {
            try {
                app(\App\Services\AuditLogService::class)->logAction(
                    auth()->id(),
                    'deleted',
                    User::class,
                    $secondarySyndic->id,
                    ['before' => ['nom' => $secondarySyndic->name, 'email' => $secondarySyndic->email]]
                );
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // Nettoyage automatique : Si le syndic n'a plus d'attribution, suppression physique du record
        if ($secondarySyndic->secondaryImmeubles()->count() == 0 && $secondarySyndic->immeubles()->count() == 0) {
            $secondarySyndic->delete();
        }

        return back()->with('success', 'Syndic secondaire retiré avec succès.');
    }

    /**
     * Transférer la responsabilité principale de gestion d'immeuble à un adjoint (Syndic principal -> Syndic secondaire)
     * - Permet une passation de pouvoir propre via une transaction de base de données.
     * - Le syndic secondaire sélectionné devient le syndic principal unique.
     * - L'ancien syndic principal est rétrogradé au rang de syndic secondaire sur cette copropriété.
     */
    public function transferPrimaryBySyndic(Request $request, $id)
    {
        $secondarySyndic = User::findOrFail($id);

        $request->validate([
            'immeuble_id' => 'required|exists:immeubles,id',
        ]);

        $immeuble = \App\Models\Immeuble::findOrFail($request->immeuble_id);

        // Sécurité : Seul le propriétaire actuel de l'immeuble peut initier la passation
        if ($immeuble->syndic_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Sécurité : L'utilisateur ciblé doit faire partie des syndics secondaires de la copropriété
        if (!$immeuble->secondarySyndics->contains($secondarySyndic->id)) {
            abort(400, 'L\'utilisateur sélectionné n\'est pas un syndic secondaire de cet immeuble.');
        }

        // Transaction DB pour garantir la rétrogradation et l'élévation simultanées sans états intermédiaires corrompus
        \Illuminate\Support\Facades\DB::transaction(function() use ($immeuble, $secondarySyndic) {
            $primaryId = auth()->id();

            // 1. Détacher le promu de la co-gestion (pivot)
            $immeuble->secondarySyndics()->detach($secondarySyndic->id);

            // 2. Rattacher le syndic sortant à la co-gestion (pivot)
            $immeuble->secondarySyndics()->attach($primaryId);

            // 3. Mettre à jour l'ID du gestionnaire principal sur l'immeuble
            $immeuble->update(['syndic_id' => $secondarySyndic->id]);
        });

        // Journalisation de la passation de pouvoir
        if (class_exists(\App\Services\AuditLogService::class)) {
            try {
                app(\App\Services\AuditLogService::class)->logAction(
                    auth()->id(),
                    'updated',
                    \App\Models\Immeuble::class,
                    $immeuble->id,
                    [
                        'before' => ['syndic_id' => auth()->id()],
                        'after' => ['syndic_id' => $secondarySyndic->id],
                        'description' => 'Transfert du rôle principal à ' . $secondarySyndic->prenom . ' ' . $secondarySyndic->nom
                    ]
                );
            } catch (\Exception $e) {
                // Ignore
            }
        }

        return redirect()->route('dashboard')->with('success', 'Rôle principal transféré avec succès. Vous êtes maintenant un syndic secondaire de cet immeuble.');
    }
}

