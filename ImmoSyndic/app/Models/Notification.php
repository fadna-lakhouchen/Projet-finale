<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Notification
 * Enregistre les notifications système destinées aux utilisateurs (Ex: Rappel de paiement de charges, alerte incident).
 */
class Notification extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'user_id',    // Utilisateur destinataire de la notification
        'titre',      // Titre ou sujet bref de la notification
        'message',    // Message détaillé
        'type',       // Catégorie (Info, Warning, Urgence...)
        'lu',         // État de lecture (Boolean)
        'date_envoi', // Date et heure de création de la notification
    ];

    // Caster les valeurs vers PHP
    protected $casts = [
        'lu' => 'boolean',
        'date_envoi' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur destinataire.
     * Une notification appartient à un utilisateur (BelongsTo).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

