<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", "On");
ini_set("display_startup_errors", "On");
date_default_timezone_set("Europe/London");

session_start();

// TODO: setup the production domain

$stagingDomain = 'playground.mattimatti.com';

$isStaging = (strpos($_SERVER['HTTP_HOST'], $stagingDomain) !== false);

if ($isStaging) {
    $settings = require __DIR__ . '/../src/staging.php';
} else {
    $settings = require __DIR__ . '/../src/production.php';
}

// Instantiate the app

// print_r($settings);
// exit();

$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
