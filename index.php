<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$application = App\Core\Application::depuisEnvironnement();

if (! $application->sessionJeu()->estAuthentifie()) {
    header('Location: ' . App\Core\Url::vers('connexion'));
    exit;
}

header('Location: ' . App\Core\Url::vers($application->sessionJeu()->obtenirPageCouranteJeu()));
exit;
