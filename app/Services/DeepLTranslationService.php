<?php

namespace App\Services;

use DeepL\Translator;
use Illuminate\Support\Facades\Log;

class DeepLTranslationService
{
    protected $translator;
    protected $authKey;

    public function __construct()
    {
        $this->authKey = config('services.deepl.key');
        if ($this->authKey) {
            $this->translator = new Translator($this->authKey);
        }
    }

    /**
     * Traduire un texte vers une langue cible
     * 
     * @param string $text Texte à traduire
     * @param string $targetLang Langue cible (ex: 'fr', 'en', 'ar')
     * @return string Texte traduit
     */
    public function translate(string $text, string $targetLang): string
    {
        if (!$this->translator) {
            return $text;
        }

        try {
            // Mapping for common languages if needed (DeepL uses 'EN-GB' or 'EN-US' etc.)
            $targetLang = $this->mapLanguageCode($targetLang);
            
            $result = $this->translator->translateText($text, null, $targetLang);
            return $result->text;
        } catch (\Exception $e) {
            Log::error('DeepL Translation Error: ' . $e->getMessage());
            return $text;
        }
    }

    /**
     * Traduire un tableau de textes
     */
    public function translateArray(array $texts, string $targetLang): array
    {
        if (!$this->translator || empty($texts)) {
            return $texts;
        }

        try {
            $targetLang = $this->mapLanguageCode($targetLang);
            $results = $this->translator->translateText($texts, null, $targetLang);
            
            return array_map(fn($res) => $res->text, $results);
        } catch (\Exception $e) {
            Log::error('DeepL Batch Translation Error: ' . $e->getMessage());
            return $texts;
        }
    }

    /**
     * Vérifier si le service est disponible
     */
    public function isAvailable(): bool
    {
        return $this->translator !== null;
    }

    /**
     * Mapper les codes de langue pour DeepL
     */
    protected function mapLanguageCode(string $code): string
    {
        $code = strtoupper($code);
        if ($code === 'EN') return 'EN-US';
        return $code;
    }
}
