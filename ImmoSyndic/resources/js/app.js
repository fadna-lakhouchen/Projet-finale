import './bootstrap';
import 'preline';

import Alpine from 'alpinejs';

// Admin components
import adminMenu from './components/admin/adminMenu.js';
import adminSyndics from './components/admin/adminSyndics.js';
import adminResidents from './components/admin/adminResidents.js';
import adminPaiements from './components/admin/adminPaiements.js';
import adminImmeubles from './components/admin/adminImmeubles.js';

// Syndic components
import syndicPanel from './components/syndic/syndicPanel.js';
import syndicResidents from './components/syndic/syndicResidents.js';
import syndicPaiements from './components/syndic/syndicPaiements.js';
import syndicInterventions from './components/syndic/syndicInterventions.js';
import syndicImmeubles from './components/syndic/syndicImmeubles.js';

// Resident components
import residentPanel from './components/resident/residentPanel.js';
import residentPaiements from './components/resident/residentPaiements.js';
import residentDashboard from './components/resident/residentDashboard.js';
import residentIncidents from './components/resident/residentIncidents.js';

window.Alpine = Alpine;

// Register Admin components f Alpine
Alpine.data('adminMenu', adminMenu);
Alpine.data('adminSyndics', adminSyndics);
Alpine.data('adminResidents', adminResidents);
Alpine.data('adminPaiements', adminPaiements);
Alpine.data('adminImmeubles', adminImmeubles);

// Register Syndic components f Alpine
Alpine.data('syndicPanel', syndicPanel);
Alpine.data('syndicResidents', syndicResidents);
Alpine.data('syndicPaiements', syndicPaiements);
Alpine.data('syndicInterventions', syndicInterventions);
Alpine.data('syndicImmeubles', syndicImmeubles);

// Register Resident components f Alpine
Alpine.data('residentPanel', residentPanel);
Alpine.data('residentPaiements', residentPaiements);
Alpine.data('residentDashboard', residentDashboard);
Alpine.data('residentIncidents', residentIncidents);

Alpine.start();
