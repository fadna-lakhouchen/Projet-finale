export default () => ({
    search: '', 
    immeubleSelectionne: 'all', 
    statutSelectionne: 'all',
    showImm: false,
    showStat: false,
    
    matches(resident, immeuble, statut) {
        const s = this.search.toLowerCase();
        const matchesSearch = resident.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchesStatut = this.statutSelectionne === 'all' || statut === this.statutSelectionne;
        return matchesSearch && matchesImmeuble && matchesStatut;
    }
});