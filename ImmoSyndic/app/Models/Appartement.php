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
