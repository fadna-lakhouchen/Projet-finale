<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Notification
 * Enregistre les notifications système destinées aux utilisateurs (Ex: Rappel de paiement de charges, alerte incident).
 */
class Notification extends Model
{
    // Attributs autorisés pour le Mass Assignment
    protected $fillable = [
        'user_id',    // Utilisateur destinataire de la notification
        'titre',      // Titre ou sujet bref de la notification
        'message',    // Message détaillé
        'type',       // Catégorie (Info, Warning, Urgence...)
        'lu',         // État de lecture (Boolean)
        'date_envoi', // Date et heure de création de la notification
    ];

    // Caster les valeurs vers PHP
    protected $casts = [
        'lu' => 'boolean',
        'date_envoi' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur destinataire.
     * Une notification appartient à un utilisateur (BelongsTo).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor pour le titre traduit
     */
    public function getTranslatedTitreAttribute()
    {
        return __($this->titre);
    }

    /**
     * Accessor pour le message traduit
     */
    public function getTranslatedMessageAttribute()
    {
        $message = $this->message;

        // Pattern 1: Votre paiement de 850.00 MAD pour les charges de Janvier 2025 a été validé.
        if (preg_match('/Votre paiement de (.+?) pour les charges de (.+?) a été validé\./i', $message, $matches)) {
            return __('Votre paiement de :montant pour les charges de :mois a été validé.', [
                'montant' => str_replace(['MAD', 'DH'], [__('MAD'), __('DH')], $matches[1]),
                'mois' => self::translateMonthYear($matches[2])
            ]);
        }

        // Pattern 2: Une nouvelle annonce a été publiée : Coupure d'eau vendredi 14 février.
        if (preg_match('/Une nouvelle annonce a été publiée : (.+?)\./i', $message, $matches)) {
            return __('Une nouvelle annonce a été publiée : :titre.', [
                'titre' => __($matches[1])
            ]);
        }

        // Pattern 3: Votre charge de Mars 2025 (850.00 MAD) est due avant le 31/03/2025.
        if (preg_match('/Votre charge de (.+?) \((.+?)\) est due avant le (.+?)\./i', $message, $matches)) {
            return __('Votre charge de :mois (:montant) est due avant le :date.', [
                'mois' => self::translateMonthYear($matches[1]),
                'montant' => str_replace(['MAD', 'DH'], [__('MAD'), __('DH')], $matches[2]),
                'date' => $matches[3]
            ]);
        }

        // Pattern 4: L\'incident sur l\'ascenseur a bien été enregistré. Le syndic a été notifié.
        if (preg_match('/L\'incident sur (.+?) a bien été enregistré\. Le syndic a été notifié\./i', $message, $matches)) {
            return __('L\'incident sur :element a bien été enregistré. Le syndic a été notifié.', [
                'element' => __($matches[1])
            ]);
        }

        // Pattern 5: Le résident Mohamed Alami (Appt 12) est prêt à régler sa cotisation de Juin 2026 (850.00 MAD) en espèces. Note: ...
        if (preg_match('/Le résident (.+?) \(Appt (.+?)\) est prêt à régler sa cotisation de (.+?) \((.+?)\) en espèces\.(?: Note: (.+))?/i', $message, $matches)) {
            $note = isset($matches[5]) ? " " . __('Note:') . " " . $matches[5] : "";
            return __('Le résident :resident (Appt :appt) est prêt à régler sa cotisation de :cotisation (:montant) en espèces.', [
                'resident' => self::translateName($matches[1]),
                'appt' => $matches[2],
                'cotisation' => self::translateMonthYear($matches[3]),
                'montant' => str_replace(['MAD', 'DH'], [__('MAD'), __('DH')], $matches[4])
            ]) . $note;
        }

        return __($message);
    }

    /**
     * Traduire une chaîne contenant Mois Année (Ex: Janvier 2025)
     */
    public static function translateMonthYear($string)
    {
        $parts = explode(' ', trim($string));
        if (count($parts) === 2) {
            $month = __($parts[0]);
            $year = $parts[1];
            return $month . ' ' . $year;
        }
        return __($string);
    }

    /**
     * Traduire un nom complet (Ex: Fatima Tazi -> فاطمة تازي)
     */
    public static function translateName($name)
    {
        if (empty($name)) {
            return $name;
        }
        $parts = explode(' ', trim($name));
        $translatedParts = array_map(function($part) {
            return __($part);
        }, $parts);
        return implode(' ', $translatedParts);
    }
}

