<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modèle User
 * Représente tous les utilisateurs du système (Administrateurs, Syndics, Résidents).
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Attributs autorisés pour le Mass Assignment.
     */
    protected $fillable = [
        'nom',                 // Nom de famille
        'prenom',              // Prénom
        'email',               // Adresse de messagerie électronique (sert de login)
        'password',            // Mot de passe hashé
        'telephone',           // Numéro de téléphone
        'cin',                 // Carte d'Identité Nationale (CIN)
        'ville',               // Ville de résidence
        'date_entree',         // Date d'entrée dans la copropriété
        'date_sortie',         // Date de sortie/déménagement
        'notes',               // Remarques ou notes internes
        'is_active',           // État du compte : Actif (true) ou Suspendu (false)
        'preferences_alertes', // Préférences de notifications (JSON)
        'role',                // Rôle textuel de l'utilisateur (administrateur, syndic, resident)
        'google_id',
        'google_token',
    ];

    /**
     * Attributs masqués lors de la sérialisation (Ex: API JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Caster les types SQL en types PHP natifs.
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

    /**
     * Relation avec les immeubles gérés en tant que syndic principal.
     * Un syndic principal a plusieurs immeubles rattachés (HasMany).
     */
    public function immeubles()
    {
        return $this->hasMany(Immeuble::class, 'syndic_id');
    }

    /**
     * Relation avec les immeubles gérés en tant que syndic secondaire.
     * Relation Many-to-Many via la table pivot 'immeuble_syndic'.
     */
    public function secondaryImmeubles()
    {
        return $this->belongsToMany(Immeuble::class, 'immeuble_syndic', 'user_id', 'immeuble_id')
            ->withTimestamps();
    }

    /**
     * Requête : Récupérer tous les immeubles gérés (soit comme principal, soit comme secondaire).
     * Retourne une instance de Query Builder d'Immeuble.
     */
    public function managedImmeubles()
    {
        return Immeuble::where(function ($query) {
            $query->where('syndic_id', $this->id)
                  ->orWhereHas('secondarySyndics', function ($q) {
                      $q->where('users.id', $this->id);
                  });
        });
    }

    /**
     * Relation avec les appartements habités ou possédés (pour les résidents).
     * Relation Many-to-Many via la table pivot 'appartement_user'.
     */
    public function appartements()
    {
        return $this->belongsToMany(Appartement::class, 'appartement_user')
            ->withPivot(['type_resident', 'date_entree', 'date_sortie'])
            ->withTimestamps();
    }

    /**
     * Relation avec les paiements de charges effectués par l'utilisateur.
     * Un utilisateur dispose d'un historique de paiements (HasMany).
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Relation avec les incidents déclarés par l'utilisateur.
     * Un utilisateur peut déclarer plusieurs pannes/incidents (HasMany).
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /**
     * Relation avec les notifications reçues par l'utilisateur.
     * Un utilisateur dispose d'une boîte de réception de notifications (HasMany).
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relation avec les annonces publiées (pour les syndics).
     * Un syndic peut publier plusieurs annonces (HasMany).
     */
    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    /**
     * Accessseur (Getter) : Nom complet
     * Récupère le prénom et le nom concaténés. Accessible via `$user->name`.
     */
    public function getNameAttribute()
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /**
     * Accessor pour le prénom traduit
     */
    public function getPrenomAttribute($value)
    {
        return __($value);
    }

    /**
     * Accessor pour le nom de famille traduit
     */
    public function getNomAttribute($value)
    {
        return __($value);
    }

    /**
     * Relation avec les logs d'audit des actions effectuées par cet utilisateur.
     * Un utilisateur a généré plusieurs entrées de log d'audit (HasMany).
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Accessseur (Getter) : active_role
     * Récupère le rôle actif en cours stocké dans la session de l'utilisateur
     * avec vérification de la légitimité des rôles.
     */
    public function getActiveRoleAttribute()
    {
        if (app()->bound('session') && request()->hasSession()) {
            $sessionRole = session('active_role');
            if ($sessionRole && ($this->hasRole($sessionRole) || $this->role === $sessionRole)) {
                return $sessionRole;
            }
        }
        return $this->role;
    }

    /**
     * Helper : Vérifier si l'utilisateur possède plusieurs rôles configurés.
     */
    public function hasMultipleRoles()
    {
        $rolesCount = 0;
        if ($this->hasRole('syndic') || $this->role === 'syndic') {
            $rolesCount++;
        }
        if ($this->hasRole('resident') || $this->role === 'resident') {
            $rolesCount++;
        }
        if ($this->hasRole('administrateur') || $this->role === 'administrateur' || $this->role === 'admin') {
            $rolesCount++;
        }
        return $rolesCount > 1;
    }

    /**
     * Helper : Vérifier si l'utilisateur est administrateur.
     */
    public function isAdministrateur()
    {
        return $this->active_role === 'administrateur' || $this->active_role === 'admin';
    }

    /**
     * Helper : Vérifier si l'utilisateur est un syndic.
     */
    public function isSyndic()
    {
        return $this->active_role === 'syndic';
    }

    /**
     * Helper : Vérifier si l'utilisateur est un résident.
     */
    public function isResident()
    {
        return $this->active_role === 'resident';
    }

    /**
     * Envoyer la notification de vérification de l'adresse email.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }

    public function abonnementsSyndics()
    {
        return $this->hasMany(AbonnementSyndic::class, 'user_id');
    }

    /**
     * Calculer l'abonnement global dû par ce syndic
     * Additionne l'abonnement mensuel de tous les immeubles dont il est le syndic principal.
     */
    public function calculateTotalSubscription()
    {
        $total = 0;
        $breakdown = [];

        foreach ($this->immeubles as $immeuble) {
            $calc = $immeuble->calculateMonthlySubscription();
            $total += $calc['total_price'];
            $breakdown[] = [
                'immeuble' => $immeuble,
                'calculation' => $calc,
            ];
        }

        return [
            'total_price' => $total,
            'breakdown' => $breakdown,
        ];
    }
}

