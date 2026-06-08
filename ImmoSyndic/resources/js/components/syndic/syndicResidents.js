export default (config = {}) => ({
    search: '',
    immeubleSelectionne: 'all',
    statutSelectionne: 'all',
    openImm: false,
    openStat: false,
    isEditing: false,
    residentEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', immeuble_id: '', numero_appartement: '', date_entree: '', override_mois_retard: '' },

    // Pagination attributes
    items: config.items || [],
    currentPage: 1,
    perPage: 10,

    init() {
        this.$watch('search', () => this.currentPage = 1);
        this.$watch('immeubleSelectionne', () => this.currentPage = 1);
        this.$watch('residentEnCours.immeuble_id', (value) => {
            if (!this.isEditing) {
                this.residentEnCours.numero_appartement = '';
            }
        });
    },

    get filteredItems() {
        return this.items.filter(item => this.matches(item.name, item.immeuble));
    },

    isRowVisible(id, name, immeuble) {
        if (!this.matches(name, immeuble)) return false;
        const index = this.filteredItems.findIndex(item => item.id == id);
        if (index === -1) return false;
        const start = (this.currentPage - 1) * this.perPage;
        const end = this.currentPage * this.perPage;
        return index >= start && index < end;
    },

    initAjout() {
        this.isEditing = false;
        this.residentEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', immeuble_id: '', numero_appartement: '', date_entree: '', override_mois_retard: '' };
        if (window.editor) window.editor.commands.setContent('');
    },

    initEdit(id, prenom, nom, email, telephone, cin, immeuble_id, numero_appt, date_e, notes, override_mois_retard) {
        this.isEditing = true;
        this.residentEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, notes: notes, role: 'resident', immeuble_id: immeuble_id, numero_appartement: numero_appt, date_entree: date_e, override_mois_retard: override_mois_retard };
        if (window.editor) window.editor.commands.setContent(notes || '');
    },

    matches(name, immeuble) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        return matchesSearch && matchesImmeuble;
    }
});