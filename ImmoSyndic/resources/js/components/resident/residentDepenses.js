export default () => ({
    search: '',

    matches(titre) {
        const s = this.search.toLowerCase();
        return titre.toLowerCase().includes(s);
    }
});
