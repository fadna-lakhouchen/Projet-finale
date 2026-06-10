export default () => ({
    statutSelectionne: 'all',
    isEditing: false,
    interventionEnCours: { id: '', titre: '', description: '', immeuble_id: '', statut: 'Ouvert' },

    initAjout() {
        this.isEditing = false;
        this.interventionEnCours = { id: '', titre: '', description: '', immeuble_id: '', statut: 'Ouvert' };
    },

    initEdit(id, titre, desc, imm_id, stat) {
        this.isEditing = true;
        this.interventionEnCours = { id: id, titre: titre, description: desc, immeuble_id: imm_id, statut: stat };
    },

    matches(statut) {
        if (this.statutSelectionne === 'all') return true;
        const norm = statut ? statut.toLowerCase() : '';
        if (this.statutSelectionne === 'à traiter' && (norm === 'ouvert' || norm === 'nouveau')) return true;
        if (this.statutSelectionne === 'en cours' && norm === 'en cours') return true;
        if (this.statutSelectionne === 'terminé' && (norm === 'résolu' || norm === 'résolue' || norm === 'terminé')) return true;
        return false;
    }
});