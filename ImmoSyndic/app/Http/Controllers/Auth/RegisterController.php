<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Immeuble;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'prenom' => ['required', 'string', 'max:255'],
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

        if ($role === 'resident') {
            $rules['immeuble_id'] = ['required', 'exists:immeubles,id'];
            $rules['numero_appartement'] = ['required', 'string', 'max:255'];
            $rules['date_entree'] = ['required', 'date'];
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

        $user = User::create([
            'prenom' => $data['prenom'],
            'nom' => $data['nom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'] ?? null,
            'cin' => $data['cin'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $role,
            'is_active' => true, // Everyone is active immediately upon registration
        ]);

        // Assign spatie role if enabled
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role);
        }

        if ($role === 'resident') {
            // Find or create the apartment
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
                ]
            );

            // Link the resident to the apartment
            $user->appartements()->attach($appt->id, [
                'date_entree' => $data['date_entree'],
            ]);

            // Generate initial monthly charge for the new apartment
            \App\Models\Charge::generateCurrentMonthCharge($appt->id);
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
                $immeuble->update([
                    'syndic_id' => $user->id
                ]);
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
