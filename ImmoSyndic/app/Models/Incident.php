<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Incident
 * Représente un problème technique ou logistique signalé par un résident ou le syndic lui-même (Ex: Fuite d'eau, panne ascenseur).
 */
class Incident extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'user_id',     // Résident auteur du signalement
        'immeuble_id', // Immeuble où l'incident s'est produit
        'titre',       // Titre concis de l'incident
        'description', // Description détaillée
        'priorite',    // Priorité de l'incident (basse, moyenne, haute, urgente)
        'statut',      // Statut actuel (Ouvert, En cours, Résolu)
        'photo',       // Chemin d'accès à la photo justificative
    ];

    /**
     * Relation avec l'utilisateur (le déclarant).
     * Un incident est signalé par un utilisateur (BelongsTo).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'immeuble.
     * L'incident est localisé dans un immeuble (BelongsTo).
     */
    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    /**
     * Relation avec les interventions de réparation.
     * Un incident peut faire l'objet de plusieurs interventions de dépannage (HasMany).
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}

