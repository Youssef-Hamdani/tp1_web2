<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$config = require __DIR__ . '/config/app.php';
$application = new App\Core\Application($config);

if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) === 'POST') {
    $application->auth()->traiterInscription();

    return;
}

$application->auth()->afficherInscription();
