export default (config = {}) => ({
    search: '',
    filterVille: 'all',
    filterStatut: 'all',
    openVille: false,
    openStatut: false,
    isEditing: false,
    syndicNames: config.syndicNames || {},
    immeubleEnCours: { id: '', nom: '', adresse: '', ville: '', syndic_id: '', nombre_etages: '', nombre_appartements: '' },

    // État du dropdown Ville dans le formulaire modal
    isAddingCustomVille: false,
    customVilleInput: '',

    initAjout() {
        this.isEditing = false;
        this.immeubleEnCours = { id: '', nom: '', adresse: '', ville: '', syndic_id: '', nombre_etages: '', nombre_appartements: '' };
        this.isAddingCustomVille = false;
        this.customVilleInput = '';
    },

    initEdit(id, nom, adresse, ville, syndic_id, nb_etages, nb_app, toutesLesVilles = []) {
        this.isEditing = true;
        this.immeubleEnCours = {
            id: id,
            nom: nom,
            adresse: adresse,
            ville: ville,
            syndic_id: syndic_id,
            nombre_etages: nb_etages,
            nombre_appartements: nb_app
        };

        // Déterminer si la ville est dans la liste standard ou personnalisée
        if (toutesLesVilles.includes(ville) || ville === '') {
            this.isAddingCustomVille = false;
            this.customVilleInput = '';
        } else {
            this.isAddingCustomVille = true;
            this.customVilleInput = ville;
        }
    },

    enableCustomVille() {
        this.isAddingCustomVille = true;
        this.immeubleEnCours.ville = '';
        this.customVilleInput = '';
    },

    disableCustomVille() {
        this.isAddingCustomVille = false;
        this.immeubleEnCours.ville = '';
        this.customVilleInput = '';
    },

    matches(nom, ville, statut) {
        const matchesSearch = nom.toLowerCase().includes(this.search.toLowerCase());
        const matchesVille = this.filterVille === 'all' || ville === this.filterVille;
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        return matchesSearch && matchesVille && matchesStatut;
    }
});