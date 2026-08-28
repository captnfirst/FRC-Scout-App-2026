<?php

define('APP_DIR', dirname(__DIR__));
define('CORE', APP_DIR . '/core');
define('CONFIG', APP_DIR . '/config');

global $config;

$config = array(
    "authentication" => array(
        "auth_urls" => array(
            "default" => "/default/login",
        ),
        "auth_files" => array(
            "default" => "admin",
        )
    ),
    "smtp" => array(
        "host"       => "smtp.gmail.com",
        "port"       => 587,
        "username"   => "",
        "password"   => "",
        "encryption" => "tls",
        "from_email" => "noreply@frcskor.com",
        "from_name"  => "FRC SCOUT APP"
    ),
    "debug" => "yes"
);

// Load Local / Production Secret Overrides if present (Ignored by Git)
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

// Fallback Default Database Settings if not defined in config.local.php
if (!defined('HOST'))   define("HOST", "localhost");
if (!defined('USER'))   define("USER", "root");
if (!defined('PASS'))   define("PASS", "");
if (!defined('DBNAME')) define("DBNAME", "score");

require_once CORE . "/Model.php";
require_once CORE . "/Controller.php";
require_once CORE . "/View.php";
require_once CORE . "/App.php";
require_once CORE . "/TBA.php";
require_once CORE . "/Lang.php";
require_once CONFIG . "/routing.php";
require_once APP_DIR . "/vendor/autoload.php";

spl_autoload_register(function ($class_name){
    $module = explode("Model", $class_name);
    if(file_exists($file = APP_DIR . "/modules/{$module[0]}/model/{$class_name}.php"))
        require_once $file;

    if(file_exists($file = CORE . "/interface/{$class_name}.php"))
        require_once $file;
});

function fatal_handler()
{
    global $config;

    $error = error_get_last();
    if($error != NULL)
    {
        if($config['debug'] == "yes")
        {
            var_dump($error);
        }
        elseif($config['debug'] == "no" && $error['type'] != 8192)
        {
            echo "<h3 style='text-align: center; color:red'>System Error</h3>";
        }
    }
}

register_shutdown_function("fatal_handler");

?>