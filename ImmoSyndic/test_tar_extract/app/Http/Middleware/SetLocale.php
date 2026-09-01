<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the locale from session, fallback to the config locale, fallback to 'ar'
        $locale = session('locale', config('app.locale', 'ar'));

        // Check if the requested locale is supported
        if (in_array($locale, ['ar', 'fr', 'en'])) {
            App::setLocale($locale);
            \Carbon\Carbon::setLocale($locale);
        } else {
            App::setLocale('ar');
            \Carbon\Carbon::setLocale('ar');
        }

        return $next($request);
    }
}
