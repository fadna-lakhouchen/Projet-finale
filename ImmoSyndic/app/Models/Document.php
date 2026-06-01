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
        
        // Use dynamic request host if in web/API context, fallback to APP_URL for console/seeder
        if (request() && !app()->runningInConsole()) {
            $baseUrl = request()->getSchemeAndHttpHost();
        } else {
            $baseUrl = env('APP_URL', 'http://10.0.2.2:8000');
            if ($baseUrl === 'http://localhost') {
                $baseUrl = 'http://10.0.2.2:8000';
            }
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
