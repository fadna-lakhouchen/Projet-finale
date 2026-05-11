# Charte Graphique - ImmoSyndic

Cette charte graphique définit l'identité visuelle de la plateforme ImmoSyndic, garantissant une cohérence sur l'ensemble des interfaces Web et Mobile.

## 1. Couleurs (Palette Sémantique)

L'application utilise un thème basé sur le bleu pour inspirer confiance et professionnalisme (domaine immobilier/syndic).

### Couleurs Principales (Brand)
- **Primary** : `bg-blue-600` (#2563eb) - Actions principales, boutons, liens actifs.
- **Primary Hover** : `hover:bg-blue-700` (#1d4ed8) - Interaction au survol.
- **Primary Light** : `bg-blue-50` (#eff6ff) - Fonds d'éléments sélectionnés ou alertes info.

### Couleurs Neutres (Grayscale)
- **Background App** : `bg-gray-50` (#f9fafb) - Fond général de l'application.
- **Background Surface** : `bg-white` (#ffffff) - Cartes, modales, zones de contenu.
- **Border** : `border-gray-200` (#e5e7eb) - Séparateurs, bordures de champs.
- **Text Primary** : `text-gray-900` (#111827) - Titres et texte principal lu.
- **Text Secondary** : `text-gray-500` (#6b7280) - Sous-titres, placeholders, labels.

### Couleurs Sémantiques (Feedback)
- **Success (Succès / Payé)** : `text-green-700 bg-green-50` (Badge) / `bg-green-600` (Bouton).
- **Danger (Erreur / Impayé / Urgent)** : `text-red-700 bg-red-50` (Badge) / `bg-red-600` (Bouton).
- **Warning (En attente / Retard)** : `text-yellow-700 bg-yellow-50` (Badge) / `bg-yellow-600` (Bouton).
- **Info** : Mêmes teintes que `Primary`.

## 2. Typographie

Nous utilisons l'utilitaire classique de Tailwind CSS basé sur **Inter** (ou la typographie sans-serif par défaut du système).

- **Font Family** : `font-sans` (Inter, Roboto, Helvetica...)
- **Titres (Headings)** :
  - `H1` : `text-3xl font-bold text-gray-900 tracking-tight`
  - `H2` : `text-2xl font-semibold text-gray-900`
  - `H3` : `text-xl font-medium text-gray-900`
- **Corps de texte (Body)** :
  - `Base` : `text-base text-gray-600`
  - `Small` : `text-sm text-gray-500`
  - `Tiny` : `text-xs text-gray-400`

## 3. Espacements & Layout (Spacing)

L'échelle d'espacement de Tailwind est utilisée telle quelle.
- **Micro** : `gap-2` ou `p-2` (8px)
- **Small** : `gap-4` ou `p-4` (16px) - Padding standard des composants (cartes, boutons).
- **Medium** : `gap-6` ou `p-6` (24px) - Padding des sections principales.
- **Large** : `gap-8` ou `p-8` (32px) - Espace entre les gros blocs.

### Rayons de bordure (Border Radius)
Pour un design moderne et doux :
- **Boutons & Inputs** : `rounded-md` (6px) ou `rounded-lg` (8px).
- **Cartes & Modales** : `rounded-xl` (12px) pour les surfaces importantes.
- **Avatars & Badges circulaires** : `rounded-full`.

### Ombres (Shadows)
- **Cartes standard** : `shadow-sm` pour un léger relief.
- **Éléments interactifs survolés / Dropdowns** : `shadow-md` ou `shadow-lg`.
- **Modales** : `shadow-xl`.

---
*Document généré pour accompagner l'intégration UI.*
