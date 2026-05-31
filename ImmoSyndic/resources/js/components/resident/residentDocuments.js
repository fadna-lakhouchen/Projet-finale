export default () => ({
    search: '',
    categorieSelectionne: 'all',
    openCat: false,

    matches(titre, categorie) {
        const s = this.search.toLowerCase();
        const matchesSearch = titre.toLowerCase().includes(s);
        const matchesCategorie = this.categorieSelectionne === 'all' || categorie === this.categorieSelectionne;
        return matchesSearch && matchesCategorie;
    }
});
