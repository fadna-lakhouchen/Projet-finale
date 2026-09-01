<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbonnementSyndic extends Model
{
    protected $table = 'abonnements_syndics';

    protected $fillable = [
        'user_id',
        'mois',
        'annee',
        'montant',
        'statut',
        'date_paiement',
        'notes',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant' => 'float',
        'mois' => 'integer',
        'annee' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
