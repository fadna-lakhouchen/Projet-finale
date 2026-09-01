<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Immeuble;
use App\Models\Appartement;
use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectToGoogle(Request $request)
    {
        // Save form details if any (role, immeuble_id, numero_appartement, date_entree, etc.)
        if ($request->has('role')) {
            $data = $request->only([
                'role',
                'immeuble_id',
                'numero_appartement',
                'date_entree',
                'immeuble_type',
                'immeuble_nom',
                'immeuble_ville',
                'telephone',
                'cin',
            ]);
            session(['pending_google_registration' => $data]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and authenticate/register them.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $driver = Socialite::driver('google');

            // Bypass SSL verification in local environment to avoid cURL error 60
            if (app()->environment('local') && method_exists($driver, 'setHttpClient')) {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }

            $googleUser = $driver->user();
        } catch (\Exception $e) {
            return redirect()->route('register')->with('error', 'Erreur d\'authentification avec Google : ' . $e->getMessage());
        }

        // Check if user already exists
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update Google token/ID if they matched by email but didn't have google_id yet
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                ]);
            }
            
            // Mark email as verified since they authenticated with Google
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }
            
            Auth::login($user, true);
            return redirect()->route('dashboard');
        }

        // Check if there is a pending registration
        $registrationData = session('pending_google_registration');

        if (!$registrationData) {
            // No registration data in session -> they clicked sign-in with Google, but have no account.
            // Save details to prefill form
            $fullName = $googleUser->getName();
            $parts = explode(' ', $fullName, 2);
            $prenom = $parts[0] ?? '';
            $nom = $parts[1] ?? ($prenom ?: 'Utilisateur Google');

            session(['google_user_details' => [
                'prenom' => $prenom,
                'nom' => $nom,
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
            ]]);

            return redirect()->route('register')->with('info', 'Compte introuvable. Veuillez d\'abord sélectionner votre immeuble pour vous inscrire.');
        }

        // We have pending registration data! Let's create the user.
        $role = $registrationData['role'] ?? 'resident';

        // Split name from Google into prenom / nom
        $fullName = $googleUser->getName();
        $parts = explode(' ', $fullName, 2);
        $prenom = $parts[0] ?? '';
        $nom = $parts[1] ?? ($prenom ?: 'Utilisateur Google');

        $newUser = User::create([
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'google_token' => $googleUser->token,
            'password' => Hash::make(Str::random(24)), // Random secure password
            'telephone' => $registrationData['telephone'] ?? null,
            'cin' => $registrationData['cin'] ?? null,
            'role' => $role,
            'is_active' => true,
        ]);

        // Assign Spatie Role
        if (method_exists($newUser, 'assignRole')) {
            $newUser->assignRole($role);
        }

        // Fire Registered event to send verification email and redirect to validation screen
        event(new \Illuminate\Auth\Events\Registered($newUser));

        // Execute specific role initialization logic
        if ($role === 'resident') {
            if (isset($registrationData['immeuble_id']) && isset($registrationData['numero_appartement'])) {
                // Find or create the apartment
                $appt = Appartement::firstOrCreate(
                    [
                        'immeuble_id' => $registrationData['immeuble_id'],
                        'numero' => $registrationData['numero_appartement'],
                    ],
                    [
                        'etage' => 1,
                        'superficie' => 80.00,
                        'type' => 'F3',
                        'statut' => 'occupé',
                    ]
                );

                // Link the resident to the apartment
                $newUser->appartements()->attach($appt->id, [
                    'date_entree' => $registrationData['date_entree'] ?? now()->format('Y-m-d'),
                ]);

                // Generate initial monthly charge for the new apartment
                Charge::generateCurrentMonthCharge($appt->id);
            }
        } else {
            // Syndic logic
            if (isset($registrationData['immeuble_type'])) {
                if ($registrationData['immeuble_type'] === 'new') {
                    Immeuble::create([
                        'nom' => $registrationData['immeuble_nom'] ?? 'Nouvel Immeuble',
                        'ville' => $registrationData['immeuble_ville'] ?? 'Casablanca',
                        'syndic_id' => $newUser->id,
                        'adresse' => 'Adresse non spécifiée',
                        'nombre_etages' => 0,
                        'nombre_appartements' => 0,
                    ]);
                } else if (isset($registrationData['immeuble_id'])) {
                    $immeuble = Immeuble::findOrFail($registrationData['immeuble_id']);
                    if ($immeuble->syndic_id) {
                        $immeuble->secondarySyndics()->attach($newUser->id);
                    } else {
                        $immeuble->update([
                            'syndic_id' => $newUser->id
                        ]);
                    }
                }
            }
        }

        // Clean up session
        session()->forget(['pending_google_registration', 'google_user_details']);

        // Log the user in
        Auth::login($newUser, true);

        return redirect()->route('dashboard')->with('success', 'Votre compte a été créé avec succès avec Google !');
    }

    /**
     * Cancel Google registration and clear the session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelGoogleRegistration()
    {
        session()->forget(['pending_google_registration', 'google_user_details']);
        return redirect()->route('register')->with('status', 'Inscription avec Google annulée.');
    }
}
