<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $fillable = [
        'immeuble_id',
        'user_id',
        'titre',
        'contenu',
        'date_publication',
    ];

    protected $casts = [
        'date_publication' => 'date',
    ];

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function syndic()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
