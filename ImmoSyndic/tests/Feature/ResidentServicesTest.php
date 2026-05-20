<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Immeuble;
use App\Models\Appartement;
use App\Models\Charge;
use App\Models\Paiement;
use App\Models\Incident;
use App\Services\PaiementService;
use App\Services\IncidentService;

class ResidentServicesTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $appartement;
    private $charge1;
    private $charge2;
    private $paiementService;
    private $incidentService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create resident user
        $this->user = User::create([
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'resident',
        ]);

        // Create building
        $immeuble = Immeuble::create([
            'nom' => 'Résidence Test',
            'adresse' => '123 Rue de Test',
            'ville' => 'Casablanca',
            'nombre_etages' => 4,
            'nombre_appartements' => 16,
        ]);

        // Create apartment
        $this->appartement = Appartement::create([
            'immeuble_id' => $immeuble->id,
            'numero' => '101',
            'etage' => 1,
            'superficie' => 85.5,
            'type' => 'F3',
            'statut' => 'occupé',
        ]);

        // Associate user to apartment
        $this->user->appartements()->attach($this->appartement->id, [
            'type_resident' => 'Locataire',
            'date_entree' => now(),
        ]);

        // Create charges
        $this->charge1 = Charge::create([
            'appartement_id' => $this->appartement->id,
            'titre' => 'Charges Janvier 2026',
            'montant' => 500.00,
            'date_echeance' => '2026-01-31',
            'statut' => 'non payé',
        ]);

        $this->charge2 = Charge::create([
            'appartement_id' => $this->appartement->id,
            'titre' => 'Charges Février 2026',
            'montant' => 500.00,
            'date_echeance' => '2026-02-28',
            'statut' => 'payé',
        ]);

        // Create services
        $this->paiementService = new PaiementService(new Paiement());
        $this->incidentService = new IncidentService(new Incident());
    }

    public function test_get_resident_stats_calculates_correct_totals(): void
    {
        // Total unpaid charges is charge1 = 500.00
        // Let's create a payment for charge2
        Paiement::create([
            'charge_id' => $this->charge2->id,
            'user_id' => $this->user->id,
            'montant' => 500.00,
            'date_paiement' => now()->format('Y-m-d'),
            'mode_paiement' => 'Carte',
            'statut' => 'validé',
        ]);

        $stats = $this->paiementService->getResidentStats($this->user);

        $this->assertArrayHasKey('a_payer_mois', $stats);
        $this->assertArrayHasKey('total_paye_annee', $stats);
        $this->assertEquals(500.00, $stats['a_payer_mois']); // charge1 is unpaid
        $this->assertEquals(500.00, $stats['total_paye_annee']); // charge2 is paid in 2026
    }

    public function test_get_user_paiements_returns_only_user_paiements(): void
    {
        $paiement = Paiement::create([
            'charge_id' => $this->charge2->id,
            'user_id' => $this->user->id,
            'montant' => 500.00,
            'date_paiement' => now()->format('Y-m-d'),
            'mode_paiement' => 'Carte',
            'statut' => 'validé',
        ]);

        $paiements = $this->paiementService->getUserPaiements($this->user);

        $this->assertCount(1, $paiements);
        $this->assertEquals($paiement->id, $paiements->first()->id);
        $this->assertEquals('Résidence Test', $paiements->first()->charge->appartement->immeuble->nom);
    }

    public function test_get_user_incidents_respects_limit_and_order(): void
    {
        // Create 3 incidents
        for ($i = 1; $i <= 3; $i++) {
            Incident::create([
                'user_id' => $this->user->id,
                'immeuble_id' => $this->appartement->immeuble_id,
                'titre' => "Incident $i",
                'description' => "Description $i",
                'priorite' => 'moyenne',
                'statut' => 'nouveau',
            ]);
        }

        $incidentsLimit2 = $this->incidentService->getUserIncidents($this->user, 2);
        $this->assertCount(2, $incidentsLimit2);

        $allIncidents = $this->incidentService->getAllUserIncidents($this->user);
        $this->assertCount(3, $allIncidents);
    }
}
