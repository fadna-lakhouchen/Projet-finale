<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle AuditLog
 * Enregistre les actions des utilisateurs (connexions, modifications, créations) pour la traçabilité (Audit Trail).
 */
class AuditLog extends Model
{
    // Désactiver les timestamps automatiques de Laravel (on gère manuellement juste created_at)
    public $timestamps = false;

    // Attributs autorisés pour l'attribution de masse
    protected $fillable = [
        'user_id',       // Utilisateur auteur de l'action
        'action',        // Description de l'action (Ex: created, updated, deleted, login...)
        'model_type',    // Type de modèle impacté (Ex: App\Models\Immeuble) - Utilisé pour le polymorphisme
        'model_id',      // ID de l'instance impactée
        'modifications', // Modifications détaillées au format JSON (avant/après)
    ];

    // Caster les types SQL en types PHP natifs (modifications rattachées en Array JSON, created_at en datetime)
    protected $casts = [
        'modifications' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur auteur de l'action.
     * Chaque log d'audit est lié à un utilisateur (BelongsTo).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphique avec le modèle audité.
     * Permet de récupérer dynamiquement l'objet ciblé (Immeuble, Paiement, etc.) via morphTo.
     */
    public function auditable()
    {
        return $this->morphTo(null, 'model_type', 'model_id');
    }
}

