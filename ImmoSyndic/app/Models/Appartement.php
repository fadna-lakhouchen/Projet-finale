<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    protected $fillable = [
        'immeuble_id',
        'numero',
        'etage',
        'superficie',
        'type',
        'statut',
        'cotisation_mensuelle',
        'override_mois_retard',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'cotisation_mensuelle' => 'decimal:2',
        'override_mois_retard' => 'integer',
    ];

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function residents()
    {
        return $this->belongsToMany(User::class, 'appartement_user')
            ->withPivot(['type_resident', 'date_entree', 'date_sortie'])
            ->withTimestamps();
    }

    public function charges()
    {
        return $this->hasMany(Charge::class);
    }
}
