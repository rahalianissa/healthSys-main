<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch($locale)
    {
        $availableLocales = ['fr', 'ar', 'en'];
        
        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
            App::setLocale($locale);
            
            // Sauvegarder en base si utilisateur connecté
            if (auth()->check()) {
                auth()->user()->update(['language' => $locale]);
            }
        }
        
        return redirect()->back();
    }
    
    /**
     * Set language via AJAX
     */
    public function setLanguage(Request $request)
    {
        $request->validate([
            'lang' => 'required|in:fr,ar,en'
        ]);
        
        Session::put('locale', $request->lang);
        App::setLocale($request->lang);
        
        if (auth()->check()) {
            auth()->user()->update(['language' => $request->lang]);
        }
        
        return response()->json([
            'success' => true,
            'locale' => $request->lang,
            'direction' => $request->lang == 'ar' ? 'rtl' : 'ltr'
        ]);
    }
}