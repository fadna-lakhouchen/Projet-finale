<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'immeuble_id',
        'charge_id',
        'titre',
        'fichier_path',
        'categorie',
    ];

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }
}
