<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Immeuble;
use App\Models\AbonnementSyndic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyndicBillingHistoryTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $syndic;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin User
        $this->admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@immosyndic.com',
            'password' => bcrypt('password'),
            'role' => 'administrateur',
            'is_active' => true,
        ]);
        $this->admin->email_verified_at = now();
        $this->admin->save();

        // Syndic User
        $this->syndic = User::create([
            'nom' => 'Khadir',
            'prenom' => 'Youssef',
            'email' => 'youssef@syndic.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $this->syndic->email_verified_at = now();
        $this->syndic->save();

        // Create a building for the syndic to calculate billing amount
        Immeuble::create([
            'nom' => 'Résidence Test',
            'adresse' => 'Rue Test',
            'ville' => 'Casablanca',
            'nombre_etages' => 2,
            'nombre_appartements' => 4,
            'syndic_id' => $this->syndic->id,
        ]);
    }

    /**
     * Test that only admin can access syndic abonnements page.
     */
    public function test_only_admin_can_access_billing_history(): void
    {
        // Guests redirect to login
        $response = $this->get(route('admin.syndics.abonnements', $this->syndic->id));
        $response->assertRedirect(route('login'));

        // Syndics get unauthorized (403 or redirect)
        $this->actingAs($this->syndic);
        $response = $this->get(route('admin.syndics.abonnements', $this->syndic->id));
        $response->assertStatus(403);

        // Admins can access successfully
        $this->actingAs($this->admin);
        $response = $this->get(route('admin.syndics.abonnements', $this->syndic->id));
        $response->assertStatus(200);
    }

    /**
     * Test that current month's subscription is generated automatically when admin views history.
     */
    public function test_current_month_billing_generated_automatically(): void
    {
        $this->actingAs($this->admin);

        // Assert database does not have any records
        $this->assertEquals(0, AbonnementSyndic::count());

        $response = $this->get(route('admin.syndics.abonnements', $this->syndic->id));
        $response->assertStatus(200);

        // Verify that a record was generated for the current month/year
        $this->assertEquals(1, AbonnementSyndic::count());
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $this->assertDatabaseHas('abonnements_syndics', [
            'user_id' => $this->syndic->id,
            'mois' => $currentMonth,
            'annee' => $currentYear,
            'montant' => 8.00, // 1 syndic * 8 DH = 8 DH
            'statut' => 'non_payé',
        ]);
    }

    /**
     * Test that admin can update billing record status, notes and date.
     */
    public function test_admin_can_update_billing_status(): void
    {
        $this->actingAs($this->admin);

        // 1. Visit page to generate subscription
        $this->get(route('admin.syndics.abonnements', $this->syndic->id));
        $abonnement = AbonnementSyndic::first();
        $this->assertEquals('non_payé', $abonnement->statut);

        // 2. Post update
        $response = $this->post(route('admin.syndics.abonnements.update', $abonnement->id), [
            'statut' => 'payé',
            'date_paiement' => '2026-06-22',
            'notes' => 'Virement reçu sur compte CIH Bank.',
        ]);

        $response->assertStatus(302);
        
        // 3. Verify database values
        $abonnement = $abonnement->fresh();
        $this->assertEquals('payé', $abonnement->statut);
        $this->assertEquals('2026-06-22 00:00:00', $abonnement->date_paiement->format('Y-m-d H:i:s'));
        $this->assertEquals('Virement reçu sur compte CIH Bank.', $abonnement->notes);
    }
}
