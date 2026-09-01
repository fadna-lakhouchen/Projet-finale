export default () => ({
    search: '',
    immeubleSelectionne: 'all',
    categorieSelectionne: 'all',
    openImm: false,
    openCat: false,
    openFormImm: false,
    openFormCat: false,
    documentEnCours: { id: '', titre: '', categorie: '', immeuble_id: '' },

    initAjout() {
        this.documentEnCours = { id: '', titre: '', categorie: '', immeuble_id: '' };
    },

    matches(titre, immeuble, categorie) {
        const s = this.search.toLowerCase();
        const matchesSearch = titre.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchesCategorie = this.categorieSelectionne === 'all' || categorie === this.categorieSelectionne;
        return matchesSearch && matchesImmeuble && matchesCategorie;
    }
});
