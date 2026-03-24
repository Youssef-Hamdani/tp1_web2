<?php

declare(strict_types=1);

date_default_timezone_set('America/Toronto');
error_reporting(E_ALL);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . '/Core/Autoloader.php';

$autoloader = new App\Core\Autoloader(__DIR__);
$autoloader->register();
