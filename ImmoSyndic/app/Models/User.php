<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'cin',
        'ville',
        'date_entree',
        'date_sortie',
        'notes',
        'is_active',
        'preferences_alertes',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'preferences_alertes' => 'array',
        ];
    }

    public function immeubles()
    {
        return $this->hasMany(Immeuble::class, 'syndic_id');
    }

    public function appartements()
    {
        return $this->belongsToMany(Appartement::class, 'appartement_user')
            ->withPivot(['type_resident', 'date_entree', 'date_sortie'])
            ->withTimestamps();
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    public function getNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isAdministrateur()
    {
        return $this->hasRole('administrateur') || $this->role === 'administrateur' || $this->role === 'admin';
    }

    public function isSyndic()
    {
        return $this->hasRole('syndic') || $this->role === 'syndic';
    }

    public function isResident()
    {
        return $this->hasRole('resident') || $this->role === 'resident';
    }
}
