# Ideation – Use Cases & Fonctionnalités par Sprint

## Sprint 1 – MVP (Planification & Conception)

### Use Cases principaux
| ID | Acteur | Description | Résultat attendu |
|----|--------|-------------|-----------------|
| UC‑1 | Résident | Créer un compte et se connecter | Accès sécurisé au tableau de bord résident |
| UC‑2 | Résident | Consulter les charges de son appartement | Affichage clair du montant et de l'échéance |
| UC‑3 | Syndic | Ajouter / modifier un immeuble | Immeuble enregistré dans la base de données |
| UC‑4 | Syndic | Visualiser la liste des résidents d’un immeuble | Tableau listant nom, appartement, état de paiement |
| UC‑5 | Administrateur | Gérer les rôles (admin, syndic, résident) | Permissions correctement appliquées |

### Fonctionnalités implémentées
- **Gestion des comptes** : inscription, connexion (email + mot de passe) avec validation côté serveur.
- **Tableau de bord résident** : affichage des charges, statut paiement, historique minimal.
- **CRUD Immeuble** : création, édition, suppression des immeubles pour les syndics.
- **Gestion des rôles** via *Spatie Laravel Permission*.
- **API REST** basique pour les opérations ci‑dessus (endpoints : `/api/residents`, `/api/immeubles`).
- **Tests unitaires** couvrant les contrôleurs d’authentification et les modèles `User`, `Immeuble`.

## Sprint 2 – Fonctionnalités Avancées (Développement & Tests)

### Use Cases supplémentaires
| ID | Acteur | Description | Résultat attendu |
|----|--------|-------------|-----------------|
| UC‑6 | Résident | Déclarer une intervention (panne, problème) | Ticket créé, notification au syndic |
| UC‑7 | Résident | Recevoir des notifications de rappel de paiement | E‑mail / push envoyé avant l’échéance |
| UC‑8 | Syndic | Générer un reçu de paiement | PDF téléchargeable, enregistré dans le système |
| UC‑9 | Syndic | Produire un rapport de charges par immeuble | Tableau agrégé affichant totaux par période |
| UC‑10| Administrateur | Exporter les données utilisateurs (CSV) | Fichier CSV contenant toutes les informations nécessaires |

### Fonctionnalités ajoutées
- **Gestion des interventions** : création, suivi du statut, affectation au personnel.
- **Système de notifications** (mail + Laravel notifications) pour rappels de paiement et nouveaux tickets.
- **Génération de PDF** (`barryvdh/laravel-dompdf`) pour les reçus et rapports.
- **Tableaux de bord analytiques** avec graphiques (Chart.js) pour le syndic.
- **Export CSV** des listes d’utilisateurs et de paiements.
- **Tests d’intégration** avec Postman/Newman et couverture > 80 %.

## Sprint 3 – Optimisation & Extension (Finalisation & Optimisation)

### Use Cases étendus
| ID | Acteur | Description | Résultat attendu |
|----|--------|-------------|-----------------|
| UC‑11 | Résident | Consulter l’historique complet de ses paiements et interventions | Vue paginée avec filtres date/etat |
| UC‑12 | Résident | Personnaliser les alertes (type, fréquence) | Paramètres sauvegardés et appliqués aux notifications |
| UC‑13 | Syndic | Accéder à des statistiques avancées (pLI, taux d’impayés) | Dashboard avec indicateurs clés de performance |
| UC‑14 | Administrateur | Gestion des backups de la base de données | Sauvegarde planifiée, restauration possible via UI |
| UC‑15 | Tous | Utiliser l’application mobile (iOS/Android) | API sécurisée consumée par l’app mobile native |

### Fonctionnalités finales
- **Historique complet** avec pagination serveur‑side et filtres avancés.
- **Paramétrage des notifications** (type, canal, fréquence) via interface UI.
- **Statistiques avancées** (graphes, indicateurs de performance) pour le syndic.
- **Module de backup** automatisé (artisan command `php artisan backup:run`).
- **API mobile** conforme aux standards OAuth2 / JWT pour les applications natives.
- **Optimisation performance** : cache Redis pour les listes fréquentes, indexation DB, audit des requêtes lentes.
- **Tests de charge** (k6) et améliorations de sécurité (scan OWASP).
- **Documentation complète** (OpenAPI spec) et guide d’utilisation.

---

**Note** : Chaque sprint a été planifié avec des revues et des rétrospectives afin d’ajuster le périmètre fonctionnel en fonction des retours utilisateurs et des contraintes techniques.
