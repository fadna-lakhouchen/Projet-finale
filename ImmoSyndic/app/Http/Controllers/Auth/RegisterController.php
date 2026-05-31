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
        return Validator::make($data, [
            'prenom' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users',
                function ($attribute, $value, $fail) {
                    $parts = explode('@', $value);
                    $domain = end($parts);
                    if ($domain && !checkdnsrr($domain, 'MX')) {
                        $fail('Le domaine de l\'adresse email (' . $domain . ') n\'est pas configuré pour recevoir des emails.');
                    }
                }
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
            // Immeuble inputs
            'immeuble_type' => ['required', 'in:new,existing'],
            'immeuble_nom' => ['required_if:immeuble_type,new', 'nullable', 'string', 'max:255'],
            'immeuble_ville' => ['required_if:immeuble_type,new', 'nullable', 'string', 'max:255'],
            'immeuble_id' => ['required_if:immeuble_type,existing', 'nullable', 'exists:immeubles,id'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'prenom' => $data['prenom'],
            'nom' => $data['nom'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'syndic',
            'is_active' => true,
        ]);

        // Assign spatie role if enabled
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('syndic');
        }

        // Handle Immeuble
        if ($data['immeuble_type'] === 'new') {
            Immeuble::create([
                'nom' => $data['immeuble_nom'],
                'ville' => $data['immeuble_ville'],
                'syndic_id' => $user->id,
                'adresse' => 'Adresse non spécifiée',
                'nombre_etages' => 0,
                'nombre_appartements' => 0,
            ]);
        } else {
            $immeuble = Immeuble::findOrFail($data['immeuble_id']);
            $immeuble->update([
                'syndic_id' => $user->id
            ]);
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

        $parts = explode('@', $email);
        $domain = end($parts);

        if ($domain && !checkdnsrr($domain, 'MX')) {
            return response()->json([
                'valid' => false,
                'exists' => false,
                'message' => 'Le domaine de cette adresse email (' . $domain . ') n\'est pas configuré pour recevoir des emails.'
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
