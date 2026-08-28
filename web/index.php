<?php
session_start();

$possible_paths = [
    __DIR__ . "/../app/config/config.php",
    __DIR__ . "/app/config/config.php",
    dirname(__DIR__) . "/app/config/config.php",
    $_SERVER['DOCUMENT_ROOT'] . "/app/config/config.php",
    $_SERVER['DOCUMENT_ROOT'] . "/score/app/config/config.php"
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
    die();
}

$rawPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalize path for local subfolder installations (e.g. /score/ or /score/web/)
$subfolders = ['/score/web', '/score'];
$path = $rawPath;
foreach ($subfolders as $sub) {
    if (strpos($path, $sub) === 0) {
        $path = substr($path, strlen($sub));
        break;
    }
}

if (empty($path) || $path === '') {
    $path = '/';
}

$method = $_SERVER['REQUEST_METHOD'];

$app = new App($path, $method, $config);
$app->run();
?>
