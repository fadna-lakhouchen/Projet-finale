export default () => ({
    search: '',
    immeubleSelectionne: 'all',
    openImm: false,
    openFormImm: false,
    depenseEnCours: { id: '', titre: '', description: '', montant: '', date_depense: '', immeuble_id: '' },

    initAjout() {
        this.depenseEnCours = { id: '', titre: '', description: '', montant: '', date_depense: '', immeuble_id: '' };
    },

    matches(titre, immeuble) {
        const s = this.search.toLowerCase();
        const matchesSearch = titre.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        return matchesSearch && matchesImmeuble;
    }
});
