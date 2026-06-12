<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Charge (Cotisations mensuelles)
 * Représente les cotisations de copropriété dues pour chaque appartement et chaque mois.
 */
class Charge extends Model
{
    // Attributs autorisés pour l'attribution de masse (Mass Assignment)
    protected $fillable = [
        'appartement_id', // Identifiant de l'appartement concerné
        'titre',           // Libellé de la charge (Ex: Cotisation de Juin 2026)
        'description',     // Description détaillée de la charge
        'montant',         // Montant exigé (MAD)
        'date_echeance',   // Date limite de règlement
        'statut',          // Statut du paiement global (payé, partiel, impayé)
    ];

    // Caster les champs vers des types de données PHP natifs
    protected $casts = [
        'date_echeance' => 'date',
        'montant' => 'decimal:2',
    ];

    /**
     * Relation avec l'appartement ciblé.
     * Chaque charge est émise pour un seul appartement (BelongsTo).
     */
    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    /**
     * Relation avec les versements de paiements effectués.
     * Une charge peut recevoir plusieurs règlements/versements (HasMany).
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Relation avec les documents justificatifs associés (Reçus de paiement, etc.).
     * Permet d'associer un ou plusieurs fichiers PDF/images (HasMany).
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Accessseur (Getter) : Reste à payer (MAD)
     * Calcule le montant restant dû en soustrayant le total des paiements validés du montant total requis.
     * Accessible via `$charge->reste_a_payer`.
     */
    public function getResteAPayerAttribute()
    {
        $totalPaye = $this->paiements->where('statut', 'validé')->sum('montant');
        return $this->montant - $totalPaye;
    }

    /**
     * Accessseur (Getter) : Nom du premier résident
     * Récupère le nom complet (Prénom Nom) du résident actuel de l'appartement.
     * Accessible via `$charge->resident_nom`.
     */
    public function getResidentNomAttribute()
    {
        $resident = $this->appartement ? $this->appartement->residents->first() : null;
        return $resident ? "{$resident->prenom} {$resident->nom}" : 'Non assigné';
    }

    /**
     * Méthode Statique : Générer la cotisation du mois en cours
     * Vérifie si la cotisation pour le mois courant existe déjà. Sinon, la crée automatiquement
     * en utilisant le montant de cotisation par défaut configuré sur l'appartement.
     */
    public static function generateCurrentMonthCharge($appartementId)
    {
        \Carbon\Carbon::setLocale('fr');
        
        // Vérifier l'existence d'une charge pour le même appartement et le même mois
        $alreadyExists = self::where('appartement_id', $appartementId)
            ->whereYear('date_echeance', now()->year)
            ->whereMonth('date_echeance', now()->month)
            ->exists();

        if (!$alreadyExists) {
            $appt = Appartement::find($appartementId);
            if ($appt) {
                return self::create([
                    'appartement_id' => $appartementId,
                    'titre' => "Cotisation de " . ucfirst(now()->translatedFormat('F Y')),
                    'description' => "Cotisation mensuelle de copropriété pour l'appartement n° " . $appt->numero,
                    'montant' => $appt->cotisation_mensuelle ?? 850.00,
                    'date_echeance' => now()->setDate(now()->year, now()->month, 25), // Échéance fixée au 25 du mois courant
                    'statut' => 'impayé',
                ]);
            }
        }
        return null;
    }
}


