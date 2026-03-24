<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$config = require __DIR__ . '/config/app.php';
$application = new App\Core\Application($config);

if (! $application->sessionJeu()->estAuthentifie()) {
    header('Location: ' . App\Core\Url::vers('connexion'));
    exit;
}

header('Location: ' . App\Core\Url::vers($application->sessionJeu()->obtenirPageCouranteJeu()));
exit;
