<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class LangHelper
{
    /**
     * Get current language direction (ltr/rtl)
     */
    public static function getDirection()
    {
        $locale = App::getLocale();
        return in_array($locale, ['ar', 'fa', 'he']) ? 'rtl' : 'ltr';
    }
    
    /**
     * Get current language code
     */
    public static function getCurrentLocale()
    {
        return App::getLocale();
    }
    
    /**
     * Get available languages
     */
    public static function getAvailableLocales()
    {
        return [
            'fr' => ['name' => 'Français', 'flag' => '🇫🇷', 'dir' => 'ltr'],
            'ar' => ['name' => 'العربية', 'flag' => '🇸🇦', 'dir' => 'rtl'],
            'en' => ['name' => 'English', 'flag' => '🇬🇧', 'dir' => 'ltr'],
        ];
    }
    
    /**
     * Format date according to locale
     */
    public static function formatDate($date, $format = null)
    {
        if (!$date) return null;
        
        $locale = App::getLocale();
        
        if ($format === null) {
            $format = $locale === 'fr' ? 'd/m/Y' : ($locale === 'ar' ? 'Y/m/d' : 'Y-m-d');
        }
        
        if ($date instanceof \Carbon\Carbon) {
            return $date->locale($locale)->translatedFormat($format);
        }
        
        return \Carbon\Carbon::parse($date)->locale($locale)->translatedFormat($format);
    }
    
    /**
     * Format datetime according to locale
     */
    public static function formatDateTime($date)
    {
        if (!$date) return null;
        
        $locale = App::getLocale();
        $dateFormat = $locale === 'fr' ? 'd/m/Y H:i' : ($locale === 'ar' ? 'Y/m/d H:i' : 'Y-m-d H:i');
        
        if ($date instanceof \Carbon\Carbon) {
            return $date->locale($locale)->translatedFormat($dateFormat);
        }
        
        return \Carbon\Carbon::parse($date)->locale($locale)->translatedFormat($dateFormat);
    }
}