export default () => ({
    search: '', 
    immeubleSelectionne: 'all', 
    statutSelectionne: 'all',
    openImm: false,
    openStat: false,
    isEditing: false,
    residentEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', type_resident: 'locataire', immeuble_id: '', numero_appartement: '', date_entree: '' },
    
    init() {
        this.$watch('residentEnCours.immeuble_id', (value) => {
            if (!this.isEditing) {
                this.residentEnCours.numero_appartement = '';
            }
        });
    },
    
    initAjout() {
        this.isEditing = false;
        this.residentEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', type_resident: 'locataire', immeuble_id: '', numero_appartement: '', date_entree: '' };
        if (window.editor) window.editor.commands.setContent('');
    },
    
    initEdit(id, prenom, nom, email, telephone, cin, type, immeuble_id, numero_appt, date_e, notes) {
        this.isEditing = true;
        this.residentEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, notes: notes, role: 'resident', type_resident: type, immeuble_id: immeuble_id, numero_appartement: numero_appt, date_entree: date_e };
        if (window.editor) window.editor.commands.setContent(notes || '');
    },
    
    matches(name, immeuble, role) {
        const s = this.search.toLowerCase();
        const matchesSearch = name.toLowerCase().includes(s);
        const matchesImmeuble = this.immeubleSelectionne === 'all' || immeuble === this.immeubleSelectionne;
        const matchesStatut = this.statutSelectionne === 'all' || role === this.statutSelectionne;
        return matchesSearch && matchesImmeuble && matchesStatut;
    }
});