<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Annonce
 * Gère la table des annonces diffusées par les syndics pour informer les résidents des immeubles.
 */
class Annonce extends Model
{
    // Attributs autorisés pour l'attribution de masse (Mass Assignment)
    protected $fillable = [
        'immeuble_id',      // Identifiant de l'immeuble ciblé par l'annonce
        'user_id',          // Identifiant du syndic auteur de l'annonce
        'titre',            // Titre de l'annonce
        'contenu',          // Contenu/corps du message de l'annonce
        'date_publication', // Date effective de publication de l'annonce
    ];

    // Caster les champs de la base de données vers des types PHP natifs (Carbon pour les dates)
    protected $casts = [
        'date_publication' => 'date',
    ];

    /**
     * Accessor pour le titre traduit
     */
    public function getTitreAttribute($value)
    {
        return __($value);
    }

    /**
     * Accessor pour le contenu traduit
     */
    public function getContenuAttribute($value)
    {
        return __($value);
    }

    /**
     * Relation avec l'immeuble ciblé.
     * Une annonce appartient à un seul immeuble (BelongsTo).
     */
    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    /**
     * Relation avec le syndic (créateur de l'annonce).
     * L'annonce est publiée par un utilisateur (de rôle syndic).
     */
    public function syndic()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

