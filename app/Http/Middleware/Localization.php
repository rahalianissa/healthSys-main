<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Récupérer la langue depuis la session
        $locale = Session::get('locale', 'fr');
        
        // Vérifier si la langue est valide
        $availableLocales = ['fr', 'ar', 'en'];
        
        if (in_array($locale, $availableLocales)) {
            App::setLocale($locale);
        } else {
            App::setLocale('fr');
        }
        
        // Debug (optionnel - à retirer en production)
        // Log::info('Language set to: ' . App::getLocale());
        
        return $next($request);
    }
}