<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Paiement
 * Enregistre les transactions financières de règlement des charges de copropriété.
 */
class Paiement extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'charge_id',     // Cotisation/Charge concernée
        'user_id',       // Résident qui a effectué le versement
        'montant',       // Montant réglé lors de cette transaction (MAD)
        'date_paiement', // Date effective du versement
        'mode_paiement', // Moyen de paiement (Virement Bancaire, Cash, etc.)
        'statut',        // Statut du versement (en attente, validé)
        'recu_path',     // Fichier justificatif téléversé (PDF ou image)
    ];

    // Caster les types SQL en types PHP natifs
    protected $casts = [
        'date_paiement' => 'date',
        'montant' => 'decimal:2',
    ];

    /**
     * Relation avec la charge correspondante.
     * Un paiement est alloué à une charge mensuelle (BelongsTo).
     */
    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }

    /**
     * Relation avec le résident auteur du règlement.
     * Un paiement est effectué par un utilisateur (BelongsTo).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

