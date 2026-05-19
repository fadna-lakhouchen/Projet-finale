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

        if (!auth()->user()->hasRole($role) && auth()->user()->role !== $role) {
            if ($role === 'administrateur' && auth()->user()->role === 'admin') {
                return $next($request);
            }
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
