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
            $errorMessage = 'Votre compte est inactif.';
            if ($user->role === 'syndic') {
                $errorMessage = 'Votre compte Syndic est suspendu ou en attente d\'activation. Veuillez contacter l\'administrateur pour activer ou réactiver votre accès.';
            }

            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => $errorMessage,
            ]);
        }

        $activeRole = $user->active_role;

        if ($activeRole !== $role) {
            if ($role === 'administrateur' && ($activeRole === 'admin' || $activeRole === 'administrateur')) {
                return $next($request);
            }
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
