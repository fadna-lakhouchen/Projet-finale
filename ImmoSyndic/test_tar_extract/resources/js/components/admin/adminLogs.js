export default (logsData) => ({
    logs: logsData,
    search: '',
    actionSelectionnee: 'all',
    dateSelectionnee: 'all',
    openAction: false,
    openDate: false,
    showModal: false,
    selectedLog: null,
    
    get filteredLogs() {
        return this.logs.filter(log => {
            const matchesSearch = log.user_name.toLowerCase().includes(this.search.toLowerCase()) ||
                                  log.action.toLowerCase().includes(this.search.toLowerCase()) ||
                                  log.model_type.toLowerCase().includes(this.search.toLowerCase());
                                  
            const matchesAction = this.actionSelectionnee === 'all' || log.action === this.actionSelectionnee;
            
            let matchesDate = true;
            if (this.dateSelectionnee === 'today') {
                const todayStr = new Date().toDateString();
                const logDateStr = new Date(log.created_at_raw).toDateString();
                matchesDate = logDateStr === todayStr;
            } else if (this.dateSelectionnee === 'week') {
                const oneWeekAgo = new Date();
                oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
                const logDate = new Date(log.created_at_raw);
                matchesDate = logDate >= oneWeekAgo;
            }
            
            return matchesSearch && matchesAction && matchesDate;
        });
    },
    
    getFriendlyModel(type) {
        const parts = type.split('\\');
        const name = parts[parts.length - 1];
        const map = {
            'Charge': 'Charge / Cotisation',
            'Paiement': 'Paiement',
            'Incident': 'Signalement Problème',
            'Annonce': 'Annonce',
            'User': 'Compte Utilisateur',
            'Intervention': 'Intervention Technique',
            'Document': 'Document officiel',
            'Depense': 'Dépense / Justificatif'
        };
        return map[name] || name;
    },

    openDetails(log) {
        this.selectedLog = log;
        this.showModal = true;
    }
});
