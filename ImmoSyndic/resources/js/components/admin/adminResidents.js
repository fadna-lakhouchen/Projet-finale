export default () => ({
    search: '', 
    immeubleSelectionne: 'all', 
    statutSelectionne: 'all',
    showImm: false,
    showStat: false,
    isEditing: false,
    residentEnCours: { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', type_resident: 'locataire', immeuble_id: '', appartement_id: '', date_entree: '' },
    
    init() {
        this.$watch('residentEnCours.immeuble_id', (value) => {
            if (!this.isEditing) {
                this.residentEnCours.appartement_id = '';
            }
        });
    },
    
    initAjout() {
        this.isEditing = false;
        this.residentEnCours = { id: '', prenom: '', nom: '', email: '', telephone: '', cin: '', notes: '', role: 'resident', type_resident: 'locataire', immeuble_id: '', appartement_id: '', date_entree: '' };
        if (window.editor) window.editor.commands.setContent('');
    },
    
    initEdit(id, prenom, nom, email, telephone, cin, type, immeuble_id, appt_id, date_e, notes) {
        this.isEditing = true;
        this.residentEnCours = { id: id, prenom: prenom, nom: nom, email: email, telephone: telephone, cin: cin, notes: notes, role: 'resident', type_resident: type, immeuble_id: immeuble_id, appartement_id: appt_id, date_entree: date_e };
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