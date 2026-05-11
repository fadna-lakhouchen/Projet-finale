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

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if (!$this->fichier_path) return null;
        
        // Use 10.0.2.2 as default if on localhost, to work correctly with Android Emulators
        $baseUrl = env('APP_URL', 'http://10.0.2.2:8000');
        if ($baseUrl === 'http://localhost') {
            $baseUrl = 'http://10.0.2.2:8000';
        }

        return rtrim($baseUrl, '/') . '/storage/' . ltrim($this->fichier_path, '/');
    }

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }
}
