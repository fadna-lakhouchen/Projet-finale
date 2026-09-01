<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Immeuble;
use App\Models\Appartement;

class SubscriptionBillingTest extends TestCase
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
    }

    /**
     * Test the monthly subscription fee calculation algorithm for an Immeuble.
     */
    public function test_subscription_price_calculation_for_immeuble(): void
    {
        // 1. Create a building managed by our primary syndic
        $immeuble = Immeuble::create([
            'nom' => 'Résidence Al Manar',
            'adresse' => 'Avenue de la Gare',
            'ville' => 'Casablanca',
            'nombre_etages' => 4,
            'nombre_appartements' => 8,
            'syndic_id' => $this->syndic->id,
        ]);

        // 2. Create 2 apartments
        $appt1 = Appartement::create([
            'immeuble_id' => $immeuble->id,
            'numero' => '1',
            'etage' => 1,
            'superficie' => 80.00,
            'type' => 'F3',
            'statut' => 'occupé',
        ]);
        $appt2 = Appartement::create([
            'immeuble_id' => $immeuble->id,
            'numero' => '2',
            'etage' => 1,
            'superficie' => 85.00,
            'type' => 'F3',
            'statut' => 'occupé',
        ]);

        // 3. Create 2 residents
        $resident1 = User::create([
            'nom' => 'El Mansouri',
            'prenom' => 'Ahmed',
            'email' => 'ahmed@resident.com',
            'password' => bcrypt('password'),
            'role' => 'resident',
            'is_active' => true,
        ]);
        $resident1->appartements()->attach($appt1->id, ['date_entree' => now()]);

        $resident2 = User::create([
            'nom' => 'Alaoui',
            'prenom' => 'Meryem',
            'email' => 'meryem@resident.com',
            'password' => bcrypt('password'),
            'role' => 'resident',
            'is_active' => true,
        ]);
        $resident2->appartements()->attach($appt2->id, ['date_entree' => now()]);

        // 4. Create 1 secondary syndic
        $secondarySyndic = User::create([
            'nom' => 'Adjoint',
            'prenom' => 'Karim',
            'email' => 'karim@syndic.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $immeuble->secondarySyndics()->attach($secondarySyndic->id);

        // 5. Calculate and assert
        // Residents: 2 (2 * 4 = 8 DH)
        // Syndics: 2 (Primary + 1 Secondary) (2 * 8 = 16 DH)
        // Expected: 24 DH
        $calc = $immeuble->calculateMonthlySubscription();

        $this->assertEquals(2, $calc['residents_count']);
        $this->assertEquals(2, $calc['syndics_count']);
        $this->assertEquals(8, $calc['residents_price']);
        $this->assertEquals(16, $calc['syndics_price']);
        $this->assertEquals(24, $calc['total_price']);
    }

    /**
     * Test the combined total subscription fee for a primary syndic.
     */
    public function test_combined_subscription_total_for_syndic(): void
    {
        // Immeuble A: 1 resident (4 DH) + 1 syndic (8 DH) = 12 DH
        $immeubleA = Immeuble::create([
            'nom' => 'Résidence A',
            'adresse' => 'Rond point A',
            'ville' => 'Casablanca',
            'nombre_etages' => 2,
            'nombre_appartements' => 4,
            'syndic_id' => $this->syndic->id,
        ]);
        $apptA = Appartement::create(['immeuble_id' => $immeubleA->id, 'numero' => 'A1', 'etage' => 1, 'superficie' => 70, 'type' => 'F2', 'statut' => 'occupé']);
        $resA = User::create(['nom' => 'ResA', 'prenom' => 'One', 'email' => 'resa@email.com', 'password' => bcrypt('password'), 'role' => 'resident', 'is_active' => true]);
        $resA->appartements()->attach($apptA->id, ['date_entree' => now()]);

        // Immeuble B: 2 residents (8 DH) + 1 syndic (8 DH) = 16 DH
        $immeubleB = Immeuble::create([
            'nom' => 'Résidence B',
            'adresse' => 'Rond point B',
            'ville' => 'Casablanca',
            'nombre_etages' => 2,
            'nombre_appartements' => 4,
            'syndic_id' => $this->syndic->id,
        ]);
        $apptB1 = Appartement::create(['immeuble_id' => $immeubleB->id, 'numero' => 'B1', 'etage' => 1, 'superficie' => 70, 'type' => 'F2', 'statut' => 'occupé']);
        $apptB2 = Appartement::create(['immeuble_id' => $immeubleB->id, 'numero' => 'B2', 'etage' => 1, 'superficie' => 70, 'type' => 'F2', 'statut' => 'occupé']);
        $resB1 = User::create(['nom' => 'ResB1', 'prenom' => 'One', 'email' => 'resb1@email.com', 'password' => bcrypt('password'), 'role' => 'resident', 'is_active' => true]);
        $resB2 = User::create(['nom' => 'ResB2', 'prenom' => 'Two', 'email' => 'resb2@email.com', 'password' => bcrypt('password'), 'role' => 'resident', 'is_active' => true]);
        $resB1->appartements()->attach($apptB1->id, ['date_entree' => now()]);
        $resB2->appartements()->attach($apptB2->id, ['date_entree' => now()]);

        // Total Expected: 12 + 16 = 28 DH
        $sub = $this->syndic->calculateTotalSubscription();
        $this->assertEquals(28, $sub['total_price']);
        $this->assertCount(2, $sub['breakdown']);
    }

    /**
     * Test that admin can toggle a syndic account status.
     */
    public function test_admin_can_toggle_syndic_status(): void
    {
        $this->actingAs($this->admin);

        $this->assertTrue($this->syndic->is_active);

        // Deactivate
        $response = $this->post(route('admin.syndics.toggle-status', $this->syndic->id));
        $response->assertStatus(302);
        $this->assertFalse($this->syndic->fresh()->is_active);

        // Reactivate
        $response = $this->post(route('admin.syndics.toggle-status', $this->syndic->id));
        $response->assertStatus(302);
        $this->assertTrue($this->syndic->fresh()->is_active);
    }

    /**
     * Test that an inactive syndic is logged out and blocked by RoleMiddleware.
     */
    public function test_inactive_syndic_is_blocked_by_middleware(): void
    {
        // Deactivate the syndic
        $this->syndic->update(['is_active' => false]);

        $this->actingAs($this->syndic);

        // Attempt to access syndic dashboard
        $response = $this->get(route('syndic.dashboard'));

        // Assert redirect to login with specialized payment error message
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Votre compte Syndic est suspendu ou en attente d\'activation. Veuillez contacter l\'administrateur pour activer ou réactiver votre accès.'
        ]);

        // Assert user is logged out
        $this->assertNull(auth()->user());
    }
}
