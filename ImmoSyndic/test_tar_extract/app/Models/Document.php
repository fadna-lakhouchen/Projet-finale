<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Document
 * Gère les documents officiels de la copropriété (Règlements, rapports d'AG, factures) partagés par les syndics.
 */
class Document extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'immeuble_id',  // Identifiant de l'immeuble associé au document
        'charge_id',    // Identifiant de la charge associée (si c'est un reçu/justificatif de cotisation)
        'titre',        // Titre explicite du document
        'fichier_path', // Chemin vers le fichier stocké sur le disque dur
        'categorie',    // Catégorie du document (Ex: Règlement de copropriété, Rapport AG, Autre...)
    ];

    // Attribut virtuel ajouté automatiquement à la sérialisation (JSON)
    protected $appends = ['url'];

    /**
     * Accessor pour le titre traduit
     */
    public function getTitreAttribute($value)
    {
        return __($value);
    }

    /**
     * Accessseur (Getter) : URL publique du fichier
     * Permet de récupérer l'URL absolue du fichier pour les liens de téléchargement.
     * Accessible via `$document->url`.
     */
    public function getUrlAttribute()
    {
        if (!$this->fichier_path) return null;
        
        // Utiliser l'hôte de la requête si en contexte web, sinon fallback sur APP_URL
        if (request() && !app()->runningInConsole()) {
            $baseUrl = request()->getSchemeAndHttpHost();
        } else {
            $baseUrl = env('APP_URL', 'http://10.0.2.2:8000');
            if ($baseUrl === 'http://localhost') {
                $baseUrl = 'http://10.0.2.2:8000';
            }
        }

        return rtrim($baseUrl, '/') . '/storage/' . ltrim($this->fichier_path, '/');
    }

    /**
     * Relation avec l'immeuble.
     * Un document est rattaché à un seul immeuble (BelongsTo).
     */
    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    /**
     * Relation avec la charge/cotisation.
     * Un document peut être rattaché facultativement à une charge (BelongsTo).
     */
    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }
}

