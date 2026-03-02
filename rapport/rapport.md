# Rapport de Fin de Formation
## Digitalisation des Services de Coaching : Développement d’une Solution Web Intégrée de Gestion et de Branding
### Formation de développement Mobile – Mode Bootcamp

**Réalisée par :** Fadna Lakhouchen  
**Encadré par :** Mr. Essarraj Fouad  

**Année de Formation : 2025/2026**

---

## Table des matières

- [Liste des figures](#liste-des-figures)  
- [Remerciement](#remerciement)  
- [Introduction](#introduction)  
- [Contexte de projet](#contexte-de-projet)  
- [Objectif de projet](#objectif-de-projet)  
- [Cahier de charge](#cahier-de-charge)  
- [Méthode de travail](#méthode-de-travail)  
  - [Scrum](#scrum)  
  - [La méthodologie 2TUP](#la-méthodologie-2tup)  
  - [Design Thinking](#design-thinking)  
- [Branche fonctionnelle](#branche-fonctionnelle)  
  - [Carte d’empathie](#carte-dempathie)  
  - [Définition de problème](#définition-de-problème)  
  - [Diagrammes de cas d’utilisation](#diagrammes-de-cas-dutilisation)  
- [Branche technique](#branche-technique)  
  - [Choix technologiques](#choix-technologiques)  
  - [Architecture de projet](#architecture-de-projet)  
- [Prototype (Fonctionnalités, Classes)](#prototype-fonctionnalités-classes)  
- [Conception](#conception)  
  - [Diagramme de classe](#diagramme-de-classe)  
  - [Maquettes](#maquettes)  
  - [Charte graphique](#charte-graphique)  
- [Réalisation](#réalisation)  
  - [Interfaces](#interfaces)  
- [Conclusion](#conclusion)

---

## Liste des figures



---

## Remerciement

Je souhaite exprimer ma profonde gratitude à toutes les personnes qui ont contribué au succès de mon projet de fin de formation. Un merci tout particulier à **M. Essarraj Fouad** pour son encadrement attentif, ses conseils pertinents et son soutien constant tout au long du projet.  

Je tiens également à remercier l’équipe de **Solicode** pour leur patience, leur accompagnement quotidien et pour avoir créé un environnement d’apprentissage agréable et stimulant.  

Enfin, je remercie mes camarades de promotion pour leur esprit d’entraide, leur bonne humeur et les moments partagés qui ont rendu cette expérience mémorable. Votre soutien et vos échanges ont été précieux pour la réussite de ce projet et de mon stage.

---

## Introduction

Dans le cadre de notre formation à Solicode, le projet « Digitalisation de la gestion du Syndic » a été réalisé pour répondre à un besoin réel dans la gestion des immeubles résidentiels. Actuellement, le suivi des charges et des paiements se fait souvent de manière manuelle, ce qui rend l’organisation difficile et peu transparente.  

L’objectif principal de ce projet est de concevoir une plateforme web  et mobile permettant de centraliser les informations de l’immeuble et d’assurer un meilleur suivi des paiements. La solution proposée vise à améliorer la communication entre les acteurs et à garantir une gestion plus structurée et efficace.  

Pour atteindre ces objectifs, une démarche agile a été adoptée, combinant l’approche **Design Thinking** pour analyser les besoins des utilisateurs et la méthode **Scrum** pour organiser le développement par itérations successives.

---

## Contexte de projet

Le projet de digitalisation de la gestion du Syndic vise à améliorer l’organisation et le suivi des immeubles résidentiels. Il facilite la gestion des charges et des paiements ainsi que la communication entre le syndic et les résidents. Actuellement, la gestion se fait de manière manuelle à l’aide de documents papier. Cette méthode limite l’accès aux informations et complique la mise à jour des données. La mise en place d’une plateforme web et mobile permettra d’assurer une gestion plus efficace et transparente.

---

## Objectif de projet

Dans le cadre de ce projet, nous cherchons à développer une solution digitale qui simplifie la gestion des immeubles et facilite les interactions entre le syndic et les résidents. Cette solution doit permettre un suivi efficace des charges, améliorer la transparence des opérations financières et réduire les conflits tout en organisant les informations de manière claire et structurée.

Le projet a pour objectifs principaux :  
- proposer une solution digitale simple pour améliorer la gestion du syndic et faciliter le suivi des charges,  
- mettre en place un système centralisé de gestion,  
- faciliter le suivi des paiements des résidents,  
- améliorer la transparence des opérations financières,  
- réduire les conflits entre résidents et syndic,  
- organiser les données de manière claire et structurée.

---

## Cahier de charge

## 1. Présentation du projet

**Nom du projet :** ImmoSyndic  
**Type de projet :** Application Web et Mobile pour la gestion des immeubles et du syndic  
**Bénéficiaires :** Résidents, copropriétaires, syndics et administrateurs  

ImmoSyndic est une plateforme centralisée qui facilite la gestion des immeubles, le suivi des paiements et la communication entre tous les acteurs. Elle permet aux résidents de consulter leurs charges et interventions facilement, et aux syndics et administrateurs de gérer et analyser les informations efficacement.



## 2. Contexte et problème

Aujourd’hui, la gestion des immeubles est souvent réalisée manuellement ou via plusieurs outils disparates, ce qui entraîne :  

- Difficultés pour suivre les paiements et charges en temps réel.  
- Communication peu claire entre résidents, syndic et administrateur.  
- Risque d’erreurs et doublons dans les informations financières.  
- Manque de transparence et de traçabilité des opérations.  

Une solution digitale centralisée est donc indispensable pour améliorer le suivi, sécuriser les informations et faciliter la collaboration entre les différents acteurs.


## 3. Objectifs du projet

- Centraliser toutes les informations relatives aux immeubles et aux résidents.  
- Permettre un suivi clair et automatique des paiements et des charges.  
- Faciliter la communication entre résidents, syndics et administrateurs.  
- Offrir des rapports financiers et des tableaux de bord précis.  
- Réduire les conflits et améliorer la transparence des opérations.


## 4. Utilisateurs et rôles

### 4.1 Résident
- **Objectif :** Consulter ses charges, suivre ses paiements et signaler des problèmes.  
- **Besoins :** Interface simple, notifications claires, suivi facile des interventions.

### 4.2 Syndic
- **Objectif :** Gérer un ou plusieurs immeubles, suivre les paiements, planifier les interventions et communiquer avec les résidents.  
- **Besoins :** Tableau de bord par immeuble, accès aux informations financières des résidents, gestion des interventions et notifications ciblées.

### 4.3 Administrateur
- **Objectif :** Superviser l’ensemble de la plateforme, gérer tous les comptes utilisateurs, valider les informations et générer des rapports globaux.  
- **Besoins :** Tableau de bord complet pour tous les immeubles, statistiques consolidées, contrôle des accès, gestion des droits et sécurité de la plateforme.

## 5. Fonctionnalités

### Phase 1 : Fonctions essentielles (MVP)

**Pour les résidents :**  
- Création de compte et connexion sécurisée.  
- Consultation des charges et paiements.  
- Signalement d’interventions ou problèmes.  
- Notifications pour paiements en retard et échéances à venir.

**Pour les syndics :**  
- Gestion des informations propres à leurs immeubles.  
- Suivi des paiements et génération automatique de reçus.  
- Planification et suivi des interventions.  
- Historique des actions et notifications ciblées.

**Pour les administrateurs :**  
- Gestion et contrôle des comptes de tous les utilisateurs.  
- Supervision des immeubles et des paiements.  
- Validation des données et rapports globaux.  
- Gestion des droits et sécurité de la plateforme.


### Phase 2 : Fonctions avancées

**Pour les résidents :**  
- Historique complet des paiements et interventions.  
- Alertes personnalisées pour nouvelles informations ou décisions prises.

**Pour les syndics :**  
- Statistiques détaillées par immeuble et par résident.  
- Gestion centralisée des communications pour leurs immeubles.  
- Tableau de bord amélioré pour une navigation efficace.

**Pour les administrateurs :**  
- Statistiques consolidées sur l’ensemble de la plateforme.  
- Contrôle complet des communications et notifications.  
- Visualisation globale de tous les immeubles et paiements.  
- Amélioration de l’interface pour faciliter la supervision.


## 6. Contraintes et exigences

- Sécurité des données personnelles et financières des utilisateurs.  
- Interface responsive adaptée aux ordinateurs, tablettes et smartphones.  
- Accès différencié selon le rôle (résident, syndic, administrateur).  
- Notifications fiables et en temps réel.  
- Fonctionnalités testées et opérationnelles pour toutes les phases.


## 7. Critères de réussite

1. Les résidents peuvent consulter et suivre leurs paiements et interventions facilement.  
2. Les syndics peuvent gérer efficacement leurs immeubles et résidents.  
3. Les administrateurs peuvent superviser et contrôler la plateforme globalement.  
4. Les notifications et alertes fonctionnent correctement.  
5. Les rapports et tableaux de bord sont précis et lisibles.  
6. L’interface est intuitive et accessible sur tous les appareils.  
7. Toutes les fonctionnalités prévues pour le MVP et les fonctions avancées sont opérationnelles.

---

## Méthode de travail

### Scrum

La méthodologie Scrum est une méthodologie agile qui permet de gérer un projet de manière flexible et collaborative, en favorisant la livraison progressive de fonctionnalités. Elle repose sur l’itération, la priorisation des tâches et la communication régulière entre les membres de l’équipe.  

**Principes clés :**  
- **Transparence :** Toutes les tâches et objectifs sont visibles par l’équipe.  
- **Inspection :** Chaque sprint est évalué pour détecter les améliorations possibles.  
- **Adaptation :** L’équipe ajuste le plan de travail selon les résultats des sprints précédents.  

 <img src="images/scrum.jpg" class="img-methodo" alt="Scrum">

**Sprints du projet :**  
- **Sprint 1 :** Planification et conception  
- **Sprint 2 :** Développement et tests  
- **Sprint 3 :** Finalisation et optimisation

---

### La méthodologie 2TUP
<img src="images/2TUP.PNG" class="img-methodo" alt="2TUP">
La méthodologie **2TUP (Two-Tracks Unified Process)** est un processus de développement logiciel structuré en Y. Elle sépare puis réunit deux dimensions essentielles :  

- Analyse fonctionnelle (ce que doit faire le système)  
- Conception technique (comment le réaliser)  

**Principes clés :**  
- Développement itératif et incrémental  
- Piloté par les risques  
- Séparation fonctionnelle / technique  
- Architecture solide  
- Collaboration continue  

**Structure en Y :**  
1. **Phase initiale :** Capture des besoins  
2. **Branche fonctionnelle :** Analyse fonctionnelle du système  
3. **Branche technique :** Architecture et choix techniques  
4. **Phase de convergence :** Développement, tests, intégration et livraison

---

### Design Thinking
<img src="images/designThinking.png" class="img-methodo" alt="Design Thinking">
**Définition :** Approche centrée sur l’humain visant à créer des solutions innovantes adaptées aux besoins réels des utilisateurs.  

**Objectifs :**  
- Encourager la créativité et l’innovation  
- Développer des solutions adaptées aux utilisateurs  
- Favoriser la collaboration inter-équipes  
- Résoudre des problèmes complexes  

**Étapes :**  
1. **Empathie (Empathize)** : Comprendre l’utilisateur  
2. **Définition du problème (Define)** : Formuler un problème clair  
3. **Idéation (Ideate)** : Générer des idées  
4. **Prototype** : Créer des maquettes ou versions simplifiées  
5. **Test** : Recueillir les retours et améliorer

---

## Branche fonctionnelle

### Carte d’empathie

<img src="images/carte-d-empathie.PNG" class="img-methodo" alt="Carte d'Empathie">

### Définition de problème

**Problématique centrale :**  
- Comment pouvons-nous améliorer la gestion des immeubles et des paiements tout en garantissant la transparence, la communication efficace et la réduction des conflits entre résidents et syndic à travers une solution digitale centralisée ?

### Diagrammes de cas d’utilisation

- **Cas général**  
- **Sprint 1**  
- **Sprint 2**  

*(à compléter avec les diagrammes)*

---

## Branche technique

### Choix technologiques

**Backend :**  
- PHP 8+  
- Laravel 12  
- Eloquent ORM  
- Spatie Laravel Permission  

**Frontend :**  
- Blade Templates  
- Tailwind CSS  
- JavaScript + jQuery  
- Preline Library  
- Vite  

**Base de données :**  
- MySQL  
- Migrations Laravel  

**Outils externes :**  
- Tiptap (éditeur de texte WYSIWYG)

---

### Architecture de projet

**Architecture MVC :**  
- **Modèle :** gestion des données via Eloquent ORM  
- **Vue :** Blade Templates, Tailwind CSS, JavaScript/jQuery  
- **Contrôleur :** logique métier et communication  

**Architecture 3-tiers :**  
- **Couche Présentation :** pages publiques et formulaires  
- **Couche Logique Métier :** validation, gestion des articles et utilisateurs  
- **Couche Accès aux Données :** Modèles Eloquent et MySQL  

**Architecture globale :** MVC + 3-tiers pour modularité, sécurité et maintenance

---

## Prototype (Fonctionnalités, Classes)

**Partie Administrateur :**  
- Ajouter, supprimer, modifier des articles  
- Rechercher et filtrer les articles  

**Partie Publique :**  
- Affichage et consultation des articles  
- Recherche  

**API REST :**  
- Endpoints pour ajouter et afficher les articles  

**Application Mobile :**  
- Consultation des articles via API  
- Interface adaptée au mobile  

**Classes principales :**  
- `User {id, name, email, password, role}`  
- `Article {id, title, slug, excerpt, content, statut, user_id, category_id}`  
- `Category {id, name, slug}`  
- `ArticleCategory {id, article_id, category_id}`

---

## Conception

- Diagramme de classes UML pour modéliser les entités et relations  
- Maquettes des interfaces pour visualiser l’expérience utilisateur  
- Charte graphique pour uniformité visuelle  

### Diagramme de classe

*(à compléter avec le diagramme UML)*

### Maquettes

*(à compléter avec les maquettes des pages)*

### Charte graphique

*(à compléter avec couleurs, typographies et style visuel)*

---

## Réalisation

**Outils de développement :**  
*(à compléter selon le contenu du rapport)*

---

## Interfaces

*(à compléter avec captures d’écran ou description des interfaces)*

---

## Conclusion

*(à compléter selon le contenu du rapport)*
