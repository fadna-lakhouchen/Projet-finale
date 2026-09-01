<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Immeuble
 * Représente un immeuble/bâtiment de la copropriété.
 */
class Immeuble extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'syndic_id',           // Identifiant du syndic principal (Syndic 1)
        'nom',                 // Nom de la résidence
        'adresse',             // Adresse physique
        'ville',               // Ville marocaine
        'nombre_etages',       // Nombre total d'étages
        'nombre_appartements', // Nombre d'appartements
        'image',               // Chemin de l'image de couverture
    ];

    /**
     * Accessor pour le nom de l'immeuble traduit
     */
    public function getNomAttribute($value)
    {
        return __($value);
    }

    /**
     * Accessor pour la ville de l'immeuble traduite
     */
    public function getVilleAttribute($value)
    {
        return __($value);
    }

    /**
     * Relation avec le syndic principal.
     * Chaque immeuble a un seul syndic principal (BelongsTo).
     */
    public function syndic()
    {
        return $this->belongsTo(User::class, 'syndic_id');
    }

    /**
     * Relation avec les syndics secondaires associés à l'immeuble.
     * Table de liaison Many-to-Many 'immeuble_syndic'.
     */
    public function secondarySyndics()
    {
        return $this->belongsToMany(User::class, 'immeuble_syndic', 'immeuble_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Relation avec les appartements constitutifs.
     * Un immeuble contient plusieurs appartements (HasMany).
     */
    public function appartements()
    {
        return $this->hasMany(Appartement::class);
    }

    /**
     * Relation avec les annonces diffusées.
     * Un immeuble dispose d'un historique d'annonces (HasMany).
     */
    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    /**
     * Relation avec les incidents signalés.
     * Plusieurs incidents peuvent affecter l'immeuble (HasMany).
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Relation avec les documents partagés.
     * Un immeuble dispose d'un coffre-fort de documents (HasMany).
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Calculer l'abonnement mensuel de l'immeuble
     * Formule de calcul :
     * - Résident de l'immeuble : 4 DH / mois.
     * - Syndic (principal et secondaires) de l'immeuble : 8 DH / mois.
     * Retourne un tableau détaillé avec le nombre d'acteurs et les prix correspondants.
     */
    public function calculateMonthlySubscription()
    {
        // Compter les résidents distincts associés aux appartements de cet immeuble
        $residentsCount = User::where(function ($query) {
                $query->where('role', 'resident')
                      ->orWhereHas('roles', fn ($q) => $q->where('name', 'resident'));
            })
            ->whereHas('appartements', function ($query) {
                $query->where('immeuble_id', $this->id);
            })
            ->distinct()
            ->count();

        // Compter le syndic principal (si existant) + syndics secondaires
        $syndicsCount = ($this->syndic_id ? 1 : 0) + $this->secondarySyndics()->count();

        // Calculer les montants
        $price = ($residentsCount * 4) + ($syndicsCount * 8);

        return [
            'residents_count' => $residentsCount,
            'syndics_count' => $syndicsCount,
            'residents_price' => $residentsCount * 4,
            'syndics_price' => $syndicsCount * 8,
            'total_price' => $price,
        ];
    }
}

