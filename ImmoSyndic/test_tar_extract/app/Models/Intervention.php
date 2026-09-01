<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Intervention
 * Représente l'intervention d'un technicien externe pour résoudre un incident (Ex: Plomberie, Maintenance ascenseur).
 */
class Intervention extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'incident_id',      // Incident associé à l'intervention
        'type',             // Type d'intervention (Ex: Plomberie, Électricité, Maçonnerie...)
        'description',      // Description de la tâche planifiée ou réalisée
        'date_planifiee',   // Date prévue pour l'intervention
        'date_realisation', // Date réelle de fin d'intervention
        'statut',           // Statut (Planifié, En cours, Réalisé, Annulé)
        'cout_estime',      // Coût financier de l'intervention (MAD)
        'intervenant_nom',  // Nom du prestataire/artisan qui effectue les travaux
    ];

    // Caster les champs de la base de données vers PHP
    protected $casts = [
        'date_planifiee' => 'date',
        'date_realisation' => 'date',
        'cout_estime' => 'decimal:2',
    ];

    /**
     * Accessor pour le type traduit
     */
    public function getTypeAttribute($value)
    {
        return __($value);
    }

    /**
     * Accessor pour la description traduite
     */
    public function getDescriptionAttribute($value)
    {
        return __($value);
    }

    /**
     * Accessor pour le nom de l'intervenant traduit
     */
    public function getIntervenantNomAttribute($value)
    {
        return __($value);
    }

    /**
     * Relation avec l'incident d'origine.
     * Chaque intervention résout un incident technique spécifique (BelongsTo).
     */
    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}

