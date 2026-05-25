export default () => ({
    search: '', 
    filterStatut: 'all', 
    filterCharge: 'all',
    showStat: false,
    showCharge: false,
    isEditing: false,
    syndicEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', ville: '', date_entree: '', date_sortie: '', notes: '', immeubles: [] },
    
    initAjout() {
        this.isEditing = false;
        this.syndicEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', ville: '', date_entree: '', date_sortie: '', notes: '', immeubles: [] };
        if (window.editor) window.editor.commands.setContent('');
    },
    
    initEdit(id, prenom, nom, email, telephone, cin, ville, date_entree, date_sortie, notes, immeubles) {
        this.isEditing = true;
        this.syndicEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, ville: ville, date_entree: date_entree, date_sortie: date_sortie, notes: notes, immeubles: immeubles };
        if (window.editor) window.editor.commands.setContent(notes || '');
    },
    
    matches(name, email, statut, nbImmeubles) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s) || email.toLowerCase().includes(s);
        const matchesStatut = this.filterStatut === 'all' || statut === this.filterStatut;
        let matchesCharge = true;
        
        if (this.filterCharge === 'Sans immeuble') matchesCharge = nbImmeubles === 0;
        else if (this.filterCharge === '1-3 Immeubles') matchesCharge = nbImmeubles >= 1 && nbImmeubles <= 3;
        else if (this.filterCharge === '4+ Immeubles') matchesCharge = nbImmeubles >= 4;
        
        return matchesSearch && matchesStatut && matchesCharge;
    }
});