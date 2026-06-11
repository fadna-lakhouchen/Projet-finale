<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Immeuble;
use App\Models\AuditLog;
use App\Models\Paiement;
use App\Models\Appartement;
use App\Models\Charge;

class SyndicSecondaryManagementTest extends TestCase
{
    use RefreshDatabase;

    private $primarySyndic;
    private $secondarySyndic;
    private $immeuble;
    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        // Create primary syndic (Syndic 1)
        $this->primarySyndic = User::create([
            'nom' => 'Benzakour',
            'prenom' => 'Karim',
            'email' => 'karim@syndic.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);

        $this->primarySyndic->email_verified_at = now();
        $this->primarySyndic->save();

        // Create building owned by primary syndic
        $this->immeuble = Immeuble::create([
            'nom' => 'Résidence Atlas',
            'adresse' => 'Route de l\'Ourika',
            'ville' => 'Marrakech',
            'nombre_etages' => 4,
            'nombre_appartements' => 16,
            'syndic_id' => $this->primarySyndic->id,
        ]);
    }

    /**
     * Test that Syndic 1 can add a secondary syndic to their building.
     */
    public function test_primary_syndic_can_add_secondary_syndic(): void
    {
        $this->actingAs($this->primarySyndic);

        $response = $this->post(route('syndic.secondary-syndics.store'), [
            'prenom' => 'Yassine',
            'nom' => 'El Fassi',
            'email' => 'yassine@secondary.com',
            'telephone' => '0612345678',
            'cin' => 'EE123456',
            'immeuble_id' => $this->immeuble->id,
        ]);

        $response->assertStatus(302);
        
        // Check if secondary syndic exists in DB
        $secondary = User::where('email', 'yassine@secondary.com')->first();
        $this->assertNotNull($secondary);
        $this->assertEquals('syndic', $secondary->role);

        // Check relationship in pivot table
        $this->assertTrue($this->immeuble->secondarySyndics->contains($secondary->id));
        
        // Verify log created
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->primarySyndic->id,
            'action' => 'created',
            'model_type' => User::class,
            'model_id' => $secondary->id,
        ]);
    }

    /**
     * Test that secondary syndics cannot add other syndics (unauthorized action).
     */
    public function test_secondary_syndic_cannot_add_secondary_syndic(): void
    {
        // Add a secondary syndic first
        $secondary = User::create([
            'nom' => 'Berrada',
            'prenom' => 'Adnane',
            'email' => 'adnane@secondary.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $secondary->email_verified_at = now();
        $secondary->save();
        $this->immeuble->secondarySyndics()->attach($secondary->id);

        // Act as secondary syndic and try to add another secondary syndic
        $this->actingAs($secondary);

        $response = $this->post(route('syndic.secondary-syndics.store'), [
            'prenom' => 'Mehdi',
            'nom' => 'Tahiri',
            'email' => 'mehdi@secondary.com',
            'immeuble_id' => $this->immeuble->id,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that secondary syndics have access to the building dashboard and can record payments.
     */
    public function test_secondary_syndic_can_view_dashboard_and_record_payment(): void
    {
        // Create secondary syndic
        $secondary = User::create([
            'nom' => 'Berrada',
            'prenom' => 'Adnane',
            'email' => 'adnane@secondary.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $secondary->email_verified_at = now();
        $secondary->save();
        $this->immeuble->secondarySyndics()->attach($secondary->id);

        // Create resident and charge f the building
        $appt = Appartement::create([
            'immeuble_id' => $this->immeuble->id,
            'numero' => '3A',
            'etage' => 1,
            'superficie' => 85.00,
            'type' => 'F3',
            'statut' => 'occupé',
        ]);
        $resident = User::create([
            'nom' => 'Naji',
            'prenom' => 'Mariam',
            'email' => 'mariam@resident.com',
            'password' => bcrypt('password'),
            'role' => 'resident',
            'is_active' => true,
        ]);
        $resident->email_verified_at = now();
        $resident->save();
        $resident->appartements()->attach($appt->id, ['date_entree' => now()]);

        $charge = Charge::create([
            'appartement_id' => $appt->id,
            'titre' => 'Cotisation Janvier 2026',
            'montant' => 850.00,
            'date_echeance' => '2026-01-25',
            'statut' => 'impayé',
        ]);

        // Act as secondary syndic
        $this->actingAs($secondary);

        // Check they can see the building f dashboard
        $response = $this->get(route('syndic.dashboard'));
        $response->assertStatus(200);
        
        $immeubles = $response->original->getData()['immeubles'];
        $this->assertTrue($immeubles->contains($this->immeuble->id));

        // Register a payment
        $response = $this->post(route('syndic.paiements.store'), [
            'charge_id' => $charge->id,
            'montant' => 850.00,
            'date_paiement' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $this->assertEquals('payé', $charge->fresh()->statut);

        // Verify audit log has user_id of the secondary syndic
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $secondary->id,
            'action' => 'created',
            'model_type' => Paiement::class,
        ]);
    }

    /**
     * Test that primary syndics can remove a secondary syndic.
     */
    public function test_primary_syndic_can_remove_secondary_syndic(): void
    {
        // Add secondary syndic
        $secondary = User::create([
            'nom' => 'Berrada',
            'prenom' => 'Adnane',
            'email' => 'adnane@secondary.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $secondary->email_verified_at = now();
        $secondary->save();
        $this->immeuble->secondarySyndics()->attach($secondary->id);

        $this->actingAs($this->primarySyndic);

        $response = $this->delete(route('syndic.secondary-syndics.destroy', $secondary->id));
        $response->assertStatus(302);

        // Verify detachment f pivot table
        $this->assertFalse($this->immeuble->fresh()->secondarySyndics->contains($secondary->id));

        // Verify secondary syndic deleted since they have no other building associations
        $this->assertNull(User::find($secondary->id));
    }

    /**
     * Test that registering as a syndic for an existing building that already has a primary syndic
     * registers them as a secondary syndic in the pivot table rather than replacing the primary syndic.
     */
    public function test_registering_as_syndic_for_occupied_building_becomes_secondary(): void
    {
        $response = $this->post('/register', [
            'role' => 'syndic',
            'prenom' => 'Said',
            'nom' => 'Tazi',
            'email' => 'said@secondary.com',
            'telephone' => '0623456789',
            'cin' => 'EE987654',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'immeuble_type' => 'existing',
            'immeuble_id' => $this->immeuble->id,
        ]);

        $response->assertStatus(302);

        // Retrieve registered user
        $newUser = User::where('email', 'said@secondary.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('syndic', $newUser->role);

        // Verify the original primary syndic is STILL the primary owner in the building
        $this->immeuble = $this->immeuble->fresh();
        $this->assertEquals($this->primarySyndic->id, $this->immeuble->syndic_id);

        // Verify that the new user is linked via the pivot table
        $this->assertTrue($this->immeuble->secondarySyndics->contains($newUser->id));
    }

    /**
     * Test that registering as a syndic for an existing building that has no primary syndic
     * registers them as the primary syndic directly.
     */
    public function test_registering_as_syndic_for_vacant_building_becomes_primary(): void
    {
        // Create an empty building with no primary syndic (syndic_id is null)
        $vacantImmeuble = Immeuble::create([
            'nom' => 'Résidence Vacante',
            'adresse' => 'Route de Targa',
            'ville' => 'Marrakech',
            'nombre_etages' => 3,
            'nombre_appartements' => 12,
            'syndic_id' => null,
        ]);

        $response = $this->post('/register', [
            'role' => 'syndic',
            'prenom' => 'Amine',
            'nom' => 'Alami',
            'email' => 'amine@primary.com',
            'telephone' => '0634567890',
            'cin' => 'EE111222',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'immeuble_type' => 'existing',
            'immeuble_id' => $vacantImmeuble->id,
        ]);

        $response->assertStatus(302);

        $newUser = User::where('email', 'amine@primary.com')->first();
        $this->assertNotNull($newUser);

        // Verify the new user became the primary syndic of the vacant building
        $vacantImmeuble = $vacantImmeuble->fresh();
        $this->assertEquals($newUser->id, $vacantImmeuble->syndic_id);

        // Verify the pivot table is empty for this building/user
        $this->assertFalse($vacantImmeuble->secondarySyndics->contains($newUser->id));
    }

    /**
     * Test that primary syndic can transfer their role to a secondary syndic of the building.
     */
    public function test_primary_syndic_can_transfer_role_to_secondary(): void
    {
        // Create a secondary syndic
        $secondary = User::create([
            'nom' => 'Berrada',
            'prenom' => 'Adnane',
            'email' => 'adnane@secondary.com',
            'password' => bcrypt('password'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $secondary->email_verified_at = now();
        $secondary->save();
        $this->immeuble->secondarySyndics()->attach($secondary->id);

        // Act as primary syndic
        $this->actingAs($this->primarySyndic);

        // Post to the role transfer route
        $response = $this->post(route('syndic.secondary-syndics.transfer-primary', $secondary->id), [
            'immeuble_id' => $this->immeuble->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));

        $this->immeuble = $this->immeuble->fresh();

        // 1. Verify that the secondary syndic has become the new primary syndic
        $this->assertEquals($secondary->id, $this->immeuble->syndic_id);

        // 2. Verify that the old primary syndic is now a secondary syndic in the pivot table
        $this->assertTrue($this->immeuble->secondarySyndics->contains($this->primarySyndic->id));

        // 3. Verify that the new primary syndic is no longer in the pivot table
        $this->assertFalse($this->immeuble->secondarySyndics->contains($secondary->id));

        // 4. Verify log entry was created
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->primarySyndic->id,
            'action' => 'updated',
            'model_type' => Immeuble::class,
            'model_id' => $this->immeuble->id,
        ]);
    }

    /**
     * Test registering as a syndic without prenom and without cin.
     */
    public function test_registering_as_syndic_without_prenom_and_cin_succeeds(): void
    {
        $response = $this->post('/register', [
            'role' => 'syndic',
            'nom' => 'Alami',
            'email' => 'amine_new@primary.com',
            'telephone' => '0634567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'immeuble_type' => 'new',
            'immeuble_nom' => 'Résidence Nouvelle A',
            'immeuble_ville' => 'Rabat',
        ]);

        $response->assertStatus(302);

        $newUser = User::where('email', 'amine_new@primary.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('syndic', $newUser->role);
        $this->assertEquals('', $newUser->prenom); // Must default to empty string
        $this->assertNull($newUser->cin);
        $this->assertEquals('Alami', $newUser->name); // Name accessor should trim
    }

    /**
     * Test registering as a resident without prenom succeeds.
     */
    public function test_registering_as_resident_without_prenom_succeeds(): void
    {
        $response = $this->post('/register', [
            'role' => 'resident',
            'nom' => 'Naji',
            'email' => 'naji_resident@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'immeuble_id' => $this->immeuble->id,
            'numero_appartement' => '4B',
            'date_entree' => '2026-01-01',
        ]);

        $response->assertStatus(302);
        
        $resident = User::where('email', 'naji_resident@test.com')->first();
        $this->assertNotNull($resident);
        $this->assertEquals('', $resident->prenom);
    }
}
