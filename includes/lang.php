<?php
/**
 * CodeReview.pl - i18n System
 * Simple translation management
 */

class Language {
    private static array $translations = [];
    private static string $currentLang = 'pl';
    private const ALLOWED_LANGS = ['pl', 'en'];
    private const COOKIE_NAME = 'site_lang';

    public static function init(): void {
        // 1. Check URL param
        if (isset($_GET['lang']) && in_array($_GET['lang'], self::ALLOWED_LANGS)) {
            self::$currentLang = $_GET['lang'];
            setcookie(self::COOKIE_NAME, self::$currentLang, time() + (86400 * 30), '/');
        } 
        // 2. Check cookie
        elseif (isset($_COOKIE[self::COOKIE_NAME]) && in_array($_COOKIE[self::COOKIE_NAME], self::ALLOWED_LANGS)) {
            self::$currentLang = $_COOKIE[self::COOKIE_NAME];
        }
        // 3. Check browser language
        elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($browserLang, self::ALLOWED_LANGS)) {
                self::$currentLang = $browserLang;
            }
        }

        self::loadTranslations();
    }

    private static function loadTranslations(): void {
        $file = __DIR__ . '/../lang/' . self::$currentLang . '.php';
        if (file_exists($file)) {
            self::$translations = require $file;
        }
    }

    public static function get(string $key, array $replace = []) {
        $value = self::$translations[$key] ?? $key;
        
        if (is_array($value)) {
            return $value;
        }

        foreach ($replace as $k => $v) {
            $value = str_replace(':' . $k, $v, $value);
        }
        
        return $value;
    }

    public static function getCurrent(): string {
        return self::$currentLang;
    }

    public static function getLangs(): array {
        return self::ALLOWED_LANGS;
    }
}

/**
 * Helper function for translations
 */
function __ (string $key, array $replace = []) {
    return Language::get($key, $replace);
}
