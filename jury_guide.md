# Guide de Soutenance : Cartographie de l'Application ImmoSyndic

Ce document sert de fiche de révision complète pour votre passage devant le jury. Il associe chaque élément visuel de l'interface (bouton, formulaire, tableau, CRUD) aux fichiers de code correspondants (Blade, Contrôleurs, JavaScript, Modèles) avec des explications techniques prêtes à être présentées.

---

## 🏛️ 1. ESPACE SYNDIC (Principal et Secondaire)

L'espace Syndic permet de gérer les immeubles, les résidents, d'encaisser les cotisations mensuelles, de suivre les incidents techniques et d'administrer des syndics secondaires.

### Tableau de Cartographie de l'Espace Syndic

| Élément UI / Action / Bouton | Fichier Vue (Blade) | Contrôleur & Méthode (Laravel) | Composant JS / Alpine | Table & Colonnes (Base de Données) | Fonctionnement & Argumentation Jury |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Tableau de bord : Compteurs Statistiques** | `admin/syndic/dashboard.blade.php` | `DashboardController@syndicDashboard` | Aucun (Blade direct) | `users`, `incidents`, `paiements`, `charges` | Calcule les totaux dynamiquement (Ex: `paiements_ce_mois` somme le montant de tous les paiements du mois en cours liés aux immeubles gérés par ce syndic). |
| **Bouton "Nouveau signalement" (Modal)** | `admin/syndic/dashboard.blade.php` | `IncidentController@store` | `syndicDashboard.js` | Table `incidents` (colonnes: `titre`, `priorite`, `immeuble_id`, `description`, `statut`) | Ouvre un modal géré par Alpine.js. Les menus déroulants d'immeuble et de priorité sont des dropdowns personnalisés utilisant Alpine.js pour éviter les bogues de sélecteurs classiques et assurer la réactivité. |
| **Alerte : Traitement de paiement cash** | `admin/syndic/dashboard.blade.php` | `DashboardController@markSingleNotificationAsRead` | Aucun | Table `notifications` (colonnes: `lu`, `type`, `message`) | S'affiche en vert si un résident signale qu'il a préparé sa cotisation en espèces. Le bouton "Masquer" met à jour la notification à `lu = true`. |
| **Créer/Modifier/Supprimer un Immeuble** | `admin/syndic/immeubles.blade.php` | `ImmeubleController` (`storeBySyndic`, `updateBySyndic`, `destroyBySyndic`) | `syndicImmeubles.js` | Table `immeubles` (colonnes: `nom`, `adresse`, `ville`, `nombre_etages`, `syndic_id`) | Gère le parc immobilier. Le syndic ne peut voir et modifier que les immeubles dont il est le gérant principal ou secondaire en base. |
| **Activer un Résident en attente** | `admin/syndic/residents.blade.php` | `UserController@activateResidentBySyndic` | `syndicResidents.js` | Table `users` (colonne: `is_active`) | Lorsque le résident s'inscrit, son compte est inactif (`is_active = false`). Ce bouton permet au syndic de valider son identité et de passer `is_active` à `true` pour lui ouvrir l'accès. |
| **Créer/Modifier/Supprimer un Résident** | `admin/syndic/residents.blade.php` | `UserController` (`storeResidentBySyndic`, `updateResidentBySyndic`, `destroyUserBySyndic`) | `syndicResidents.js` | Table `users` et liaison via la table pivot `appartement_user` | Permet d'administrer l'accès des résidents de l'immeuble. La suppression détache le résident de son appartement en base de données. |
| **Enregistrer un encaissement (Cash/Chèque)** | `admin/syndic/paiements.blade.php` | `PaiementController@store` | `syndicPaiements.js` | Table `paiements` (colonnes: `charge_id`, `montant`, `date_paiement`, `recu_path`) | Saisit un versement. Déclenche automatiquement le recalcul du statut de la cotisation (`charges.statut` passe à `payé` si le total versé couvre la dette, sinon à `partiel`). |
| **Modifier / Supprimer un Paiement** | `admin/syndic/paiements.blade.php` | `PaiementController` (`update`, `destroy`) | `syndicPaiements.js` | Tables `paiements` et `charges` | Permet des corrections (ex: erreur de montant). Le contrôleur recalcule dynamiquement le statut de la cotisation précédente et de la nouvelle cotisation en base. |
| **Bouton "Imprimer le Reçu"** | `admin/syndic/paiements.blade.php` | `PaiementController@generateReceipt` | Aucun | Table `paiements` | Ouvre une page épurée et stylisée en CSS (`receipt.blade.php`) contenant un reçu officiel de paiement avec un code-barres généré pour archivage. |
| **Boutons "Exporter Excel / PDF"** | `admin/syndic/paiements.blade.php` | `PaiementController` (`exportExcel`, `exportPdf`) | Aucun | Tables `charges` et `paiements` | Génère des états comptables. L'export Excel configure des en-têtes HTTP spécifiques pour forcer le téléchargement sous format tableur. |
| **Changer le Statut / Priorité d'Incident** | `admin/syndic/interventions.blade.php` | `IncidentController@update` | Aucun | Table `incidents` (colonnes: `statut`, `priorite`) | Permet de passer un incident de "Ouvert" à "En cours" ou "Résolu". Affiche des badges colorés (Rouge pour Urgent, Bleu pour Moyen, etc.) selon la priorité. |
| **Ajouter un Communiqué / Annonce** | `admin/syndic/annonces.blade.php` | `AnnonceController@store` | Aucun | Table `annonces` et création automatique de `notifications` | Enregistre un message destiné aux résidents. Le service (`AnnonceService`) crée automatiquement des notifications pour tous les résidents habitant dans l'immeuble ciblé. |
| **Partager un Document (Règlement, PV)** | `admin/syndic/documents.blade.php` | `DocumentController@storeBySyndic` | Aucun | Table `documents` (colonne: `fichier_path`) | Upload un document PDF/Image. Le fichier est stocké de manière sécurisée dans le dossier local `storage/app/public/documents` et lié en base de données. |
| **Saisir une Dépense de l'immeuble** | `admin/syndic/depenses.blade.php` | `DepenseController@storeBySyndic` | Aucun | Table `depenses` (colonnes: `titre`, `montant`, `justificatif_path`) | Permet d'enregistrer les frais de maintenance (ex: électricité, ampoules) pour le suivi financier de la copropriété. |
| **Associer un Syndic Secondaire** | `admin/syndic/secondary-syndics.blade.php` | `UserController@storeSecondarySyndicBySyndic` | Aucun | Table pivot `immeuble_syndic` | Permet d'ajouter un autre syndic pour l'aider dans la gestion. Il aura accès en lecture/écriture mais ne pourra pas supprimer l'immeuble. |
| **Bouton "Transférer la Gestion"** | `admin/syndic/secondary-syndics.blade.php` | `UserController@transferPrimaryBySyndic` | Aucun | Table `immeubles` et pivot `immeuble_syndic` | Transfère le rôle de gérant principal (Syndic 1) à un syndic secondaire. S'exécute de façon sécurisée au sein d'une **transaction de base de données** pour éviter toute perte de rôle. |
| **Consulter les Logs d'Activité** | `admin/syndic/logs.blade.php` | `DashboardController@syndicLogs` | Aucun | Table `audit_logs` | Permet au syndic principal de tracer en temps réel toutes les actions (ajouts, modifications, suppressions) effectuées par ses adjoints. |

---

## 🏠 2. ESPACE RÉSIDENT

L'espace Résident permet aux habitants de consulter leurs cotisations, de signaler des pannes, de télécharger les documents de l'immeuble et d'accéder au système de transparence financière.

### Tableau de Cartographie de l'Espace Résident

| Élément UI / Action / Bouton | Fichier Vue (Blade) | Contrôleur & Méthode (Laravel) | Table & Colonnes (Base de Données) | Fonctionnement & Argumentation Jury |
| :--- | :--- | :--- | :--- | :--- |
| **Tableau de bord : Solde Personnel** | `admin/resident/dashboard.blade.php` | `DashboardController@residentDashboard` | Table `charges` | Somme le montant des cotisations impayées de son appartement. |
| **Transparence Financière : Liste des Appartements** | `admin/resident/dashboard.blade.php` | `DashboardController@residentDashboard` | Tables `appartements` et `charges` | Affiche quels appartements sont en règle ou en retard de paiement (sans afficher les noms des personnes pour protéger la vie privée). C'est un élément clé pour inciter les résidents à payer à temps. |
| **Bouton "Cotisation Prête (Espèces)"** | `admin/resident/dashboard.blade.php` | `DashboardController@signalReadyToPay` | Table `notifications` | Permet de prévenir le syndic que l'argent liquide est disponible. Le système crée une notification de type `ready_to_pay` liée au syndic de l'immeuble. |
| **Consulter ses Paiements** | `admin/resident/paiements.blade.php` | `DashboardController@residentPaiements` | Table `paiements` | Affiche l'historique de ses versements avec leur statut (ex: validé). |
| **Signaler une panne (Incident)** | `admin/resident/incidents.blade.php` | `IncidentController@storeResidentIncident` | Table `incidents` | Formulaire permettant de déclarer un incident (ex: fuite d'eau) pour que le syndic planifie une intervention. |
| **Consulter les Annonces de l'immeuble** | `admin/resident/annonces.blade.php` | `DashboardController@residentAnnonces` | Table `annonces` | Affiche les avis officiels publiés par le syndic de la résidence. |
| **Télécharger un Document** | `admin/resident/documents.blade.php` | `DashboardController@residentDocuments` | Table `documents` | Met à disposition les PV d'AG et règlements de copropriété en téléchargement direct. |

---

## 👑 3. ESPACE ADMINISTRATEUR (Super-Administrateur)

L'Administrateur supervise l'intégralité de la plateforme : il crée les immeubles, nomme les syndics, contrôle les abonnements, et gère les suspensions de comptes.

### Tableau de Cartographie de l'Espace Administrateur

| Élément UI / Action / Bouton | Fichier Vue (Blade) | Contrôleur & Méthode (Laravel) | Table & Colonnes (Base de Données) | Fonctionnement & Argumentation Jury |
| :--- | :--- | :--- | :--- | :--- |
| **Dashboard : Graphique d'activité** | `admin/administrateur/dashboard.blade.php` | `DashboardController@adminDashboard` | Table `audit_logs` | Compte le nombre d'actions sur la plateforme par jour durant la dernière semaine pour générer un graphique d'activité. |
| **Créer / Modifier / Supprimer un Immeuble** | `admin/administrateur/immeubles.blade.php` | `ImmeubleController` (`store`, `update`, `destroy`) | Table `immeubles` | Permet d'ajouter une nouvelle copropriété et de lui affecter un syndic principal en base. |
| **Bouton "Abonnement" (Détail Modal)** | `admin/administrateur/syndics.blade.php` | Calculé via `User@calculateTotalSubscription` | Tables `users` et `immeubles` | Calcule dynamiquement le coût mensuel pour le syndic (**4 DH/résident** + **8 DH/syndic** pour chaque immeuble géré). Une popup Alpine.js affiche le détail des calculs. |
| **Bouton Activer / Suspendre le compte Syndic** | `admin/administrateur/syndics.blade.php` | `UserController@toggleSyndicStatus` | Table `users` (colonne: `is_active`) | Change la valeur de `is_active` (true/false). Si le syndic est suspendu (`is_active = false`), le middleware de l'application le déconnecte instantanément de sa session. |
| **Logs Système Globaux** | `admin/administrateur/logs.blade.php` | `DashboardController@adminLogs` | Table `audit_logs` | Liste de sécurité montrant toutes les actions critiques de la plateforme (Connexions, créations, modifications, suppressions) avec date, heure et utilisateur. |

---

## 🔒 4. SYSTÈMES ET MIDDLEWARES TRANSVERSAUX (Sécurité et Logique Métier)

Ces fichiers n'ont pas d'interface utilisateur visible, mais ils gèrent le moteur de l'application en arrière-plan. Il est très important de savoir en parler au jury.

### Fichiers Système à Connaître

| Nom du Fichier / Classe | Rôle et Utilité Technique | Argumentation Jury |
| :--- | :--- | :--- |
| **`app/Http/Middleware/RoleMiddleware.php`** | **Contrôle d'accès et de suspension**. Ce middleware intercepte chaque requête vers les espaces Admin, Syndic ou Résident : <br>1. Il vérifie que l'utilisateur a le bon rôle.<br>2. Si c'est un syndic et que son statut est inactif (`is_active = false`), le middleware le déconnecte automatiquement, invalide sa session et le redirige vers le formulaire de connexion avec un message l'informant que son compte est suspendu pour défaut de paiement. | *"Nous avons sécurisé l'application avec un middleware personnalisé. Si l'administrateur désactive un syndic pour non-paiement d'abonnement, le syndic perd immédiatement son accès au tableau de bord."* |
| **`app/Services/AuditLogService.php`** | **Système d'audit et de sécurité**. Service appelé lors de chaque création, modification ou suppression d'un élément important (comme un paiement ou un immeuble). Il enregistre qui a fait quoi, quand et avec quelles données dans la table `audit_logs`. | *"Toutes les actions administratives et financières critiques sont auditées en base de données. Cela garantit une traçabilité totale en cas de litige financier."* |
| **`app/Services/PaiementService.php`** | **Calculateur financier**. Service encapsulant la logique d'obtention des statistiques financières (Solde à payer, total validé, etc.) pour éviter d'alourdir les contrôleurs. | *"Nous suivons les principes de la Clean Architecture en séparant la logique métier des contrôleurs grâce à des services dédiés."* |
