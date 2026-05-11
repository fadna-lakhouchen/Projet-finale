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
        $stats = [
            'total_residents'          => User::where('role', 'resident')->count(),
            'total_immeubles'          => Immeuble::count(),
            'incidents_ouverts'        => Incident::where('statut', '!=', 'Résolu')->count(),
            'paiements_retard'         => Paiement::where('statut', 'en retard')->count(),
            'total_paiements_attendus' => Paiement::count(),
        ];
        $recentActivity = AuditLog::with('user')->latest()->take(5)->get();
        return view('admin.administrateur.dashboard', compact('stats', 'recentActivity'));
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
        return view('admin.administrateur.syndics', compact('syndics'));
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
            'incidents_ouverts' => Incident::whereIn('immeuble_id', $immeubleIds)->where('statut', '!=', 'Résolu')->count(),
            'paiements_ce_mois' => Paiement::whereHas('charge.appartement', function($q) use ($immeubleIds) {
                $q->whereIn('immeuble_id', $immeubleIds);
            })->whereMonth('date_paiement', now()->month)->sum('montant'),
        ];

        return view('admin.syndic.dashboard', compact('stats', 'immeubles'));
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
        return view('admin.syndic.immeubles', compact('immeubles', 'villes'));
    }

    public function syndicPaiements()
    {
        $user = Auth::user();
        $immeubleIds = Immeuble::where('syndic_id', $user->id)->pluck('id');
        
        $paiements = Paiement::whereHas('charge.appartement', function($q) use ($immeubleIds) {
            $q->whereIn('immeuble_id', $immeubleIds);
        })->with(['charge.appartement.immeuble', 'user'])->latest()->get();

        $stats = [
            'totalCollecte' => $paiements->where('statut', 'validé')->sum('montant'),
            'totalAttente' => $paiements->where('statut', 'en attente')->sum('montant'),
            'nbPaiements' => $paiements->count(),
        ];
        
        $immeubles = Immeuble::whereIn('id', $immeubleIds)->get();

        $moisDisponibles = $paiements->map(function($p) {
            return ucfirst(\Carbon\Carbon::parse($p->date_paiement)->translatedFormat('F Y'));
        })->unique()->values();

        return view('admin.syndic.paiements', compact('paiements', 'immeubles', 'stats', 'moisDisponibles'));
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
        $appartement = $user->appartements()->first();
        $immeuble = $appartement ? $appartement->immeuble : null;

        // Utilisation du Service pour les statistiques
        $stats = $this->paiementService->getResidentStats($user);
        
        // Ajout du nombre d'incidents ouverts
        $stats['incidents_ouverts'] = Incident::where('user_id', $user->id)
            ->where('statut', '!=', 'résolu')
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
                'color' => $p->statut === 'Payé' ? 'green' : 'red'
            ]);
        }

        $mesIncidents = $this->incidentService->getUserIncidents($user, 5);
        foreach ($mesIncidents as $i) {
            $activites->push([
                'date' => $i->created_at,
                'type' => 'Signalement',
                'description' => $i->titre,
                'statut' => $i->statut,
                'color' => $i->statut === 'nouveau' ? 'blue' : ($i->statut === 'en cours' ? 'orange' : 'green')
            ]);
        }
        $activites = $activites->sortByDesc('date')->take(5);
        
        return view('admin.resident.dashboard', compact('user', 'appartement', 'immeuble', 'stats', 'activites'));
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
}
