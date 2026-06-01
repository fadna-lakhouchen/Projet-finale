<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $fillable = [
        'immeuble_id',
        'titre',
        'description',
        'montant',
        'date_depense',
        'justificatif_path',
    ];

    protected $casts = [
        'date_depense' => 'date',
        'montant' => 'decimal:2',
    ];

    protected $appends = ['justificatif_url'];

    /**
     * Get public URL for the receipt file.
     */
    public function getJustificatifUrlAttribute()
    {
        if (!$this->justificatif_path) return null;
        
        // Use dynamic request host if in web/API context, fallback to APP_URL for console/seeder
        if (request() && !app()->runningInConsole()) {
            $baseUrl = request()->getSchemeAndHttpHost();
        } else {
            $baseUrl = env('APP_URL', 'http://10.0.2.2:8000');
            if ($baseUrl === 'http://localhost') {
                $baseUrl = 'http://10.0.2.2:8000';
            }
        }

        return rtrim($baseUrl, '/') . '/storage/' . ltrim($this->justificatif_path, '/');
    }

    /**
     * Relation with the Immeuble.
     */
    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }
}
