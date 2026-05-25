export default () => ({
    search: '',
    moisSelectionne: 'all',
    statutSelectionne: 'all',
    openMois: false,
    openStat: false,
    
    matches(ref, mois, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = ref.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        const matchStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchSearch && matchMois && matchStatut;
    }
});