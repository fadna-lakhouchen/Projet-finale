# 🤖 Workspace de l'Agent IA - ImmoSyndic

Bienvenue dans le workspace de l'**Agent IA**. Ce dossier sert de **socle technique, conceptuel et opérationnel** standardisé pour l'intégration de l'Intelligence Artificielle au sein de l'écosystème **ImmoSyndic**. 

L'ensemble de ce dossier est conçu pour **guider, contraindre et spécifier** le comportement des modèles d'IA (LLMs), évitant ainsi toute déviation comportementale, mauvaise génération de code ou non-respect des règles de la copropriété.

---

## 📂 Structure Standardisée du Workspace IA

Le dossier est organisé selon une structure modulaire stricte, permettant aux développeurs et aux futurs agents IA de comprendre l'environnement de travail :

```text
📁 Agent IA/
├── 📄 README.md                        # Présentation & Structure du Workspace (Ce document)
├── 📄 1_concept_integration.md         # Vision, rôles et bénéfices de l'IA dans la copropriété
├── 📄 2_architecture_technique.md     # Architecture système, base de données, sécurité et API
├── 📄 3_cas_d_utilisation.md          # Scénarios de prompts système et flux de dialogue
│
├── 📁 rules/                           # 🛡️ RÈGLES ET DIRECTIVES STRICTES
│   └── 📄 system_rules.md              # Sécurité, isolation, validation humaine et limites Laravel
│
├── 📁 skills/                          # 🧠 CATALOGUE DES COMPÉTENCES MODULAIRES
│   └── 📄 agent_skills.md              # Saisie d'incident, rédaction, audit financier, FAQ
│
├── 📁 workflows/                       # 🔄 FLUX DE TRAVAIL & DIAGRAMMES SÉQUENTIELS
│   └── 📄 workflows.md                 # Enchaînement d'actions et schémas Mermaid pour l'Agent
│
├── 📁 resources/                       # 🗄️ RESSOURCES ET CONTEXTE MÉTIER
│   ├── 📄 database_schema.md           # Référence exacte du schéma de base MySQL et Eloquent
│   └── 📄 reglement_copropriete_template.md # Base de connaissances sur le règlement intérieur de l'immeuble
│
└── 📁 prototype/                       # 🖥️ PROTOTYPE INTERACTIF HAUT DE GAMME
    ├── 📄 index.html                  # Interface utilisateur (Chatbot, Rédacteur, Auditeur)
    ├── 📄 style.css                   # Design système premium, glassmorphism et animations
    └── 📄 app.js                      # Simulation d'intelligence agentique et logique interactive
```

---

## 🎯 Objectifs de cette Structure

1. **Zéro Hallucination :** En fournissant la référence exacte du schéma MySQL ([database_schema.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/resources/database_schema.md)) et du règlement ([reglement_copropriete_template.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/resources/reglement_copropriete_template.md)), l'IA n'invente jamais de tables, de champs ou de clauses juridiques.
2. **Sécurité Maximale (Data Isolation) :** Des directives claires de restriction d'accès ([system_rules.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/rules/system_rules.md)) forcent l'application de filtres par utilisateur connecté pour éliminer tout risque de fuite de données financières entre voisins.
3. **Contrôle Humain Intégral :** Les flux d'exécution ([workflows.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/workflows/workflows.md)) imposent une validation humaine systématique (Human-in-the-loop) avant toute opération d'écriture ou de notification de masse.
4. **Indépendance Technologique :** Les compétences répertoriées ([agent_skills.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/skills/agent_skills.md)) sont découplées sous forme d'entrées/sorties standardisées (Function Calling), prêtes à être intégrées avec n'importe quel LLM moderne (*Gemini, Claude, GPT*).

---

## 🚀 Comment l'IA exploite ce Workspace ?

Lorsqu'un Agent d'ingénierie ou de traitement de données travaille sur ce projet, il doit :
1. **Lire les Règles ([rules/system_rules.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/rules/system_rules.md))** pour configurer son environnement de sécurité.
2. **Charger la Ressource Métier ([resources/database_schema.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/resources/database_schema.md))** pour structurer correctement les requêtes Laravel Eloquent et les appels de fonctions.
3. **Appliquer les étapes du Workflow ([workflows/workflows.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/workflows/workflows.md))** correspondant à la demande de l'utilisateur.
4. **Exécuter la Compétence requise ([skills/agent_skills.md](file:///c:/solicode/project/Projet-finale/Agent%20IA/skills/agent_skills.md))** en lui passant les paramètres saisis par le résident ou le syndic.

---
*Ce workspace structuré garantit qu'ImmoSyndic intègre l'IA selon les plus hauts standards d'ingénierie logicielle et de sécurité.*
