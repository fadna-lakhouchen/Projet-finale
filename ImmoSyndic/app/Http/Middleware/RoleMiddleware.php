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

            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte est inactif ou en attente d\'approbation.',
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
