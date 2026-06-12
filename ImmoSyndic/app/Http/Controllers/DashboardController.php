<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Immeuble;
use App\Models\User;
use App\Models\Paiement;
use App\Models\Incident;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use App\Services\PaiementService;
use App\Services\IncidentService;
use App\Models\Annonce;

/**
 * Contrôleur DashboardController
 * Centralise la logique d'affichage des tableaux de bord (Dashboards) et des espaces dédiés
 * pour chaque rôle utilisateur : Administrateur global, Syndic (principal et secondaire), et Résident.
 */
class DashboardController extends Controller
{
    // Services injectés pour encapsuler la logique métier complexe (Clean Architecture)
    protected $paiementService;
    protected $incidentService;

    /**
     * Constructeur du contrôleur
     * Injecte les services nécessaires pour la gestion des paiements et des incidents.
     */
    public function __construct(PaiementService $paiementService, IncidentService $incidentService)
    {
        $this->paiementService = $paiementService;
        $this->incidentService = $incidentService;
    }

    /**
     * Point d'entrée principal (/)
     * Redirige l'utilisateur connecté vers son tableau de bord spécifique en fonction de son rôle.
     */
    public function index()
    {
        $role = Auth::user()->role;
        return match($role) {
            'administrateur' => redirect()->route('admin.dashboard'),
            'syndic' => redirect()->route('syndic.dashboard'),
            'resident' => redirect()->route('resident.dashboard'),
            default => abort(403), // Interdit si aucun rôle valide
        };
    }

    /**
     * Tableau de bord de l'Administrateur
     * Affiche des statistiques globales de la plateforme, l'activité récente (Audit Logs),
     * les graphiques d'activité et les utilisateurs les plus actifs.
     */
    public function adminDashboard()
    {
        $now = now();

        // Récupération des statistiques globales pour les cartes informatives
        $stats = [
            'total_residents'          => User::where('role', 'resident')->count(),
            'total_immeubles'          => Immeuble::count(),
            'incidents_ouverts'        => Incident::whereNotIn('statut', ['Résolu', 'résolu'])->count(),
            'paiements_retard'         => Paiement::where('statut', 'en retard')->count(),
            'total_paiements_attendus' => Paiement::count(),
            'logs_today'               => AuditLog::whereDate('created_at', today())->count(),
            'logs_last_hour'           => AuditLog::where('created_at', '>=', $now->copy()->subHour())->count(),
        ];

        // Construction des données d'activité sur les 7 derniers jours (pour le graphique à barres)
        $activityByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $activityByDay[] = [
                'label' => $date->format('D'), // Abréviation du jour en anglais
                'date'  => $date->toDateString(),
                'count' => AuditLog::whereDate('created_at', $date->toDateString())->count(),
            ];
        }

        // Top 5 des actions les plus fréquentes dans le journal d'audit
        $topActions = AuditLog::selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Top 5 des utilisateurs les plus actifs (générant le plus de logs d'audit)
        $topUsers = AuditLog::with('user')
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Récupération des 10 dernières lignes de log d'activité globale
        $recentActivity = AuditLog::with('user')->latest()->take(10)->get();

        return view('admin.administrateur.dashboard', compact(
            'stats', 'recentActivity', 'activityByDay', 'topActions', 'topUsers'
        ));
    }

    /**
     * Espace Administrateur : Gestion des Immeubles
     * Liste tous les immeubles avec leurs syndics associés et permet d'affecter un syndic.
     */
    public function adminImmeubles()
    {
        $immeubles = Immeuble::with('syndic')->get();
        $syndics = User::where('role', 'syndic')->get();
        return view('admin.administrateur.immeubles', compact('immeubles', 'syndics'));
    }

    /**
     * Espace Administrateur : Gestion des Résidents
     * Liste tous les résidents avec leurs appartements et immeubles correspondants.
     */
    public function adminResidents()
    {
        $residents = User::where('role', 'resident')->with('appartements.immeuble')->get();
        $immeubles = Immeuble::all();
        return view('admin.administrateur.residents', compact('residents', 'immeubles'));
    }

    /**
     * Espace Administrateur : Gestion des Syndics
     * Liste tous les comptes de syndics créés sur la plateforme.
     */
    public function adminSyndics()
    {
        $syndics = User::where('role', 'syndic')->get();
        $immeubles = \App\Models\Immeuble::all();
        return view('admin.administrateur.syndics', compact('syndics', 'immeubles'));
    }

    /**
     * Espace Administrateur : Vue d'ensemble des Paiements
     * Permet à l'admin de surveiller tous les paiements et d'en voir les indicateurs clés.
     */
    public function adminPaiements()
    {
        $paiements = Paiement::with(['charge.appartement.immeuble', 'user'])->latest()->get();
        
        // Calcul des totaux clés
        $stats = [
            'totalCollecte' => $paiements->where('statut', 'validé')->sum('montant'),
            'totalAttente' => $paiements->where('statut', 'en attente')->sum('montant'),
            'nbPaiements' => $paiements->count(),
        ];

        $immeubles = Immeuble::all();
        return view('admin.administrateur.paiements', compact('paiements', 'immeubles', 'stats'));
    }

    /**
     * Espace Administrateur : Suivi des Incidents / Signalements
     * Liste l'ensemble des pannes ou problèmes signalés dans tous les immeubles.
     */
    public function adminSignalements()
    {
        $incidents = Incident::with(['immeuble', 'user'])->latest()->get();

        // Classification par statut
        $stats = [
            'ouverts'   => $incidents->whereIn('statut', ['Ouvert', 'ouvert', 'nouveau', 'Nouveau', 'à traiter'])->count(),
            'en_cours'  => $incidents->whereIn('statut', ['En cours', 'en cours'])->count(),
            'resolus'   => $incidents->whereIn('statut', ['Résolu', 'résolu', 'Terminé', 'terminé'])->count(),
        ];

        return view('admin.administrateur.signalements', compact('incidents', 'stats'));
    }

    /**
     * Tableau de bord du Syndic (Principal ou Secondaire)
     * Calcule et affiche les statistiques spécifiques aux immeubles gérés par ce syndic.
     * Fournit un flux d'activité unifié (derniers paiements + nouveaux incidents).
     */
    public function syndicDashboard()
    {
        $user = Auth::user();
        // Récupération des immeubles gérés par ce syndic (via relation managedImmeubles)
        $immeubles = $user->managedImmeubles()->with('appartements')->get();
        $immeubleIds = $immeubles->pluck('id');
        
        // Statistiques condensées de son parc immobilier
        $stats = [
            'total_residents' => User::whereHas('appartements', function($q) use ($immeubleIds) {
                $q->whereIn('immeuble_id', $immeubleIds);
            })->count(),
            'total_appartements' => $immeubles->sum(fn($i) => $i->appartements->count()),
            'incidents_ouverts' => Incident::whereIn('immeuble_id', $immeubleIds)->whereNotIn('statut', ['Résolu', 'résolu'])->count(),
            'paiements_ce_mois' => Paiement::whereHas('charge.appartement', function($q) use ($immeubleIds) {
                $q->whereIn('immeuble_id', $immeubleIds);
            })->whereMonth('date_paiement', now()->month)->sum('montant'),
        ];

        // Récupération du dernier incident urgent non résolu pour l'alerte haute visibilité
        $urgentIncident = Incident::whereIn('immeuble_id', $immeubleIds)
            ->where('statut', '!=', 'Résolu')
            ->with(['immeuble', 'user'])
            ->latest()
            ->first();

        // Récupération et fusion des dernières activités (Paiements + Signalements)
        $activites = collect();

        // Récupération des 5 derniers paiements
        $paiements = Paiement::whereHas('charge.appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['charge.appartement.immeuble', 'user'])->latest()->take(5)->get();

        foreach ($paiements as $p) {
            $appartement = $p->charge->appartement ?? null;
            $immeubleNom = $appartement && $appartement->immeuble ? $appartement->immeuble->nom : 'N/A';
            $apptNum = $appartement ? "Appt " . $appartement->numero : '';
            $userName = $p->user ? $p->user->name : 'Résident';
            
            $activites->push([
                'type' => 'Paiement',
                'evenement' => 'Paiement reçu',
                'concerne' => "$immeubleNom - $apptNum",
                'details' => number_format($p->montant, 2) . " DH (Par " . $userName . ")",
                'date' => $p->created_at,
                'icon' => 'check',
                'color' => 'emerald',
                'bg_row' => ''
            ]);
        }

        // Récupération des 5 derniers incidents
        $incidents = Incident::whereIn('immeuble_id', $immeubleIds)
            ->with(['immeuble', 'user'])
            ->latest()
            ->take(5)
            ->get();

        foreach ($incidents as $i) {
            $immeubleNom = $i->immeuble ? $i->immeuble->nom : 'N/A';
            $userName = $i->user ? $i->user->name : 'Syndic (Admin)';
            
            $activites->push([
                'type' => 'Signalement',
                'evenement' => $i->cree_par_admin ? 'Signalement (Admin)' : 'Signalement Résident',
                'concerne' => $i->appartement_id ? "$immeubleNom - Appt " . ($i->appartement->numero ?? '') : $immeubleNom,
                'details' => $i->titre . " (Par " . $userName . ")",
                'date' => $i->created_at,
                'icon' => $i->cree_par_admin ? 'alert-triangle' : 'info',
                'color' => $i->cree_par_admin ? 'rose' : 'primary',
                'bg_row' => $i->cree_par_admin ? 'bg-rose-50/[0.02]' : ''
            ]);
        }

        // Tri décroissant pour avoir le flux d'activité le plus récent en premier
        $activites = $activites->sortByDesc('date')->take(5)->values();

        // Récupération des demandes de règlement en espèces émises par les résidents
        $demandesCollecte = \App\Models\Notification::where('user_id', $user->id)
            ->where('type', 'ready_to_pay')
            ->where('lu', false)
            ->latest()
            ->get();

        return view('admin.syndic.dashboard', compact('stats', 'immeubles', 'urgentIncident', 'activites', 'demandesCollecte'));
    }

    /**
     * Espace Syndic : Gestion des Résidents
     * Liste uniquement les résidents habitant dans les immeubles du syndic connecté.
     */
    public function syndicResidents()
    {
        $user = Auth::user();
        $immeubles = $user->managedImmeubles()->get();
        $immeubleIds = $immeubles->pluck('id');
        
        $residents = User::where('role', 'resident')
            ->whereHas('appartements', function($q) use ($immeubleIds) {
                $q->whereIn('immeuble_id', $immeubleIds);
            })->with('appartements.immeuble')->get();
            
        return view('admin.syndic.residents', compact('residents', 'immeubles'));
    }

    /**
     * Espace Syndic : Gestion des Immeubles
     * Affiche les immeubles gérés par ce syndic avec leurs appartements.
     */
    public function syndicImmeubles()
    {
        $user = Auth::user();
        $immeubles = $user->managedImmeubles()->with('appartements')->get();
        $villes = $immeubles->pluck('ville')->unique()->filter()->values();
        $syndics = User::where('role', 'syndic')->get();
        return view('admin.syndic.immeubles', compact('immeubles', 'villes', 'syndics'));
    }

    /**
     * Espace Syndic : Gestion financière (Cotisations & Paiements)
     * Affiche les indicateurs de collecte financière et liste l'ensemble des cotisations mensuelles générées.
     */
    public function syndicPaiements()
    {
        $user = Auth::user();
        $immeubleIds = $user->managedImmeubles()->pluck('id');
        
        // Récupération de tous les paiements liés aux immeubles du syndic pour les stats financières
        $allPaiements = Paiement::whereHas('charge.appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->get();

        $stats = [
            'totalCollecte' => $allPaiements->where('statut', 'validé')->sum('montant'),
            'totalAttente' => $allPaiements->where('statut', 'en attente')->sum('montant'),
            'nbPaiements' => $allPaiements->count(),
        ];
        
        $immeubles = Immeuble::whereIn('id', $immeubleIds)->get();

        // Récupération de l'ensemble des cotisations mensuelles (charges) avec leurs paiements
        $chargesList = \App\Models\Charge::whereHas('appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['appartement.immeuble', 'appartement.residents', 'paiements'])
          ->latest()
          ->get();

        // Extraction des mois disponibles (pour le filtre de recherche dans la vue)
        $moisDisponibles = $chargesList->map(function($c) {
            return ucfirst(\Carbon\Carbon::parse($c->date_echeance)->translatedFormat('F Y'));
        })->unique()->values();

        return view('admin.syndic.paiements', compact('immeubles', 'stats', 'moisDisponibles', 'chargesList'));
    }

    /**
     * Espace Syndic : Gestion des Interventions & Signalements
     * Permet au syndic de suivre l'état de traitement des pannes déclarées.
     */
    public function syndicInterventions()
    {
        $user = Auth::user();
        $immeubleIds = $user->managedImmeubles()->pluck('id');
        
        $incidents = Incident::whereIn('immeuble_id', $immeubleIds)
            ->with(['immeuble', 'user'])
            ->latest()
            ->get();
            
        $immeubles = Immeuble::whereIn('id', $immeubleIds)->get();

        return view('admin.syndic.interventions', compact('incidents', 'immeubles'));
    }

    /**
     * Espace Syndic : Paramètres & Facturation de l'abonnement
     * Calcule le montant total dû pour la gestion de ses immeubles (calcul dynamique).
     */
    public function syndicParametres()
    {
        $user = Auth::user();
        $subscription = $user->calculateTotalSubscription();
        return view('admin.syndic.parametres', compact('user', 'subscription'));
    }

    /**
     * Tableau de bord du Résident
     * Gère également l'affichage de la page d'attente si le résident n'est pas encore approuvé par le syndic.
     * Inclut le système de transparence financière de l'immeuble.
     */
    public function residentDashboard()
    {
        $user = Auth::user();

        // Si le résident n'a pas encore été activé/approuvé par le syndic, on le bloque sur un écran d'attente
        if (!$user->is_active) {
            return view('admin.resident.waiting-approval', compact('user'));
        }

        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;

        // Appel au PaiementService pour obtenir les stats personnelles (solde, payé, en attente)
        $stats = $this->paiementService->getResidentStats($user);
        
        // Ajout du nombre d'incidents déclarés par ce résident et en cours de résolution
        $stats['incidents_ouverts'] = Incident::where('user_id', $user->id)
            ->whereNotIn('statut', ['résolu', 'Résolu'])
            ->count();

        // Récupération de l'historique d'activité personnelle (5 derniers paiements et incidents)
        $activites = collect();
        
        $mesPaiements = $this->paiementService->getUserPaiements($user)->take(5);
        foreach ($mesPaiements as $p) {
            $activites->push([
                'date' => $p->date_paiement,
                'type' => 'Paiement',
                'description' => "Règlement de charge REF-" . str_pad($p->id, 6, '0', STR_PAD_LEFT),
                'statut' => $p->statut,
                'color' => in_array(strtolower($p->statut), ['payé', 'validé']) ? 'green' : 'red'
            ]);
        }

        $mesIncidents = $this->incidentService->getUserIncidents($user, 5);
        foreach ($mesIncidents as $i) {
            $statusLower = strtolower($i->statut);
            $activites->push([
                'date' => $i->created_at,
                'type' => 'Signalement',
                'description' => $i->titre,
                'statut' => $i->statut,
                'color' => in_array($statusLower, ['nouveau', 'ouvert', 'à traiter']) ? 'blue' : (in_array($statusLower, ['en cours']) ? 'orange' : 'green')
            ]);
        }
        $activites = $activites->sortByDesc('date')->take(5);
        
        // Transparence de l'immeuble : classification des appartements en règle ou en retard de paiement
        $appartementsEnRetard = collect();
        $appartementsEnRegle = collect();
        
        if ($immeuble) {
            $appartements = \App\Models\Appartement::where('immeuble_id', $immeuble->id)
                ->with(['residents', 'charges.paiements'])
                ->get();
                
            foreach ($appartements as $apt) {
                // Détermination des charges non réglées
                $unpaidCharges = $apt->charges->filter(function($c) {
                    return strtolower($c->statut) !== 'payé';
                });
                
                $isMyApt = $apt->residents->contains('id', $user->id);
                
                // Prise en compte de la surcharge manuelle du nombre de mois de retard si configurée
                $actualUnpaidCount = $unpaidCharges->count();
                $displayUnpaidCount = is_null($apt->override_mois_retard) ? $actualUnpaidCount : (int)$apt->override_mois_retard;
                
                if ($displayUnpaidCount > 0 && $actualUnpaidCount > 0) {
                    $totalOwed = $unpaidCharges->sum(function($c) {
                        return $c->reste_a_payer;
                    });
                    
                    $appartementsEnRetard->push([
                        'id' => $apt->id,
                        'numero' => $apt->numero,
                        'unpaid_count' => $displayUnpaidCount,
                        'total_owed' => $totalOwed,
                        'is_my_apt' => $isMyApt,
                    ]);
                } else {
                    $appartementsEnRegle->push([
                        'id' => $apt->id,
                        'numero' => $apt->numero,
                        'is_my_apt' => $isMyApt,
                    ]);
                }
            }
            
            // Tri numérique par numéro d'appartement pour un affichage propre
            $appartementsEnRetard = $appartementsEnRetard->sortBy(function($apt) {
                return (int)$apt['numero'];
            })->values();
            
            $appartementsEnRegle = $appartementsEnRegle->sortBy(function($apt) {
                return (int)$apt['numero'];
            })->values();
        }
        
        // Liste de mes charges personnelles en attente de paiement
        $mesChargesImpayees = collect();
        if ($appartement) {
            $mesChargesImpayees = $appartement->charges()->where('statut', '!=', 'payé')->get();
        }

        return view('admin.resident.dashboard', compact(
            'user', 
            'appartement', 
            'immeuble', 
            'stats', 
            'activites', 
            'appartementsEnRetard', 
            'appartementsEnRegle',
            'mesChargesImpayees'
        ));
    }

    /**
     * Espace Résident : Historique des paiements personnels
     */
    public function residentPaiements()
    {
        $user = Auth::user();
        $paiements = $this->paiementService->getUserPaiements($user);
        
        $moisDisponibles = $paiements->map(function($p) {
            return ucfirst(\Carbon\Carbon::parse($p->date_paiement)->translatedFormat('F Y'));
        })->unique()->values();

        return view('admin.resident.paiements', compact('paiements', 'moisDisponibles'));
    }

    /**
     * Espace Résident : Historique de ses signalements d'incident
     */
    public function residentIncidents()
    {
        $user = Auth::user();
        $incidents = $this->incidentService->getAllUserIncidents($user);
        return view('admin.resident.incidents', compact('incidents'));
    }

    /**
     * Espace Syndic : Liste des annonces diffusées dans ses immeubles
     */
    public function syndicAnnonces()
    {
        $user = Auth::user();
        $immeubles = $user->managedImmeubles()->get();
        $immeubleIds = $immeubles->pluck('id');
        
        $annonces = Annonce::whereIn('immeuble_id', $immeubleIds)
            ->with(['immeuble', 'syndic'])
            ->latest()
            ->get();
            
        return view('admin.syndic.annonces', compact('annonces', 'immeubles'));
    }

    /**
     * Espace Résident : Liste des annonces publiées par le syndic dans son immeuble
     */
    public function residentAnnonces()
    {
        $user = Auth::user();
        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;
        
        $annonces = collect();
        if ($immeuble) {
            $annonces = Annonce::where('immeuble_id', $immeuble->id)
                ->with('syndic')
                ->latest()
                ->get();
        }
        
        return view('admin.resident.annonces', compact('annonces', 'immeuble'));
    }

    /**
     * Espace Administrateur : Gestion des Documents partagés
     * Calcule également l'espace de stockage occupé dynamiquement en Mo.
     */
    public function adminDocuments()
    {
        $documents = \App\Models\Document::with('immeuble')->latest()->get();
        $immeubles = Immeuble::all();
        
        // Calcul dynamique de l'espace disque consommé par les fichiers d'immeubles
        $totalSizeBytes = 0;
        foreach ($documents as $doc) {
            if ($doc->fichier_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->fichier_path)) {
                $totalSizeBytes += \Illuminate\Support\Facades\Storage::disk('public')->size($doc->fichier_path);
            }
        }
        
        $totalSizeMb = round($totalSizeBytes / (1024 * 1024), 2);
        $maxSizeMb = 100; // Quota virtuel de 100 Mo pour démo
        $percentage = $maxSizeMb > 0 ? min(round(($totalSizeMb / $maxSizeMb) * 100, 1), 100) : 0;
        
        $storageInfo = [
            'used' => $totalSizeMb . ' MB',
            'max' => $maxSizeMb . ' MB',
            'percentage' => $percentage
        ];

        return view('admin.administrateur.documents', compact('documents', 'immeubles', 'storageInfo'));
    }

    /**
     * Espace Syndic : Documents de ses immeubles
     */
    public function syndicDocuments()
    {
        $user = Auth::user();
        $immeubles = $user->managedImmeubles()->get();
        $immeubleIds = $immeubles->pluck('id');

        $documents = \App\Models\Document::whereIn('immeuble_id', $immeubleIds)
            ->with('immeuble')
            ->latest()
            ->get();

        return view('admin.syndic.documents', compact('documents', 'immeubles'));
    }

    /**
     * Espace Résident : Documents mis à disposition dans son immeuble
     */
    public function residentDocuments()
    {
        $user = Auth::user();
        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;

        $documents = collect();
        if ($immeuble) {
            $documents = \App\Models\Document::where('immeuble_id', $immeuble->id)
                ->with('immeuble')
                ->latest()
                ->get();
        }

        return view('admin.resident.documents', compact('documents', 'immeuble'));
    }

    /**
     * Espace Administrateur : Suivi des dépenses globales
     */
    public function adminDepenses()
    {
        $depenses = \App\Models\Depense::with('immeuble')->latest()->get();
        $immeubles = Immeuble::all();
        return view('admin.administrateur.depenses', compact('depenses', 'immeubles'));
    }

    /**
     * Espace Syndic : Suivi des charges de fonctionnement de ses immeubles (Dépenses)
     */
    public function syndicDepenses()
    {
        $user = Auth::user();
        $immeubles = Immeuble::where('syndic_id', $user->id)->get();
        $immeubleIds = $immeubles->pluck('id');

        $depenses = \App\Models\Depense::whereIn('immeuble_id', $immeubleIds)
            ->with('immeuble')
            ->latest()
            ->get();

        return view('admin.syndic.depenses', compact('depenses', 'immeubles'));
    }

    /**
     * Espace Résident : Paramètres personnels
     */
    public function residentParametres()
    {
        $user = Auth::user();
        return view('admin.resident.parametres', compact('user'));
    }

    /**
     * Espace Administrateur : Consultation des journaux d'audit (Logs de sécurité)
     */
    public function adminLogs()
    {
        $logs = \App\Models\AuditLog::with('user')->latest()->get();
        return view('admin.administrateur.logs', compact('logs'));
    }

    /**
     * Tout marquer comme lu (Notifications de l'utilisateur connecté)
     */
    public function markNotificationsAsRead()
    {
        \App\Models\Notification::where('user_id', auth()->id())->update(['lu' => true]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Signaler une cotisation prête (Résident -> Syndic)
     * Permet au résident d'informer son syndic qu'il a préparé le montant en espèces.
     */
    public function signalReadyToPay(Request $request)
    {
        $request->validate([
            'charge_id' => 'required|exists:charges,id',
            'note' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $charge = \App\Models\Charge::with('appartement.immeuble.syndic')->findOrFail($request->charge_id);
        $appt = $user->appartements()->first();

        // Sécurité : Vérifier que cette charge appartient bien à l'appartement du résident
        if (!$appt || $appt->id !== $charge->appartement_id) {
            abort(403, 'Accès non autorisé.');
        }

        $syndic = $appt->immeuble->syndic;

        if ($syndic) {
            $dateFr = ucfirst(\Carbon\Carbon::parse($charge->date_echeance)->translatedFormat('F Y'));
            $montant = number_format($charge->reste_a_payer, 2);
            $message = "Le résident {$user->prenom} {$user->nom} (Appt {$appt->numero}) est prêt à régler sa cotisation de {$dateFr} ({$montant} MAD) en espèces.";
            if ($request->note) {
                $message .= " Note: " . $request->note;
            }
            
            // Création d'une notification pour le syndic principal concerné
            \App\Models\Notification::create([
                'user_id' => $syndic->id,
                'titre' => '💸 Cotisation prête (Espèces)',
                'message' => $message,
                'type' => 'ready_to_pay',
                'lu' => false,
                'date_envoi' => now(),
            ]);

            return back()->with('success', 'Notification envoyée au Syndic. Il passera récupérer le paiement.');
        }

        return back()->with('error', 'Aucun Syndic n\'est assigné à votre immeuble.');
    }

    /**
     * Marquer une notification spécifique comme lue
     */
    public function markSingleNotificationAsRead($id)
    {
        $notif = \App\Models\Notification::where('user_id', auth()->id())->findOrFail($id);
        $notif->update(['lu' => true]);
        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Espace Syndic : Gestion des syndics secondaires associés à ses immeubles
     */
    public function syndicSecondarySyndics()
    {
        $user = Auth::user();
        
        // Immeubles dont cet utilisateur est le syndic principal
        $immeubles = Immeuble::where('syndic_id', $user->id)->get();
        $immeubleIds = $immeubles->pluck('id');

        // Récupération de tous les syndics secondaires associés à ces immeubles
        $secondarySyndics = User::where('role', 'syndic')
            ->whereHas('secondaryImmeubles', function($q) use ($immeubleIds) {
                $q->whereIn('immeubles.id', $immeubleIds);
            })
            ->with(['secondaryImmeubles' => function($q) use ($immeubleIds) {
                $q->whereIn('immeubles.id', $immeubleIds);
            }])
            ->get();

        return view('admin.syndic.secondary-syndics', compact('secondarySyndics', 'immeubles'));
    }

    /**
     * Espace Syndic : Historique d'activité de ses syndics secondaires (Audit Logs)
     */
    public function syndicLogs()
    {
        $user = Auth::user();

        // Récupération des immeubles dont il est le syndic principal
        $immeubleIds = Immeuble::where('syndic_id', $user->id)->pluck('id');

        // Récupération des IDs de tous les syndics secondaires liés à ces immeubles
        $secondarySyndicIds = User::where('role', 'syndic')
            ->whereHas('secondaryImmeubles', function($q) use ($immeubleIds) {
                $q->whereIn('immeubles.id', $immeubleIds);
            })->pluck('id');

        // Affichage des logs d'actions menées par ces syndics secondaires sur son périmètre
        $logs = AuditLog::whereIn('user_id', $secondarySyndicIds)
            ->with('user')
            ->latest()
            ->get();

        return view('admin.syndic.logs', compact('logs'));
    }
}


