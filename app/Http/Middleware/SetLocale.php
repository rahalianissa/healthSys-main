<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Récupérer la langue de la session
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            // Langue par défaut
            app()->setLocale('fr');
        }
        
        return $next($request);
    }
}