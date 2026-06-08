export default (config = {}) => ({
    // Recherche et filtres principaux
    search: '',
    moisSelectionne: 'all',
    immeubleSelectionne: 'all',
    statutSelectionne: 'all',
    openMois: false,
    openImm: false,
    openStat: false,

    // Pagination attributes
    items: config.items || [],
    currentPage: 1,
    perPage: 10,

    init() {
        this.$watch('search', () => this.currentPage = 1);
        this.$watch('moisSelectionne', () => this.currentPage = 1);
        this.$watch('immeubleSelectionne', () => this.currentPage = 1);
        this.$watch('statutSelectionne', () => this.currentPage = 1);
    },

    get filteredItems() {
        return this.items.filter(item => this.matches(item.name, item.mois, item.immeuble, item.statut));
    },

    isRowVisible(id, name, mois, immeuble, statut) {
        if (!this.matches(name, mois, immeuble, statut)) return false;
        const index = this.filteredItems.findIndex(item => item.id == id);
        if (index === -1) return false;
        const start = (this.currentPage - 1) * this.perPage;
        const end = this.currentPage * this.perPage;
        return index >= start && index < end;
    },

    // Saisie d'un nouveau paiement
    selectedChargeId: '',
    selectedChargeLabel: 'Sélectionnez...',
    selectedMontant: '',
    openSelectCharge: false,

    // Gestion de la modale des versements d'une cotisation spécifique
    currentCharge: null,

    // Modification d'un versement spécifique
    editPaiementId: '',
    editChargeId: '',
    editChargeLabel: '',
    editMontant: '',
    editDate: '',
    editStatut: '',
    openEditSelectCharge: false,

    // Fonction de filtrage des cotisations dans le tableau principal
    matches(name, mois, immeuble, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = name.toLowerCase().includes(s) || immeuble.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        const matchImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchSearch && matchMois && matchImmeuble && matchStatut;
    },

    // Formater la date au format standard JJ/MM/AAAA pour l'affichage jury
    formatDate(dateStr) {
        if (!dateStr) return '';
        const datePart = dateStr.split('T')[0];
        const parts = datePart.split('-');
        if (parts.length === 3) {
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateStr;
    },

    // Déclencher la suppression d'un paiement après confirmation
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