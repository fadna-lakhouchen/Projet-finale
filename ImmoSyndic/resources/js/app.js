import './bootstrap';
import 'preline';

import Alpine from 'alpinejs';

// Admin components
import adminMenu from './components/admin/adminMenu.js';
import adminSyndics from './components/admin/adminSyndics.js';
import adminResidents from './components/admin/adminResidents.js';
import adminPaiements from './components/admin/adminPaiements.js';
import adminImmeubles from './components/admin/adminImmeubles.js';
import adminDocuments from './components/admin/adminDocuments.js';
import adminDepenses from './components/admin/adminDepenses.js';

// Syndic components
import syndicPanel from './components/syndic/syndicPanel.js';
import syndicResidents from './components/syndic/syndicResidents.js';
import syndicPaiements from './components/syndic/syndicPaiements.js';
import syndicInterventions from './components/syndic/syndicInterventions.js';
import syndicImmeubles from './components/syndic/syndicImmeubles.js';
import syndicAnnonces from './components/syndic/syndicAnnonces.js';
import syndicDocuments from './components/syndic/syndicDocuments.js';
import syndicDepenses from './components/syndic/syndicDepenses.js';

// Resident components
import residentPanel from './components/resident/residentPanel.js';
import residentPaiements from './components/resident/residentPaiements.js';
import residentDashboard from './components/resident/residentDashboard.js';
import residentIncidents from './components/resident/residentIncidents.js';
import residentAnnonces from './components/resident/residentAnnonces.js';
import residentDocuments from './components/resident/residentDocuments.js';
import residentDepenses from './components/resident/residentDepenses.js';

window.Alpine = Alpine;

// Register Admin components f Alpine
Alpine.data('adminMenu', adminMenu);
Alpine.data('adminSyndics', adminSyndics);
Alpine.data('adminResidents', adminResidents);
Alpine.data('adminPaiements', adminPaiements);
Alpine.data('adminImmeubles', adminImmeubles);
Alpine.data('adminDocuments', adminDocuments);
Alpine.data('adminDepenses', adminDepenses);

// Register Syndic components f Alpine
Alpine.data('syndicPanel', syndicPanel);
Alpine.data('syndicResidents', syndicResidents);
Alpine.data('syndicPaiements', syndicPaiements);
Alpine.data('syndicInterventions', syndicInterventions);
Alpine.data('syndicImmeubles', syndicImmeubles);
Alpine.data('syndicAnnonces', syndicAnnonces);
Alpine.data('syndicDocuments', syndicDocuments);
Alpine.data('syndicDepenses', syndicDepenses);

// Register Resident components f Alpine
Alpine.data('residentPanel', residentPanel);
Alpine.data('residentPaiements', residentPaiements);
Alpine.data('residentDashboard', residentDashboard);
Alpine.data('residentIncidents', residentIncidents);
Alpine.data('residentAnnonces', residentAnnonces);
Alpine.data('residentDocuments', residentDocuments);
Alpine.data('residentDepenses', residentDepenses);

Alpine.start();
