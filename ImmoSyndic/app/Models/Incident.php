<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'user_id',
        'immeuble_id',
        'titre',
        'description',
        'priorite',
        'statut',
        'photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
