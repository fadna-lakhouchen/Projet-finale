export default () => ({
    search: '', 
    filterVille: 'all',
    filterStatut: 'all',
    openVille: false,
    openStatut: false,
    isEditing: false,
    immeubleEnCours: { id: '', nom: '', adresse: '', ville: '', nombre_etages: '', nombre_appartements: '' },
    
    initAjout() {
        this.isEditing = false;
        this.immeubleEnCours = { id: '', nom: '', adresse: '', ville: '', nombre_etages: '', nombre_appartements: '' };
    },
    
    initEdit(id, nom, adresse, ville, nb_etages, nb_app) {
        this.isEditing = true;
        this.immeubleEnCours = { id: id, nom: nom, adresse: adresse, ville: ville, nombre_etages: nb_etages, nombre_appartements: nb_app };
    },
    
    matches(nom, ville, statut) {
        const matchesSearch = nom.toLowerCase().includes(this.search.toLowerCase());
        const matchesVille = this.filterVille === 'all' || ville === this.filterVille;
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        return matchesSearch && matchesVille && matchesStatut;
    }
});