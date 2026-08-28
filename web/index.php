<?php
session_start();

$possible_paths = [
    __DIR__ . "/app/config/config.php",
    __DIR__ . "/../app/config/config.php",
    __DIR__ . "/deploy/app/config/config.php",
    dirname(__DIR__) . "/app/config/config.php",
    $_SERVER['DOCUMENT_ROOT'] . "/app/config/config.php"
];

$loaded = false;
foreach ($possible_paths as $p) {
    if (file_exists($p)) {
        require_once $p;
        $loaded = true;
        break;
    }
}

if (!$loaded) {
    echo "<h3>Configuration file could not be loaded.</h3>";
    echo "<strong>Current Directory:</strong> " . __DIR__ . "<br>";
    echo "<strong>Files and Folders in this Directory:</strong><pre>";
    print_r(scandir(__DIR__));
    echo "</pre>";
    die();
}

$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$app = new App($path, $method, $config);
$app->run();
?>
