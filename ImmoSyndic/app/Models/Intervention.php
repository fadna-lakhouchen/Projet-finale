<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $fillable = [
        'incident_id',
        'type',
        'description',
        'date_planifiee',
        'date_realisation',
        'statut',
        'cout_estime',
        'intervenant_nom',
    ];

    protected $casts = [
        'date_planifiee' => 'date',
        'date_realisation' => 'date',
        'cout_estime' => 'decimal:2',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}
