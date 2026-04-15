<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class ConfigHelper
{
    /**
     * Get current locale direction
     */
    public static function getDirection()
    {
        $locale = App::getLocale();
        return config("app.available_locales.{$locale}.dir", 'ltr');
    }
    
    /**
     * Get current locale flag
     */
    public static function getFlag()
    {
        $locale = App::getLocale();
        return config("app.available_locales.{$locale}.flag", '🌐');
    }
    
    /**
     * Get current locale name
     */
    public static function getLocaleName()
    {
        $locale = App::getLocale();
        return config("app.available_locales.{$locale}.name", 'Language');
    }
    
    /**
     * Get all available locales
     */
    public static function getAvailableLocales()
    {
        return config('app.available_locales', []);
    }
}