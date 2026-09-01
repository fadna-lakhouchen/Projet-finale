export default () => ({ 
    search: '', 
    filterSyndic: 'all', 
    filterStatut: 'all',
    showSyndic: false,
    showStatut: false,
    isEditing: false,
    immeubleEnCours: { id: '', nom: '', adresse: '', ville: '', syndic_id: '', nb_etages: '', nb_appartements: '' },
    initAjout() {
        this.isEditing = false;
        this.immeubleEnCours = { id: '', nom: '', adresse: '', ville: '', syndic_id: '', nb_etages: '', nb_appartements: '' };
    },
    initEdit(id, nom, adresse, ville, syndic_id, etages, appts) {
        this.isEditing = true;
        this.immeubleEnCours = { id: id, nom: nom, adresse: adresse, ville: ville, syndic_id: syndic_id, nb_etages: etages, nb_appartements: appts };
    },
    matches(name, address, syndic, statut) {
        const matchesSearch = name.toLowerCase().includes(this.search.toLowerCase()) || address.toLowerCase().includes(this.search.toLowerCase());
        const matchesSyndic = this.filterSyndic === 'all' || syndic === this.filterSyndic;
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        return matchesSearch && matchesSyndic && matchesStatut;
    }
});