<?php

namespace App\Services;

use DeepL\Translator;
use Illuminate\Support\Facades\Cache;

class DeepLTranslationService
{
    protected $translator;
    protected $isAvailable = false;
    
    public function __construct()
    {
        $apiKey = env('DEEPL_API_KEY');
        if ($apiKey && $apiKey !== 'your-api-key-here') {
            try {
                $this->translator = new Translator($apiKey);
                $this->isAvailable = true;
            } catch (\Exception $e) {
                $this->isAvailable = false;
            }
        }
    }
    
    /**
     * Traduire un texte
     */
    public function translate($text, $targetLang, $sourceLang = 'fr')
    {
        if (!$this->isAvailable || empty($text)) {
            return $text;
        }
        
        // Vérifier le cache
        $cacheKey = 'translation_' . md5($text . $targetLang);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $targetLang = $this->normalizeLang($targetLang);
            $result = $this->translator->translateText($text, $sourceLang, $targetLang);
            
            // Sauvegarder en cache pour 30 jours
            Cache::put($cacheKey, $result->text, 60 * 24 * 30);
            
            return $result->text;
        } catch (\Exception $e) {
            \Log::error('DeepL Translation Error: ' . $e->getMessage());
            return $text;
        }
    }
    
    /**
     * Traduire un tableau de textes
     */
    public function translateArray($texts, $targetLang, $sourceLang = 'fr')
    {
        $translated = [];
        foreach ($texts as $key => $text) {
            if (is_array($text)) {
                $translated[$key] = $this->translateArray($text, $targetLang, $sourceLang);
            } else {
                $translated[$key] = $this->translate($text, $targetLang, $sourceLang);
            }
        }
        return $translated;
    }
    
    /**
     * Traduire une page entière (contenu HTML)
     */
    public function translateHtml($html, $targetLang)
    {
        if (!$this->isAvailable) {
            return $html;
        }
        
        try {
            $targetLang = $this->normalizeLang($targetLang);
            $result = $this->translator->translateText($html, 'fr', $targetLang, [
                'tag_handling' => 'html'
            ]);
            return $result->text;
        } catch (\Exception $e) {
            return $html;
        }
    }
    
    /**
     * Normaliser le code de langue pour DeepL
     */
    private function normalizeLang($lang)
    {
        $map = [
            'fr' => 'FR',
            'ar' => 'AR',
            'en' => 'EN-US',
            'es' => 'ES',
            'de' => 'DE',
            'it' => 'IT',
            'pt' => 'PT',
            'ru' => 'RU',
            'zh' => 'ZH',
            'ja' => 'JA',
            'ko' => 'KO',
            'tr' => 'TR',
            'nl' => 'NL',
            'pl' => 'PL'
        ];
        
        return $map[$lang] ?? 'EN';
    }
    
    /**
     * Vérifier si le service est disponible
     */
    public function isAvailable()
    {
        return $this->isAvailable;
    }
}