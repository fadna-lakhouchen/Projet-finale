/**
 * Composant Alpine.js pour le Tableau de bord du Syndic
 * Gère l'état réactif et les interactions du formulaire de création de nouveau signalement/incident.
 * Ce composant évite l'utilisation de plugins de select natifs pour privilégier des dropdowns personnalisés
 * stylisés avec Preline CSS et contrôlés par l'état réactif d'Alpine.
 */
export default () => ({
    // Données réactives du formulaire de signalement
    titre: '',
    immeuble_id: '',
    immeuble_nom: 'Sélectionner un immeuble',
    priorite: 'moyenne',
    description: '',
    
    // États d'ouverture des listes déroulantes personnalisées
    openImmeubleDropdown: false,
    openPrioriteDropdown: false,

    /**
     * Sélectionner un immeuble
     * @param {number} id - ID de l'immeuble
     * @param {string} nom - Nom de l'immeuble
     */
    selectImmeuble(id, nom) {
        this.immeuble_id = id;
        this.immeuble_nom = nom;
        this.openImmeubleDropdown = false; // Ferme la dropdown après sélection
    },

    /**
     * Sélectionner un niveau de priorité
     * @param {string} val - Valeur de la priorité (basse, moyenne, haute, urgente)
     */
    selectPriorite(val) {
        this.priorite = val;
        this.openPrioriteDropdown = false; // Ferme la dropdown après sélection
    },

    /**
     * Libellé formaté de la priorité sélectionnée
     */
    get prioriteLabel() {
        const labels = {
            'basse': 'Basse',
            'moyenne': 'Moyenne',
            'haute': 'Haute',
            'urgente': 'Urgente'
        };
        return labels[this.priorite] || 'Sélectionner la priorité';
    },

    /**
     * Réinitialiser le formulaire
     * Remet à zéro toutes les variables d'état après la fermeture du modal ou la soumission réussie.
     */
    resetForm() {
        this.titre = '';
        this.immeuble_id = '';
        this.immeuble_nom = 'Sélectionner un immeuble';
        this.priorite = 'moyenne';
        this.description = '';
        this.openImmeubleDropdown = false;
        this.openPrioriteDropdown = false;
    }
});

