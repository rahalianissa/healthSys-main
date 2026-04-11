<?php

namespace App\Http\Controllers;

use App\Services\DeepLTranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected $translationService;
    
    public function __construct(DeepLTranslationService $translationService)
    {
        $this->translationService = $translationService;
        $this->middleware('auth');
    }
    
    /**
     * Traduire un texte
     */
    public function translateText(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'target_lang' => 'required|in:fr,ar,en,es,de,it'
        ]);
        
        $translated = $this->translationService->translate(
            $request->text,
            $request->target_lang
        );
        
        return response()->json([
            'success' => true,
            'original' => $request->text,
            'translated' => $translated,
            'target_lang' => $request->target_lang
        ]);
    }
    
    /**
     * Traduire plusieurs textes
     */
    public function translateBatch(Request $request)
    {
        $request->validate([
            'texts' => 'required|array',
            'target_lang' => 'required|in:fr,ar,en,es,de,it'
        ]);
        
        $translated = $this->translationService->translateArray(
            $request->texts,
            $request->target_lang
        );
        
        return response()->json([
            'success' => true,
            'translated' => $translated
        ]);
    }
    
    /**
     * Vérifier le statut de l'API
     */
    public function status()
    {
        return response()->json([
            'available' => $this->translationService->isAvailable()
        ]);
    }
}