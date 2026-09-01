<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed Spatie roles if necessary
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'administrateur']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'syndic']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'resident']);
        }
    }

    public function test_user_can_update_profile_information()
    {
        $user = User::forceCreate([
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'john.doe@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'resident',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('profile.update'), [
                'prenom' => 'Jane',
                'nom' => 'Smith',
                'email' => 'jane.smith@example.com',
                'telephone' => '0612345678',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Jane', $user->prenom);
        $this->assertEquals('Smith', $user->nom);
        $this->assertEquals('jane.smith@example.com', $user->email);
        $this->assertEquals('0612345678', $user->telephone);
    }

    public function test_user_can_update_password()
    {
        $user = User::forceCreate([
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'john.doe@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'resident',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('profile.password'), [
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_user_cannot_update_password_with_incorrect_current_password()
    {
        $user = User::forceCreate([
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'john.doe@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'resident',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('profile.password'), [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $user->refresh();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_custom_email_verification_notification_is_sent_on_registration()
    {
        \Illuminate\Support\Facades\Notification::fake();

        // Create an Immeuble for the resident to link to
        $immeuble = \App\Models\Immeuble::create([
            'nom' => 'Immeuble Test',
            'ville' => 'Casablanca',
            'adresse' => 'Test',
            'nombre_etages' => 5,
            'nombre_appartements' => 10,
        ]);

        $response = $this->post(route('register'), [
            'role' => 'resident',
            'prenom' => 'Hassan',
            'nom' => 'Afaiz',
            'email' => 'hassan.afaiz@example.com',
            'telephone' => '0600000003',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'immeuble_id' => $immeuble->id,
            'numero_appartement' => '10A',
        ]);

        $response->assertRedirect();

        $user = User::where('email', 'hassan.afaiz@example.com')->first();
        $this->assertNotNull($user);

        \Illuminate\Support\Facades\Notification::assertSentTo(
            $user,
            \App\Notifications\CustomVerifyEmail::class
        );
    }

    public function test_language_switching_works()
    {
        // Default locale is 'ar' in .env
        $this->get('/');
        $this->assertEquals('ar', app()->getLocale());

        // Switch to French
        $response = $this->get(route('lang.switch', 'fr'));
        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');

        // Request again to trigger middleware and confirm it switches
        $this->get('/');
        $this->assertEquals('fr', app()->getLocale());

        // Switch to English
        $response = $this->get(route('lang.switch', 'en'));
        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');

        // Request again to trigger middleware and confirm it switches
        $this->get('/');
        $this->assertEquals('en', app()->getLocale());
    }

    public function test_admin_can_promote_resident_to_syndic()
    {
        $admin = User::forceCreate([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'super.admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'administrateur',
            'is_active' => true,
        ]);
        $admin->assignRole('administrateur');

        $resident = User::forceCreate([
            'nom' => 'Resident',
            'prenom' => 'Test',
            'email' => 'resident.test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'resident',
            'is_active' => true,
        ]);
        $resident->assignRole('resident');

        $immeuble = \App\Models\Immeuble::create([
            'nom' => 'Immeuble Test',
            'ville' => 'Casablanca',
            'adresse' => 'Test',
            'nombre_etages' => 5,
            'nombre_appartements' => 10,
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.residents.update', $resident->id), [
                'prenom' => 'Test',
                'nom' => 'Resident',
                'email' => 'resident.test@example.com',
                'telephone' => '0600000000',
                'immeuble_id' => $immeuble->id,
                'numero_appartement' => '10A',
                'date_entree' => '2026-06-24',
                'is_syndic' => '1',
            ]);

        $response->assertRedirect();
        $resident->refresh();
        $this->assertTrue($resident->hasRole('syndic'));
        $this->assertTrue($resident->hasRole('resident'));
    }

    public function test_admin_can_promote_syndic_to_resident()
    {
        $admin = User::forceCreate([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'super.admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'administrateur',
            'is_active' => true,
        ]);
        $admin->assignRole('administrateur');

        $syndic = User::forceCreate([
            'nom' => 'Syndic',
            'prenom' => 'Test',
            'email' => 'syndic.test@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'role' => 'syndic',
            'is_active' => true,
        ]);
        $syndic->assignRole('syndic');

        $immeuble = \App\Models\Immeuble::create([
            'nom' => 'Immeuble Test',
            'ville' => 'Casablanca',
            'adresse' => 'Test',
            'nombre_etages' => 5,
            'nombre_appartements' => 10,
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.syndics.update', $syndic->id), [
                'prenom' => 'Test',
                'nom' => 'Syndic',
                'email' => 'syndic.test@example.com',
                'telephone' => '0600000000',
                'is_resident' => '1',
                'resident_immeuble_id' => $immeuble->id,
                'resident_numero_appartement' => '10B',
                'resident_date_entree' => '2026-06-24',
            ]);

        $response->assertRedirect();
        $syndic->refresh();
        $this->assertTrue($syndic->hasRole('resident'));
        $this->assertTrue($syndic->hasRole('syndic'));
        $this->assertEquals('10B', $syndic->appartements->first()->numero);
    }
}
