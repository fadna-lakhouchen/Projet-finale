<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            if ($user->role === 'resident') {
                // Allow only the resident dashboard page
                if ($request->is('resident/dashboard') || $request->is('dashboard') || $request->is('home')) {
                    return $next($request);
                }
                return redirect()->route('resident.dashboard');
            }

            $errorMessage = 'Votre compte est inactif ou en attente d\'approbation.';
            if ($user->role === 'syndic') {
                $errorMessage = 'Votre compte Syndic a été suspendu pour défaut de paiement. Veuillez effectuer le virement bancaire mensuel hors-ligne et contacter l\'administrateur pour réactiver votre accès.';
            }

            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => $errorMessage,
            ]);
        }

        if (!$user->hasRole($role) && $user->role !== $role) {
            if ($role === 'administrateur' && $user->role === 'admin') {
                return $next($request);
            }
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
