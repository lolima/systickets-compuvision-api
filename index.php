<?php
header('Content-Type: application/json; charset=utf-8');


ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
header("Access-Control-Allow-Origin: *");

// DEBUG
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// FIM DEBUG

header("Access-Control-Allow-Origin: *");
include __DIR__ . '/vendor/autoload.php';

date_default_timezone_set("America/Sao_Paulo");

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'debug' => true,
    ],
]);

$environment = new Dotenv\Dotenv(__DIR__, '.env');
$environment->load();

$container = $app->getContainer();
$container["secret"] = getenv('SECRET');


require __DIR__ . "/config/middleware.php";
require __DIR__ . "/config/logger.php";
require __DIR__ . "/config/database.php";

/**********************
 * V1      *
 *********************/
require __DIR__ . "/v1.php";


$app->get('/', function () {
    echo 'TICKETS API - v1';
});

$app->get('/home', function () {
    echo 'TICKETS API -  v1';
});

$stmt = $conn->prepare("SET time_zone = 'America/Sao_Paulo'");
$stmt->execute();

$app->run();
