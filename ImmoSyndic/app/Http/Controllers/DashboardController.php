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

class DashboardController extends Controller
{
    protected $paiementService;
    protected $incidentService;

    public function __construct(PaiementService $paiementService, IncidentService $incidentService)
    {
        $this->paiementService = $paiementService;
        $this->incidentService = $incidentService;
    }
    public function index()
    {
        $role = Auth::user()->role;
        return match($role) {
            'administrateur' => redirect()->route('admin.dashboard'),
            'syndic' => redirect()->route('syndic.dashboard'),
            'resident' => redirect()->route('resident.dashboard'),
            default => abort(403),
        };
    }

    public function adminDashboard()
    {
        $now = now();

        $stats = [
            'total_residents'          => User::where('role', 'resident')->count(),
            'total_immeubles'          => Immeuble::count(),
            'incidents_ouverts'        => Incident::whereNotIn('statut', ['Résolu', 'résolu'])->count(),
            'paiements_retard'         => Paiement::where('statut', 'en retard')->count(),
            'total_paiements_attendus' => Paiement::count(),
            'logs_today'               => AuditLog::whereDate('created_at', today())->count(),
            'logs_last_hour'           => AuditLog::where('created_at', '>=', $now->copy()->subHour())->count(),
        ];

        // Activité par jour — 7 derniers jours (pour le graphique barres)
        $activityByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $activityByDay[] = [
                'label' => $date->format('D'),
                'date'  => $date->toDateString(),
                'count' => AuditLog::whereDate('created_at', $date->toDateString())->count(),
            ];
        }

        // Top 5 actions les plus fréquentes
        $topActions = AuditLog::selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Top 5 utilisateurs les plus actifs
        $topUsers = AuditLog::with('user')
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $recentActivity = AuditLog::with('user')->latest()->take(10)->get();

        return view('admin.administrateur.dashboard', compact(
            'stats', 'recentActivity', 'activityByDay', 'topActions', 'topUsers'
        ));
    }

    public function adminImmeubles()
    {
        $immeubles = Immeuble::with('syndic')->get();
        $syndics = User::where('role', 'syndic')->get();
        return view('admin.administrateur.immeubles', compact('immeubles', 'syndics'));
    }

    public function adminResidents()
    {
        $residents = User::where('role', 'resident')->with('appartements.immeuble')->get();
        $immeubles = Immeuble::all();
        return view('admin.administrateur.residents', compact('residents', 'immeubles'));
    }

    public function adminSyndics()
    {
        $syndics = User::where('role', 'syndic')->get();
        $immeubles = \App\Models\Immeuble::all();
        return view('admin.administrateur.syndics', compact('syndics', 'immeubles'));
    }

    public function adminPaiements()
    {
        $paiements = Paiement::with(['charge.appartement.immeuble', 'user'])->latest()->get();
        
        // Calculer les stats dans le Controller (Clean Architecture)
        $stats = [
            'totalCollecte' => $paiements->where('statut', 'validé')->sum('montant'),
            'totalAttente' => $paiements->where('statut', 'en attente')->sum('montant'),
            'nbPaiements' => $paiements->count(),
        ];

        $immeubles = Immeuble::all();
        return view('admin.administrateur.paiements', compact('paiements', 'immeubles', 'stats'));
    }

    public function adminSignalements()
    {
        $incidents = Incident::with(['immeuble', 'user'])->latest()->get();

        $stats = [
            'ouverts'   => $incidents->whereIn('statut', ['Ouvert', 'ouvert', 'nouveau', 'Nouveau', 'à traiter'])->count(),
            'en_cours'  => $incidents->whereIn('statut', ['En cours', 'en cours'])->count(),
            'resolus'   => $incidents->whereIn('statut', ['Résolu', 'résolu', 'Terminé', 'terminé'])->count(),
        ];

        return view('admin.administrateur.signalements', compact('incidents', 'stats'));
    }

    public function syndicDashboard()
    {
        $user = Auth::user();
        $immeubles = Immeuble::where('syndic_id', $user->id)->with('appartements')->get();
        $immeubleIds = $immeubles->pluck('id');
        
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

        // 1. Get the latest open incident for the banner alert
        $urgentIncident = Incident::whereIn('immeuble_id', $immeubleIds)
            ->where('statut', '!=', 'Résolu')
            ->with(['immeuble', 'user'])
            ->latest()
            ->first();

        // 2. Fetch unified recent activities (Paiements + Incidents)
        $activites = collect();

        // Fetch recent payments
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

        // Fetch recent incidents
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

        // Sort by date latest and take 5
        $activites = $activites->sortByDesc('date')->take(5)->values();

        $demandesCollecte = \App\Models\Notification::where('user_id', $user->id)
            ->where('type', 'ready_to_pay')
            ->where('lu', false)
            ->latest()
            ->get();

        return view('admin.syndic.dashboard', compact('stats', 'immeubles', 'urgentIncident', 'activites', 'demandesCollecte'));
    }

    public function syndicResidents()
    {
        $user = Auth::user();
        $immeubles = Immeuble::where('syndic_id', $user->id)->get();
        $immeubleIds = $immeubles->pluck('id');
        
        $residents = User::where('role', 'resident')
            ->whereHas('appartements', function($q) use ($immeubleIds) {
                $q->whereIn('immeuble_id', $immeubleIds);
            })->with('appartements.immeuble')->get();
            
        return view('admin.syndic.residents', compact('residents', 'immeubles'));
    }

    public function syndicImmeubles()
    {
        $user = Auth::user();
        $immeubles = Immeuble::where('syndic_id', $user->id)->with('appartements')->get();
        $villes = $immeubles->pluck('ville')->unique()->filter()->values();
        $syndics = User::where('role', 'syndic')->get();
        return view('admin.syndic.immeubles', compact('immeubles', 'villes', 'syndics'));
    }

    public function syndicPaiements()
    {
        $user = Auth::user();
        $immeubleIds = Immeuble::where('syndic_id', $user->id)->pluck('id');
        
        // Fetch all validated and pending payments for the stats
        $allPaiements = Paiement::whereHas('charge.appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->get();

        $stats = [
            'totalCollecte' => $allPaiements->where('statut', 'validé')->sum('montant'),
            'totalAttente' => $allPaiements->where('statut', 'en attente')->sum('montant'),
            'nbPaiements' => $allPaiements->count(),
        ];
        
        $immeubles = Immeuble::whereIn('id', $immeubleIds)->get();

        // Fetch all charges (including paid, partial, unpaid) for the main dashboard list
        $chargesList = \App\Models\Charge::whereHas('appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['appartement.immeuble', 'appartement.residents', 'paiements'])
          ->latest()
          ->get();

        // Available months from all generated charges
        $moisDisponibles = $chargesList->map(function($c) {
            return ucfirst(\Carbon\Carbon::parse($c->date_echeance)->translatedFormat('F Y'));
        })->unique()->values();

        // Unpaid or partially paid charges for the "Saisir un paiement" select dropdown
        $charges = $chargesList->filter(function($c) {
            return strtolower($c->statut) !== 'payé';
        })->values();

        return view('admin.syndic.paiements', compact('immeubles', 'stats', 'moisDisponibles', 'charges', 'chargesList'));
    }

    public function syndicInterventions()
    {
        $user = Auth::user();
        $immeubleIds = Immeuble::where('syndic_id', $user->id)->pluck('id');
        
        $incidents = Incident::whereIn('immeuble_id', $immeubleIds)
            ->with(['immeuble', 'user'])
            ->latest()
            ->get();
            
        $immeubles = Immeuble::whereIn('id', $immeubleIds)->get();

        return view('admin.syndic.interventions', compact('incidents', 'immeubles'));
    }

    public function syndicParametres()
    {
        $user = Auth::user();
        return view('admin.syndic.parametres', compact('user'));
    }

    public function residentDashboard()
    {
        $user = Auth::user();

        if (!$user->is_active) {
            return view('admin.resident.waiting-approval', compact('user'));
        }

        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;

        // Utilisation du Service pour les statistiques
        $stats = $this->paiementService->getResidentStats($user);
        
        // Ajout du nombre d'incidents ouverts
        $stats['incidents_ouverts'] = Incident::where('user_id', $user->id)
            ->whereNotIn('statut', ['résolu', 'Résolu'])
            ->count();

        // Récupération des activités via les services
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
        
        // Fetch building transparency: Calculate cumulative status for all apartments
        $appartementsEnRetard = collect();
        $appartementsEnRegle = collect();
        
        if ($immeuble) {
            $appartements = \App\Models\Appartement::where('immeuble_id', $immeuble->id)
                ->with(['residents', 'charges.paiements'])
                ->get();
                
            foreach ($appartements as $apt) {
                // Filter charges that are not fully paid
                $unpaidCharges = $apt->charges->filter(function($c) {
                    return strtolower($c->statut) !== 'payé';
                });
                
                $isMyApt = $apt->residents->contains('id', $user->id);
                
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
            
            // Sort by apartment number numerically
            $appartementsEnRetard = $appartementsEnRetard->sortBy(function($apt) {
                return (int)$apt['numero'];
            })->values();
            
            $appartementsEnRegle = $appartementsEnRegle->sortBy(function($apt) {
                return (int)$apt['numero'];
            })->values();
        }
        
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

    public function residentPaiements()
    {
        $user = Auth::user();
        $paiements = $this->paiementService->getUserPaiements($user);
        
        $moisDisponibles = $paiements->map(function($p) {
            return ucfirst(\Carbon\Carbon::parse($p->date_paiement)->translatedFormat('F Y'));
        })->unique()->values();

        return view('admin.resident.paiements', compact('paiements', 'moisDisponibles'));
    }

    public function residentIncidents()
    {
        $user = Auth::user();
        $incidents = $this->incidentService->getAllUserIncidents($user);
        return view('admin.resident.incidents', compact('incidents'));
    }

    public function syndicAnnonces()
    {
        $user = Auth::user();
        $immeubles = Immeuble::where('syndic_id', $user->id)->get();
        $immeubleIds = $immeubles->pluck('id');
        
        $annonces = Annonce::whereIn('immeuble_id', $immeubleIds)
            ->with(['immeuble', 'syndic'])
            ->latest()
            ->get();
            
        return view('admin.syndic.annonces', compact('annonces', 'immeubles'));
    }

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

    public function adminDocuments()
    {
        $documents = \App\Models\Document::with('immeuble')->latest()->get();
        $immeubles = Immeuble::all();
        
        // Calculate storage size dynamically from public storage folder
        $totalSizeBytes = 0;
        foreach ($documents as $doc) {
            if ($doc->fichier_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->fichier_path)) {
                $totalSizeBytes += \Illuminate\Support\Facades\Storage::disk('public')->size($doc->fichier_path);
            }
        }
        
        // Convert to MB
        $totalSizeMb = round($totalSizeBytes / (1024 * 1024), 2);
        // Let's set a maximum virtual quota of 100 MB for demonstration
        $maxSizeMb = 100; 
        $percentage = $maxSizeMb > 0 ? min(round(($totalSizeMb / $maxSizeMb) * 100, 1), 100) : 0;
        
        $storageInfo = [
            'used' => $totalSizeMb . ' MB',
            'max' => $maxSizeMb . ' MB',
            'percentage' => $percentage
        ];

        return view('admin.administrateur.documents', compact('documents', 'immeubles', 'storageInfo'));
    }

    public function syndicDocuments()
    {
        $user = Auth::user();
        $immeubles = Immeuble::where('syndic_id', $user->id)->get();
        $immeubleIds = $immeubles->pluck('id');

        $documents = \App\Models\Document::whereIn('immeuble_id', $immeubleIds)
            ->with('immeuble')
            ->latest()
            ->get();

        return view('admin.syndic.documents', compact('documents', 'immeubles'));
    }

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

    public function adminDepenses()
    {
        $depenses = \App\Models\Depense::with('immeuble')->latest()->get();
        $immeubles = Immeuble::all();
        return view('admin.administrateur.depenses', compact('depenses', 'immeubles'));
    }

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


    public function residentParametres()
    {
        $user = Auth::user();
        return view('admin.resident.parametres', compact('user'));
    }

    public function adminLogs()
    {
        $logs = \App\Models\AuditLog::with('user')->latest()->get();
        return view('admin.administrateur.logs', compact('logs'));
    }

    public function markNotificationsAsRead()
    {
        \App\Models\Notification::where('user_id', auth()->id())->update(['lu' => true]);
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function signalReadyToPay(Request $request)
    {
        $request->validate([
            'charge_id' => 'required|exists:charges,id',
            'note' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $charge = \App\Models\Charge::with('appartement.immeuble.syndic')->findOrFail($request->charge_id);
        $appt = $user->appartements()->first();

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

    public function markSingleNotificationAsRead($id)
    {
        $notif = \App\Models\Notification::where('user_id', auth()->id())->findOrFail($id);
        $notif->update(['lu' => true]);
        return back()->with('success', 'Notification marquée comme lue.');
    }
}

