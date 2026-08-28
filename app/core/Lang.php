<?php

class Lang {
    protected static $translations = [];
    protected static $currentLang = null;

    public static function init() {
        if (self::$currentLang !== null) {
            return;
        }

        // 1. Check GET parameter (e.g. ?lang=tr or ?lang=en)
        if (isset($_GET['lang']) && in_array(strtolower($_GET['lang']), ['tr', 'en'])) {
            $_SESSION['lang'] = strtolower($_GET['lang']);
            setcookie('lang', $_SESSION['lang'], time() + (86400 * 30), "/");
        }

        // 2. Determine active language from Session, Cookie or Default (tr)
        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], ['tr', 'en'])) {
            self::$currentLang = $_SESSION['lang'];
        } elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['tr', 'en'])) {
            self::$currentLang = $_COOKIE['lang'];
            $_SESSION['lang'] = self::$currentLang;
        } else {
            self::$currentLang = 'tr'; // Default language
            $_SESSION['lang'] = 'tr';
        }

        // Load language files
        $langFile = APP_DIR . "/languages/" . self::$currentLang . ".php";
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        } else {
            self::$translations = [];
        }
    }

    public static function get($key, $params = []) {
        self::init();

        $text = isset(self::$translations[$key]) ? self::$translations[$key] : $key;

        if (!empty($params) && is_array($params)) {
            foreach ($params as $k => $v) {
                $text = str_replace(':' . $k, $v, $text);
                $text = str_replace('{' . $k . '}', $v, $text);
            }
        }

        return $text;
    }

    public static function current() {
        self::init();
        return self::$currentLang;
    }

    public static function isTr() {
        return self::current() === 'tr';
    }

    public static function isEn() {
        return self::current() === 'en';
    }
}

// Global translation helper function
function __($key, $params = []) {
    return Lang::get($key, $params);
}
?>
