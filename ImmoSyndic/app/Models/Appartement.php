<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Appartement
 * Gère la table des appartements constitutifs d'un immeuble de copropriété.
 */
class Appartement extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'immeuble_id',           // Identifiant de l'immeuble auquel appartient cet appartement
        'numero',                // Numéro d'appartement (Ex: 12B, A5...)
        'etage',                 // Numéro d'étage
        'superficie',            // Superficie en m²
        'type',                  // Type d'appartement (Ex: Studio, F3, F4...)
        'statut',                // Statut d'occupation (Ex: Occupé, Vide...)
        'cotisation_mensuelle',  // Montant des charges mensuelles (cotisation syndic pour cet appartement)
        'override_mois_retard',  // Dérogation / Ajustement manuel sur le nombre de mois en retard de cotisation
    ];

    // Caster les types SQL en types PHP correspondants
    protected $casts = [
        'superficie' => 'decimal:2',
        'cotisation_mensuelle' => 'decimal:2',
        'override_mois_retard' => 'integer',
    ];

    /**
     * Relation avec l'immeuble parent.
     * Chaque appartement appartient à un unique immeuble (BelongsTo).
     */
    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    /**
     * Relation avec les résidents (locataires ou propriétaires) associés.
     * Relation Many-to-Many via la table pivot 'appartement_user' avec attributs pivots supplémentaires.
     */
    public function residents()
    {
        return $this->belongsToMany(User::class, 'appartement_user')
            ->withPivot(['type_resident', 'date_entree', 'date_sortie'])
            ->withTimestamps();
    }

    /**
     * Relation avec les cotisations/charges mensuelles.
     * Un appartement peut être associé à plusieurs charges mensuelles (HasMany).
     */
    public function charges()
    {
        return $this->hasMany(Charge::class);
    }
}

