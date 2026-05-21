# 🧠 Catalogue des Compétences de l'Agent IA (Skills)

Ce document répertorie les compétences modulaires que l'**Agent IA** peut acquérir et exécuter au sein d'ImmoSyndic. Chaque compétence définit ses entrées (Inputs), ses étapes logiques (Execution Steps) et ses sorties attendues (Outputs).

---

## 🛠️ Compétence #1 : `saisie_incident` (Gestion d'Incidents)

### 🎯 Objectif
Qualifier, catégoriser et pré-remplir la déclaration d'un incident technique à partir de la description informelle d'un résident.

### 📝 Schéma des Paramètres d'Entrée (Inputs)
```json
{
  "user_prompt": "Chaîne de caractères décrivant l'incident en langage naturel"
}
```

### ⚙️ Étapes de Traitement (Execution Steps)
1. **Extraction sémantique :** Analyser le texte pour isoler l'élément en panne et la localisation.
2. **Classification de la Catégorie :** Mapper le problème vers l'une des catégories strictes de la BDD :
   - `Plomberie` (fuites, coupures d'eau).
   - `Électricité` (panne d'éclairage des communs, court-circuit).
   - `Ascenseur` (panne, bruits suspects, cabine bloquée).
   - `Espaces Communs` (portes cassées, nettoyage, jardinage).
   - `Sécurité` (portail parking en panne, caméras défectueuses).
3. **Évaluation de la Priorité :** Assigner un degré d'urgence automatique :
   - `Haute` : Danger physique immédiat, sécurité du bâtiment compromise, ascenseur bloqué.
   - `Moyenne` : Dysfonctionnement d'un équipement majeur n'impactant pas directement la sécurité immédiate.
   - `Basse` : Problème esthétique ou confort mineur (ex: ampoule grillée, propreté).
4. **Génération de l'Appel de Fonction :** Retourner le JSON structuré destiné au backend Laravel.

### 📥 Sortie Attendue (Outputs)
```json
{
  "action": "trigger_incident_modal",
  "data": {
    "titre": "Titre synthétique généré par l'IA",
    "description": "Description clarifiée par l'IA",
    "priorite": "Basse | Moyenne | Haute",
    "categorie": "Plomberie | Électricité | Ascenseur | Espaces Communs | Sécurité"
  }
}
```

---

## ✍️ Compétence #2 : `redacteur_syndic` (Rédaction Administrative)

### 🎯 Objectif
Aider le syndic à concevoir des messages professionnels et percutants pour la copropriété.

### 📝 Schéma des Paramètres d'Entrée (Inputs)
```json
{
  "sujet": "Brève description de l'information à transmettre",
  "ton": "urgence | cordial | formel | info",
  "destinataires": "Nom de l'immeuble ou Tous"
}
```

### ⚙️ Étapes de Traitement (Execution Steps)
1. **Sélection de Template Sémantique :** Charger la structure de communication correspondant au ton choisi.
2. **Génération de Contenu :**
   - Appliquer les formules de politesse adaptées.
   - Ajouter automatiquement des consignes pratiques ou de sécurité spécifiques à la situation.
   - Structurer le message avec des sauts de ligne clairs et du gras (Markdown).
3. **Vérification de Cohérence :** S'assurer que les dates et heures transmises par l'utilisateur sont fidèlement reportées.

### 📥 Sortie Attendue (Outputs)
```json
{
  "document_markdown": "Texte complet prêt pour diffusion",
  "canaux_recommandes": ["email", "push_notification"]
}
```

---

## 📊 Compétence #3 : `auditeur_financier` (Analyse de Charges)

### 🎯 Objectif
Vulgariser et expliquer l'état financier et la répartition des dépenses d'un résident ou d'un immeuble pour lever toute opacité.

### 📝 Schéma des Paramètres d'Entrée (Inputs)
```json
{
  "user_id": "ID de l'utilisateur demandeur",
  "mois": "Mois d'analyse demandé (ex: '2026-05')"
}
```

### ⚙️ Étapes de Traitement (Execution Steps)
1. **Lecture des Données BDD :** Récupérer la table `charges` et `paiements` associées à l'appartement de l'utilisateur pour le mois en cours et le mois précédent.
2. **Calcul de Variation (Delta) :** Calculer le pourcentage d'augmentation ou de baisse.
3. **Identification du Facteur Majeur (Driver) :** Isoler la dépense exceptionnelle ayant provoqué la hausse (ex: facture d'entretien d'ascenseur, réparation de toiture).
4. **Formulation Pédagogique :** Rédiger un texte expliquant calmement les chiffres et rassurant le résident sur la stabilité de ses charges courantes.

### 📥 Sortie Attendue (Outputs)
```json
{
  "total_actuel": "Montant total en DH",
  "variation_pourcentage": "+XX.X%",
  "facteur_explication": "Nom de la charge exceptionnelle",
  "explication_textuelle": "Texte explicatif à afficher dans le chat ou le dashboard"
}
```

---

## 📖 Compétence #4 : `faq_copropriete` (Consultation Réglementaire)

### 🎯 Objectif
Répondre aux questions juridiques ou de vie commune en se basant sur le règlement intérieur de l'immeuble.

### 📝 Schéma des Paramètres d'Entrée (Inputs)
```json
{
  "question_juridique": "Question sur les droits et devoirs au sein de l'immeuble"
}
```

### ⚙️ Étapes de Traitement (Execution Steps)
1. **Recherche Sémantique (Vector Search / Regex) :** Parcourir les articles du règlement de copropriété indexés en base de données.
2. **Extraction de Clause :** Extraire l'article précis correspondant à la question.
3. **Synthèse de Réponse :** Expliquer l'article en langage courant (arabe ou français) et citer explicitement le numéro d'article comme référence.

### 📥 Sortie Attendue (Outputs)
```json
{
  "reponse_simplifiee": "Explication claire",
  "article_cite": "Article X du règlement de copropriété",
  "statut_autorisation": "Autorisé | Interdit | Toléré avec conditions"
}
```
