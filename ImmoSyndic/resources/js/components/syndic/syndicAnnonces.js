export default () => ({
    search: '', 
    immeubleSelectionne: 'all', 
    openImm: false,
    isEditing: false,
    annonceEnCours: { id: '', titre: '', contenu: '', immeuble_id: '' },
    
    initAjout() {
        this.isEditing = false;
        this.annonceEnCours = { id: '', titre: '', contenu: '', immeuble_id: '' };
    },
    
    initEdit(id, titre, contenu, immeuble_id) {
        this.isEditing = true;
        this.annonceEnCours = { id: id, titre: titre, contenu: contenu, immeuble_id: immeuble_id };
    },
    
    matches(titre, immeuble) {
        const s = this.search.toLowerCase();
        const matchesSearch = titre.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        return matchesSearch && matchesImmeuble;
    }
});
