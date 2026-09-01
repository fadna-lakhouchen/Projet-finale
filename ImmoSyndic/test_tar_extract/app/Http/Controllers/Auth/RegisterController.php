<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Immeuble;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(\Illuminate\Http\Request $request)
    {
        if (session()->has('google_user_details')) {
            $googleDetails = session('google_user_details');
            $request->merge([
                'prenom' => $googleDetails['prenom'] ?? '',
                'nom' => $googleDetails['nom'] ?? '',
                'email' => $googleDetails['email'] ?? '',
            ]);
        }

        $this->validator($request->all())->validate();

        $user = null;
        DB::transaction(function () use ($request, &$user) {
            $user = $this->create($request->all());
        });

        // Fire Registered event to trigger sending email verification
        event(new \Illuminate\Auth\Events\Registered($user));

        $this->guard()->login($user);

        if (session()->has('google_user_details')) {
            session()->forget('google_user_details');
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $immeubles = Immeuble::all(['id', 'nom', 'ville']);
        return view('auth.register', compact('immeubles'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $role = $data['role'] ?? 'syndic';

        $rules = [
            'role' => ['required', 'in:syndic,resident'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users',
            ],
            'telephone' => ['nullable', 'string', 'max:20'],
            'cin' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (session()->has('google_user_details')) {
            unset($rules['password']);
        }

        if ($role === 'resident') {
            $rules['immeuble_id'] = ['required', 'exists:immeubles,id'];
            $rules['numero_appartement'] = ['required', 'string', 'max:255'];
            $rules['date_entree'] = ['nullable', 'date'];
        } else {
            $rules['immeuble_type'] = ['required', 'in:new,existing'];
            $rules['immeuble_nom'] = ['required_if:immeuble_type,new', 'nullable', 'string', 'max:255'];
            $rules['immeuble_ville'] = ['required_if:immeuble_type,new', 'nullable', 'string', 'max:255'];
            $rules['immeuble_id'] = ['required_if:immeuble_type,existing', 'nullable', 'exists:immeubles,id'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $role = $data['role'] ?? 'syndic';

        $googleDetails = session('google_user_details');
        $password = $googleDetails ? Str::random(24) : $data['password'];

        $user = User::create([
            'prenom' => $data['prenom'] ?? '',
            'nom' => $data['nom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'] ?? null,
            'cin' => $data['cin'] ?? null,
            'password' => Hash::make($password),
            'role' => $role,
            'is_active' => true,
            'google_id' => $googleDetails['google_id'] ?? null,
            'google_token' => $googleDetails['google_token'] ?? null,
        ]);

        // Assign spatie role if enabled
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role);
        }

        if ($role === 'resident') {
            // Find or create the apartment with default cotisation_mensuelle of 0.00
            $appt = \App\Models\Appartement::firstOrCreate(
                [
                    'immeuble_id' => $data['immeuble_id'],
                    'numero' => $data['numero_appartement'],
                ],
                [
                    'etage' => 1,
                    'superficie' => 80.00,
                    'type' => 'F3',
                    'statut' => 'occupé',
                    'cotisation_mensuelle' => 0.00,
                ]
            );

            // Link the resident to the apartment
            $user->appartements()->attach($appt->id, [
                'date_entree' => $data['date_entree'] ?? now()->format('Y-m-d'),
            ]);

            // Generate initial monthly charge for the new apartment (will be 0.00)
            \App\Models\Charge::generateCurrentMonthCharge($appt->id);

            // Send notification to the syndic of the building
            $syndic = $appt->immeuble ? $appt->immeuble->syndic : null;
            if ($syndic) {
                \App\Models\Notification::create([
                    'user_id' => $syndic->id,
                    'titre' => '👤 Nouveau résident inscrit',
                    'message' => "Le résident {$user->prenom} {$user->nom} s'est inscrit à l'appartement n° {$appt->numero} de l'immeuble \"{$appt->immeuble->nom}\". Veuillez définir son montant de cotisation mensuelle.",
                    'type' => 'new_resident',
                    'lu' => false,
                    'date_envoi' => now(),
                ]);
            }
        } else {
            // Handle Immeuble
            if ($data['immeuble_type'] === 'new') {
                \App\Models\Immeuble::create([
                    'nom' => $data['immeuble_nom'],
                    'ville' => $data['immeuble_ville'],
                    'syndic_id' => $user->id,
                    'adresse' => 'Adresse non spécifiée',
                    'nombre_etages' => 0,
                    'nombre_appartements' => 0,
                ]);
            } else {
                $immeuble = \App\Models\Immeuble::findOrFail($data['immeuble_id']);
                if ($immeuble->syndic_id) {
                    $immeuble->secondarySyndics()->attach($user->id);
                } else {
                    $immeuble->update([
                        'syndic_id' => $user->id
                    ]);
                }
            }
        }

        return $user;
    }

    /**
     * Check if email is valid and if it already exists in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(\Illuminate\Http\Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return response()->json([
                'valid' => false,
                'exists' => false,
                'message' => 'L\'adresse email est requise.'
            ]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'valid' => false,
                'exists' => false,
                'message' => 'L\'adresse email n\'est pas valide.'
            ]);
        }



        $exists = User::where('email', $email)->exists();

        return response()->json([
            'valid' => true,
            'exists' => $exists,
            'message' => $exists 
                ? 'Cette adresse email est déjà utilisée.' 
                : 'Adresse email disponible.'
        ]);
    }
}
