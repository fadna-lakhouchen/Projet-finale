export default () => ({
    search: '',
    moisSelectionne: 'all',
    immeubleSelectionne: 'all',
    statutSelectionne: 'all',
    openMois: false,
    openImm: false,
    openStat: false,
    
    matches(name, mois, immeuble, statut) {
        const s = this.search.toLowerCase();
        const matchSearch = name.toLowerCase().includes(s) || immeuble.toLowerCase().includes(s);
        const matchMois = this.moisSelectionne === 'all' || mois === this.moisSelectionne;
        const matchImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchSearch && matchMois && matchImmeuble && matchStatut;
    }
});