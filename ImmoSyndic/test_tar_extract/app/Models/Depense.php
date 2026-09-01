<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Dépense
 * Enregistre les dépenses et frais engagés par le syndic pour l'entretien d'un immeuble (Ex: électricité, nettoyage, réparations).
 */
class Depense extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'immeuble_id',       // Identifiant de l'immeuble ayant engagé la dépense
        'titre',             // Titre ou motif de la dépense
        'description',       // Description détaillée
        'montant',           // Montant en MAD
        'date_depense',      // Date effective de la dépense
        'justificatif_path', // Chemin vers la pièce jointe (facture, reçu) stockée dans le système de fichiers
    ];

    // Caster les colonnes de la base de données
    protected $casts = [
        'date_depense' => 'date',
        'montant' => 'decimal:2',
    ];

    // Attributs virtuels ajoutés automatiquement à la sérialisation (JSON)
    protected $appends = ['justificatif_url'];

    /**
     * Accessseur (Getter) : URL publique du justificatif
     * Permet de récupérer l'URL absolue de l'image ou du fichier PDF de la facture.
     * S'adapte dynamiquement si on est dans un navigateur ou en CLI (pour les seeds).
     * Accessible via `$depense->justificatif_url`.
     */
    public function getJustificatifUrlAttribute()
    {
        if (!$this->justificatif_path) return null;
        
        // Utiliser l'hôte de la requête si en contexte web, sinon fallback sur APP_URL
        if (request() && !app()->runningInConsole()) {
            $baseUrl = request()->getSchemeAndHttpHost();
        } else {
            $baseUrl = env('APP_URL', 'http://10.0.2.2:8000');
            if ($baseUrl === 'http://localhost') {
                $baseUrl = 'http://10.0.2.2:8000';
            }
        }

        return rtrim($baseUrl, '/') . '/storage/' . ltrim($this->justificatif_path, '/');
    }

    /**
     * Relation avec l'immeuble.
     * Chaque dépense est imputée à un seul immeuble (BelongsTo).
     */
    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }
}

