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



---

## Méthode de travail

### Scrum

La méthodologie Scrum est une méthodologie agile qui permet de gérer un projet de manière flexible et collaborative, en favorisant la livraison progressive de fonctionnalités. Elle repose sur l’itération, la priorisation des tâches et la communication régulière entre les membres de l’équipe.  

**Principes clés :**  
- **Transparence :** Toutes les tâches et objectifs sont visibles par l’équipe.  
- **Inspection :** Chaque sprint est évalué pour détecter les améliorations possibles.  
- **Adaptation :** L’équipe ajuste le plan de travail selon les résultats des sprints précédents.  

**Sprints du projet :**  
- **Sprint 1 :** Planification et conception  
- **Sprint 2 :** Développement et tests  
- **Sprint 3 :** Finalisation et optimisation

---

### La méthodologie 2TUP

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

*(à compléter selon le contenu du rapport)*

### Définition de problème

**Problématique centrale :**  
- Comment créer une solution digitale qui améliore la transparence et encourage les résidents à payer leurs charges à temps ?

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
