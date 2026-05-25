export default () => ({
    statutSelectionne: 'all',
    isEditing: false,
    interventionEnCours: { id: '', titre: '', description: '', immeuble_id: '', statut: '' },
    
    initAjout() {
        this.isEditing = false;
        this.interventionEnCours = { id: '', titre: '', description: '', immeuble_id: '', statut: '' };
    },
    
    initEdit(id, titre, desc, imm_id, stat) {
        this.isEditing = true;
        this.interventionEnCours = { id: id, titre: titre, description: desc, immeuble_id: imm_id, statut: stat };
    },
    
    matches(statut) {
        if (this.statutSelectionne === 'all') return true;
        if (this.statutSelectionne === 'à traiter' && statut === 'Ouvert') return true;
        if (this.statutSelectionne === 'en cours' && statut === 'En cours') return true;
        if (this.statutSelectionne === 'terminé' && statut === 'Résolu') return true;
        return false;
    }
});