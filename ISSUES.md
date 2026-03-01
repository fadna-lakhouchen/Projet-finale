# Liste des Issues du Projet

Cette liste décrit les tâches principales (issues) à traiter dans chaque branche du projet, afin d'assurer une structure de travail claire et organisée.

## Issue #1 : Configuration et structuration de la Base de Données
- **Branche associée :** `database`
- **Description :** 
  - Concevoir le schéma de la base de données.
  - Créer les migrations pour les différentes tables (utilisateurs, immeubles, appartements, paiements, etc.).
  - Mettre en place les Seeders et les Factories pour générer des données de test.
  - S'assurer que les relations entre les tables sont correctement définies.

## Issue #2 : Implémentation de la logique métier (Services)
- **Branche associée :** `services`
- **Description :**
  - Créer les classes de services pour séparer la logique métier des contrôleurs.
  - Implémenter la logique d'ajout et de gestion des résidents, des syndics et des administrateurs.
  - Centraliser les traitements complexes (calcul des charges, gestion des paiements).
  - Écrire des tests unitaires pour valider les comportements de chaque service.

## Issue #3 : Développement de l'API Mobile
- **Branche associée :** `mobile_api`
- **Description :**
  - Créer les endpoints API (routes `api.php`) pour l'application mobile (connexion, consultation des charges, etc.).
  - Mettre en place l'authentification via Laravel Sanctum pour sécuriser l'API.
  - Formater les réponses de l'API en utilisant des API Resources (JSON).
  - Gérer les erreurs et les exceptions spécifiques aux requêtes de l'API.

## Issue #4 : Amélioration de l'Exploitation et de la Sécurité
- **Branche associée :** `Exploitation_securite`
- **Description :**
  - Configurer les politiques d'autorisation (Policies et Gates).
  - Mettre en place des logs de sécurité pour surveiller les connexions et les actions sensibles.
  - Nettoyer et sécuriser les entrées utilisateurs (validation des requêtes).
  - S'assurer de la bonne gestion des environnements (variables `.env` sécurisées).
