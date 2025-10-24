<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language', config('app.locale'));
        $locale = substr($locale, 0, 2);

        $supportedLocales = ['en', 'es', 'fr']; 
        
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.fallback_locale', 'en'); 
        }

        App::setLocale($locale);
        
        return $next($request);
    }
}
