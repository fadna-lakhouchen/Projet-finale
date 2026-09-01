/**
 * Composant Alpine.js pour la Gestion Financière du Syndic (paiements.blade.php)
 * Gère le filtrage dynamique des cotisations (mois, immeuble, statut), la pagination locale du tableau,
 * et les états d'ouverture/sélection des dropdowns personnalisés pour l'enregistrement et l'édition de transactions.
 */
export default (config = {}) => ({
    // --- RECHERCHE ET FILTRES PRINCIPAUX ---
    search: '',                     // Terme de recherche saisi (nom du résident ou nom de l'immeuble)
    moisSelectionne: 'all',          // Filtre par mois d'échéance sélectionné
    immeubleSelectionne: 'all',      // Filtre par immeuble sélectionné
    statutSelectionne: 'all',        // Filtre par statut de la charge (payé, partiel, impayé, en retard)
    
    // États d'ouverture des filtres personnalisés (Dropdowns style Preline)
    openMois: false,
    openImm: false,
    openStat: false,

    // --- ATTRIBUTS DE PAGINATION ---
    items: config.items || [],      // Liste brute des cotisations injectée au chargement
    currentPage: 1,                 // Numéro de page actuelle
    perPage: 10,                    // Nombre d'éléments par page

    /**
     * Initialisation du composant
     * Surveille les filtres pour réinitialiser la page courante à 1 en cas de modification.
     */
    init() {
        this.$watch('search', () => this.currentPage = 1);
        this.$watch('moisSelectionne', () => this.currentPage = 1);
        this.$watch('immeubleSelectionne', () => this.currentPage = 1);
        this.$watch('statutSelectionne', () => this.currentPage = 1);
    },

    /**
     * Retourne la liste des éléments filtrés selon les critères saisis
     */
    get filteredItems() {
        return this.items.filter(item => this.matches(item.name, item.mois, item.immeuble, item.statut));
    },

    /**
     * Détermine si une ligne de tableau doit être affichée (visibilité + pagination locale)
     */
    isRowVisible(id, name, mois, immeuble, statut) {
        if (!this.matches(name, mois, immeuble, statut)) return false;
        const index = this.filteredItems.findIndex(item => item.id == id);
        if (index === -1) return false;
        const start = (this.currentPage - 1) * this.perPage;
        const end = this.currentPage * this.perPage;
        return index >= start && index < end;
    },

    // --- FORMULAIRE DE NOUVEAU PAIEMENT ---
    selectedChargeId: '',             // ID de la charge sélectionnée
    selectedChargeLabel: 'Sélectionnez...', // Libellé à afficher pour l'utilisateur
    selectedMontant: '',              // Montant du versement saisi

    // --- GESTION DE LA MODALE DES PAIEMENTS REÇUS ---
    currentCharge: null,              // Cotisation actuellement inspectée pour lister ses versements

    // --- FORMULAIRE DE MODIFICATION D'UN VERSEMENT ---
    editPaiementId: '',
    editChargeId: '',
    editChargeLabel: '',
    editMontant: '',
    editDate: '',
    editStatut: '',
    openEditSelectCharge: false,

    /**
     * Applique les filtres de recherche (nom, mois, immeuble, statut)
     */
    matches(name, mois, immeuble, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = name.toLowerCase().includes(s) || immeuble.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        const matchImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchSearch && matchMois && matchImmeuble && matchStatut;
    },

    /**
     * Formate une date système YYYY-MM-DD au format lisible JJ/MM/AAAA
     */
    formatDate(dateStr) {
        if (!dateStr) return '';
        const datePart = dateStr.split('T')[0];
        const parts = datePart.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateStr;
    },

    /**
     * Déclencher la suppression d'un paiement individuel
     * Met à jour dynamiquement l'action du formulaire masqué de suppression avant soumission.
     */
    deletePaiement(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce paiement ? Le statut de la cotisation sera recalculé.')) {
            const form = document.getElementById('delete-paiement-form');
            if (form) {
                form.action = `/syndic/paiements/${id}`;
                form.submit();
            }
        }
    }
});