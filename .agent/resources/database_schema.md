# 🗄️ Référence de la Base de Données (Database Schema)

Ce document fournit la description technique exacte du schéma de base de données MySQL pour **ImmoSyndic**. L'Agent IA doit s'y référer pour toute formulation de requêtes Eloquent ou compréhension des jointures logiques.

---

## 📊 1. Modèles de Données & Tables MySQL

### 👤 1.1 Table `users`
Stocke l'ensemble des utilisateurs (Administrateurs, Syndics, Copropriétaires et Résidents).
* **`id`** : `unsignedBigInteger` (Clé primaire, Auto-incrément)
* **`nom`** : `string` (Nom de famille)
* **`prenom`** : `string` (Prénom)
* **`email`** : `string` (Unique, utilisé pour l'authentification)
* **`password`** : `string` (Mot de passe chiffré)
* **`telephone`** : `string` (Numéro de contact)
* **`is_active`** : `boolean` (Statut d'activation du compte, par défaut `true`)
* **`preferences_alertes`** : `json` (Ex: `{"email": true, "push": true, "sms": false}`)
* **`email_verified_at`** : `timestamp` (Nullable)
* **`timestamps`** : `created_at` et `updated_at`

### 🏢 1.2 Table `immeubles`
Représente un bâtiment géré dans la copropriété.
* **`id`** : `unsignedBigInteger` (Clé primaire, Auto-incrément)
* **`nom`** : `string` (Ex: "Résidence Atlas A")
* **`adresse`** : `string` (Adresse physique)
* **`ville`** : `string` (Ex: "Casablanca")
* **`nombre_etages`** : `integer`
* **`nombre_appartements`** : `integer`
* **`image`** : `string` (Chemin d'accès vers la photo de l'immeuble, nullable)
* **`syndic_id`** : `unsignedBigInteger` (Clé étrangère connectée à `users.id` pour le Syndic gérant le bâtiment)
* **`timestamps`**

### 🚪 1.3 Table `appartements`
Unité d'habitation d'un immeuble.
* **`id`** : `unsignedBigInteger` (Clé primaire)
* **`numero`** : `string` (Ex: "Apt 12")
* **`etage`** : `integer`
* **`superficie`** : `decimal(8,2)` (Superficie en m²)
* **`type`** : `string` (Ex: "F3", "F4", "Studio")
* **`immeuble_id`** : `unsignedBigInteger` (Clé étrangère vers `immeubles.id`)
* **`timestamps`**

### 👥 1.4 Table pivot `appartement_user`
Table pivot gérant les occupants successifs ou actuels (Copropriétaires / Locataires).
* **`appartement_id`** : `unsignedBigInteger` (Clé étrangère vers `appartements.id`)
* **`user_id`** : `unsignedBigInteger` (Clé étrangère vers `users.id`)
* **`date_entree`** : `date`
* **`date_sortie`** : `date` (Nullable)
* **`type_resident`** : `enum('Propriétaire', 'Locataire')`

---

## 💰 2. Dépenses & Paiements

### 🧾 2.1 Table `charges`
Les avis de charges ou cotisations courantes affectées à un appartement.
* **`id`** : `unsignedBigInteger` (Clé primaire)
* **`titre`** : `string` (Ex: "Charges communes - Mai 2026")
* **`description`** : `text` (Nullable)
* **`montant`** : `decimal(10,2)` (Montant en DH)
* **`date_echeance`** : `date`
* **`statut`** : `enum('En attente', 'Payé', 'En retard')`
* **`appartement_id`** : `unsignedBigInteger` (Clé étrangère vers `appartements.id`)
* **`timestamps`**

### 💳 2.2 Table `paiements`
Les versements effectués par les résidents pour solder leurs charges.
* **`id`** : `unsignedBigInteger` (Clé primaire)
* **`montant`** : `decimal(10,2)` (Montant payé en DH)
* **`date_paiement`** : `date`
* **`mode_paiement`** : `enum('Virement', 'Versement', 'Chèque', 'Espèces')`
* **`recu_path`** : `string` (Chemin vers le reçu de paiement PDF généré, nullable)
* **`charge_id`** : `unsignedBigInteger` (Clé étrangère unique vers `charges.id`)
* **`user_id`** : `unsignedBigInteger` (Clé étrangère vers `users.id` pour l'auteur du paiement)
* **`timestamps`**

---

## 🛠️ 3. Incidents & Interventions

### ⚠️ 3.1 Table `incidents`
Déclarations de pannes faites par les résidents ou relevées par le syndic.
* **`id`** : `unsignedBigInteger` (Clé primaire)
* **`titre`** : `string` (Ex: "Fuite d'eau cage d'escalier")
* **`description`** : `text`
* **`priorite`** : `enum('Basse', 'Moyenne', 'Haute')`
* **`statut`** : `enum('Signalé', 'En cours d\'analyse', 'Planifié', 'Résolu', 'Rejeté')`
* **`photo`** : `string` (Chemin de l'image de preuve, nullable)
* **`user_id`** : `unsignedBigInteger` (Clé étrangère vers `users.id` - le déclarant)
* **`immeuble_id`** : `unsignedBigInteger` (Clé étrangère vers `immeubles.id`)
* **`timestamps`**

### 👷 3.2 Table `interventions`
Actions techniques programmées pour résoudre un incident.
* **`id`** : `unsignedBigInteger` (Clé primaire)
* **`type`** : `string` (Ex: "Plomberie", "Maçonnerie")
* **`description`** : `text`
* **`date_planifiee`** : `date`
* **`date_realisation`** : `date` (Nullable)
* **`statut`** : `enum('Planifié', 'En cours', 'Terminé', 'Annulé')`
* **`cout_estime`** : `decimal(10,2)` (Estimation budgétaire en DH)
* **`intervenant_nom`** : `string` (Nom de l'artisan ou société prestataire)
* **`incident_id`** : `unsignedBigInteger` (Clé étrangère vers `incidents.id`)
* **`timestamps`**

---

## 📄 4. Relations Eloquent pour le Code Laravel

L'Agent IA doit structurer ses requêtes en exploitant les relations suivantes définies dans les modèles Laravel :

* **Modèle `User` :**
  ```php
  public function immeublesGeres() { return $this->hasMany(Immeuble::class, 'syndic_id'); }
  public function appartements() { return $this->belongsToMany(Appartement::class, 'appartement_user'); }
  public function paiements() { return $this->hasMany(Paiement::class); }
  public function incidentsSignales() { return $this->hasMany(Incident::class); }
  ```

* **Modèle `Immeuble` :**
  ```php
  public function appartements() { return $this->hasMany(Appartement::class); }
  public function syndic() { return $this->belongsTo(User::class, 'syndic_id'); }
  public function incidents() { return $this->hasMany(Incident::class); }
  ```

* **Modèle `Appartement` :**
  ```php
  public function immeuble() { return $this->belongsTo(Immeuble::class); }
  public function residents() { return $this->belongsToMany(User::class, 'appartement_user'); }
  public function charges() { return $this->hasMany(Charge::class); }
  ```

* **Modèle `Incident` :**
  ```php
  public function déclarant() { return $this->belongsTo(User::class, 'user_id'); }
  public function immeuble() { return $this->belongsTo(Immeuble::class); }
  public function interventions() { return $this->hasMany(Intervention::class); }
  ```
