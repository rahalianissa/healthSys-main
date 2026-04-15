<?php

if (!function_exists('__t')) {
    function __t($key, $replace = [])
    {
        return __("messages.{$key}", $replace);
    }
}

if (!function_exists('getCurrentLocale')) {
    function getCurrentLocale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('getLocaleFlag')) {
    function getLocaleFlag()
    {
        $locale = getCurrentLocale();
        switch ($locale) {
            case 'fr': return '🇫🇷';
            case 'ar': return '🇸🇦';
            case 'en': return '🇬🇧';
            default: return '🌐';
        }
    }
}

if (!function_exists('getLocaleName')) {
    function getLocaleName()
    {
        $locale = getCurrentLocale();
        switch ($locale) {
            case 'fr': return 'Français';
            case 'ar': return 'العربية';
            case 'en': return 'English';
            default: return 'Language';
        }
    }
}

if (!function_exists('isRtl')) {
    function isRtl()
    {
        return getCurrentLocale() === 'ar';
    }
}

if (!function_exists('getDirection')) {
    function getDirection()
    {
        return isRtl() ? 'rtl' : 'ltr';
    }
}

if (!function_exists('getTextAlign')) {
    function getTextAlign()
    {
        return isRtl() ? 'right' : 'left';
    }
}