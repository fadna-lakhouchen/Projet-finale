<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = [
        'appartement_id',
        'titre',
        'description',
        'montant',
        'date_echeance',
        'statut',
    ];

    protected $casts = [
        'date_echeance' => 'date',
        'montant' => 'decimal:2',
    ];

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
