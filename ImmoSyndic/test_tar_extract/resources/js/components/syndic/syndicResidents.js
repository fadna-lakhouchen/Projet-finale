/**
 * Composant Alpine.js pour la Gestion des Résidents (residents.blade.php)
 * Gère le filtrage dynamique de la liste des résidents, la pagination locale du tableau,
 * et le chargement/initialisation des données de formulaire réactif pour la création ou l'édition.
 * Il intègre également la synchronisation avec l'éditeur de texte enrichi TipTap/Editor pour le champ "notes".
 */
export default (config = {}) => ({
    // Filtres de recherche principaux
    search: '',
    immeubleSelectionne: 'all',
    statutSelectionne: 'all',
    openImm: false,
    openStat: false,
    
    isEditing: false,                 // Indique si la modale est en mode Modification (true) ou Ajout (false)
    
    // Modèle de données réactif lié aux champs de formulaire
    residentEnCours: { 
        id: '', 
        prenom: '', 
        nom: '', 
        email: '', 
        telephone: '', 
        cin: '', 
        notes: '', 
        role: 'resident', 
        immeuble_id: '', 
        numero_appartement: '', 
        date_entree: '', 
        override_mois_retard: '',
        cotisation_mensuelle: ''
    },

    // --- ATTRIBUTS DE PAGINATION ---
    items: config.items || [],      // Liste brute des résidents
    currentPage: 1,                 // Numéro de page actuel
    perPage: 10,                    // Nombre de résidents affichés par page

    /**
     * Initialisation du composant
     * Surveille les filtres de recherche pour réinitialiser la pagination.
     */
    init() {
        this.$watch('search', () => this.currentPage = 1);
        this.$watch('immeubleSelectionne', () => this.currentPage = 1);
        // Si l'immeuble change en mode création, on réinitialise le numéro d'appartement
        this.$watch('residentEnCours.immeuble_id', (value) => {
            if (!this.isEditing) {
                this.residentEnCours.numero_appartement = '';
            }
        });
    },

    /**
     * Retourne la liste des éléments filtrés selon les critères de recherche
     */
    get filteredItems() {
        return this.items.filter(item => this.matches(item.name, item.immeuble));
    },

    /**
     * Détermine si une ligne de tableau doit être affichée (visibilité + pagination locale)
     */
    isRowVisible(id, name, immeuble) {
        if (!this.matches(name, immeuble)) return false;
        const index = this.filteredItems.findIndex(item => item.id == id);
        if (index === -1) return false;
        const start = (this.currentPage - 1) * this.perPage;
        const end = this.currentPage * this.perPage;
        return index >= start && index < end;
    },

    /**
     * Préparer le formulaire pour la création d'un nouveau résident
     * Remet à zéro toutes les variables d'état et vide l'éditeur de texte enrichi TipTap.
     */
    initAjout() {
        this.isEditing = false;
        this.residentEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', immeuble_id: '', numero_appartement: '', date_entree: '', override_mois_retard: '', cotisation_mensuelle: '' };
        if (window.editor) window.editor.commands.setContent('');
    },

    /**
     * Charger les données d'un résident existant pour édition dans le formulaire
     * Initialise les champs et injecte le contenu HTML de la note dans l'éditeur TipTap.
     */
    initEdit(id, prenom, nom, email, telephone, cin, immeuble_id, numero_appt, date_e, notes, override_mois_retard, cotisation_mensuelle) {
        this.isEditing = true;
        this.residentEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, notes: notes, role: 'resident', immeuble_id: immeuble_id, numero_appartement: numero_appt, date_entree: date_e, override_mois_retard: override_mois_retard, cotisation_mensuelle: cotisation_mensuelle };
        if (window.editor) window.editor.commands.setContent(notes || '');
    },

    /**
     * Applique les critères de filtrage textuels et par immeuble
     */
    matches(name, immeuble) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        return matchesSearch && matchesImmeuble;
    }
});