<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Immeuble;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed Spatie roles if necessary (in case role assignment fails)
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'syndic']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'resident']);
        }
    }

    public function test_google_redirect()
    {
        $response = $this->get(route('auth.google.redirect'));
        
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_google_callback_with_existing_user()
    {
        $user = User::create([
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'resident',
            'is_active' => true,
            'google_id' => null,
        ]);

        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('123456789');
        $googleUser->shouldReceive('getEmail')->andReturn('johndoe@gmail.com');
        $googleUser->shouldReceive('getName')->andReturn('John Doe');
        $googleUser->token = 'mock-token';

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        
        // Verify google_id is updated
        $this->assertEquals('123456789', $user->fresh()->google_id);
    }

    public function test_google_callback_with_new_user_no_pending_registration()
    {
        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('123456789');
        $googleUser->shouldReceive('getEmail')->andReturn('newuser@gmail.com');
        $googleUser->shouldReceive('getName')->andReturn('New User');
        $googleUser->token = 'mock-token';

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        // Should redirect to register page with info message
        $response->assertRedirect(route('register'));
        $response->assertSessionHas('google_user_details');
        $this->assertEquals('newuser@gmail.com', session('google_user_details.email'));
    }

    public function test_google_callback_with_new_resident_and_pending_registration()
    {
        $immeuble = Immeuble::create([
            'nom' => 'Résidence Atlas',
            'adresse' => 'Route de l\'Ourika',
            'ville' => 'Marrakech',
            'nombre_etages' => 4,
            'nombre_appartements' => 16,
        ]);

        // Simulate choosing building info and submitting to redirect
        $this->post(route('auth.google.redirect.post'), [
            'role' => 'resident',
            'immeuble_id' => $immeuble->id,
            'numero_appartement' => 'A102',
            'date_entree' => '2026-06-15',
        ]);

        $this->assertTrue(session()->has('pending_google_registration'));

        // Mock Google Auth callback
        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('987654321');
        $googleUser->shouldReceive('getEmail')->andReturn('newresident@gmail.com');
        $googleUser->shouldReceive('getName')->andReturn('New Resident');
        $googleUser->token = 'mock-token';

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        // Fake notifications to check if verification email is sent
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        
        $this->assertDatabaseHas('users', [
            'email' => 'newresident@gmail.com',
            'google_id' => '987654321',
            'role' => 'resident',
        ]);

        $user = User::where('email', 'newresident@gmail.com')->first();
        $this->assertAuthenticatedAs($user);
        
        // Assert email verification was sent
        \Illuminate\Support\Facades\Notification::assertSentTo(
            $user,
            \App\Notifications\CustomVerifyEmail::class
        );

        // Assert apartment is created and linked
        $this->assertCount(1, $user->appartements);
        $this->assertEquals('A102', $user->appartements->first()->numero);
        $this->assertTrue($user->is_active);
    }

    public function test_google_callback_with_new_syndic_and_pending_registration()
    {
        // Simulate choosing building info and submitting to redirect for a syndic
        $this->post(route('auth.google.redirect.post'), [
            'role' => 'syndic',
            'immeuble_type' => 'new',
            'immeuble_nom' => 'Résidence Majorelle',
            'immeuble_ville' => 'Marrakech',
            'telephone' => '0670000000',
            'cin' => 'EE000111',
        ]);

        $this->assertTrue(session()->has('pending_google_registration'));

        // Mock Google Auth callback
        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('111222333');
        $googleUser->shouldReceive('getEmail')->andReturn('newsyndic@gmail.com');
        $googleUser->shouldReceive('getName')->andReturn('New Syndic');
        $googleUser->token = 'mock-token';

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        // Fake notifications to check if verification email is sent
        \Illuminate\Support\Facades\Notification::fake();

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'newsyndic@gmail.com',
            'google_id' => '111222333',
            'role' => 'syndic',
            'is_active' => true,
        ]);

        $user = User::where('email', 'newsyndic@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->is_active);
        $this->assertAuthenticatedAs($user);

        // Assert building is created for this syndic
        $this->assertDatabaseHas('immeubles', [
            'nom' => 'Résidence Majorelle',
            'syndic_id' => $user->id,
        ]);
    }
}
