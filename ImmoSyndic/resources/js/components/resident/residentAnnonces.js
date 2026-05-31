export default () => ({
    search: '',
    
    matches(titre, contenu) {
        const s = this.search.toLowerCase();
        return titre.toLowerCase().includes(s) || contenu.toLowerCase().includes(s);
    }
});
