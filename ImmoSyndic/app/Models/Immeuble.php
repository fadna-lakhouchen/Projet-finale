<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Immeuble extends Model
{
    protected $fillable = [
        'syndic_id',
        'nom',
        'adresse',
        'ville',
        'nombre_etages',
        'nombre_appartements',
        'image',
    ];

    public function syndic()
    {
        return $this->belongsTo(User::class, 'syndic_id');
    }

    public function appartements()
    {
        return $this->hasMany(Appartement::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
