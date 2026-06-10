/**
 * Composant Alpine.js pour la Gestion des Immeubles par le Syndic (immeubles.blade.php)
 * Gère l'état réactif de recherche locale, les filtres de table (villes, statuts),
 * l'initialisation du formulaire de création et modification (modales),
 * ainsi que le mécanisme d'ajout d'une ville personnalisée.
 */
export default (config = {}) => ({
    // Filtres de recherche principaux
    search: '',
    filterVille: 'all',
    filterStatut: 'all',
    openVille: false,
    openStatut: false,
    
    isEditing: false,                 // Indique si le modal est en mode Édition (true) ou Ajout (false)
    syndicNames: config.syndicNames || {}, // Mappage des IDs de syndic à leurs noms lisibles

    // Modèle de données réactif lié aux champs du formulaire
    immeubleEnCours: { 
        id: '', 
        nom: '', 
        adresse: '', 
        ville: '', 
        syndic_id: '', 
        nombre_etages: '', 
        nombre_appartements: '' 
    },

    // Attributs pour saisir manuellement une nouvelle ville non présente dans la liste existante
    isAddingCustomVille: false,       // Affiche un champ de saisie texte classique au lieu du select si vrai
    customVilleInput: '',             // Valeur de la nouvelle ville saisie

    /**
     * Préparer le formulaire pour la création d'un nouvel immeuble
     * Remet à zéro toutes les variables d'état du modèle.
     */
    initAjout() {
        this.isEditing = false;
        this.immeubleEnCours = { id: '', nom: '', adresse: '', ville: '', syndic_id: '', nombre_etages: '', nombre_appartements: '' };
        this.isAddingCustomVille = false;
        this.customVilleInput = '';
    },

    /**
     * Charger un immeuble existant pour édition dans le formulaire modal
     */
    initEdit(id, nom, adresse, ville, syndic_id, nb_etages, nb_app, toutesLesVilles = []) {
        this.isEditing = true;
        this.immeubleEnCours = {
            id: id,
            nom: nom,
            adresse: adresse,
            ville: ville,
            syndic_id: syndic_id,
            nombre_etages: nb_etages,
            nombre_appartements: nb_app
        };

        // Déterminer si la ville est dans la liste standard ou personnalisée
        if (toutesLesVilles.includes(ville) || ville === '') {
            this.isAddingCustomVille = false;
            this.customVilleInput = '';
        } else {
            // Si la ville n'est pas dans la liste préexistante, on active automatiquement la saisie manuelle
            this.isAddingCustomVille = true;
            this.customVilleInput = ville;
        }
    },

    /**
     * Activer l'affichage du champ de texte pour la saisie d'une ville personnalisée
     */
    enableCustomVille() {
        this.isAddingCustomVille = true;
        this.immeubleEnCours.ville = '';
        this.customVilleInput = '';
    },

    /**
     * Désactiver le champ personnalisé pour revenir au menu déroulant standard des villes
     */
    disableCustomVille() {
        this.isAddingCustomVille = false;
        this.immeubleEnCours.ville = '';
        this.customVilleInput = '';
    },

    /**
     * Applique les critères de filtrage pour afficher ou masquer les lignes d'immeubles
     */
    matches(nom, ville, statut) {
        const matchesSearch = nom.toLowerCase().includes(this.search.toLowerCase());
        const matchesVille = this.filterVille === 'all' || ville === this.filterVille;
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        return matchesSearch && matchesVille && matchesStatut;
    }
});