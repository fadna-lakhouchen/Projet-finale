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

    /**
     * Get the remaining balance to pay for this charge.
     */
    public function getResteAPayerAttribute()
    {
        $totalPaye = $this->paiements->where('statut', 'validé')->sum('montant');
        return $this->montant - $totalPaye;
    }

    /**
     * Get the full name of the first resident assigned to the apartment.
     */
    public function getResidentNomAttribute()
    {
        $resident = $this->appartement ? $this->appartement->residents->first() : null;
        return $resident ? "{$resident->prenom} {$resident->nom}" : 'Non assigné';
    }
}
