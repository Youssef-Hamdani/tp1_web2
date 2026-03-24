<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$application = App\Core\Application::depuisEnvironnement();

if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) === 'POST') {
    $application->jeu()->choisirPersonnage();

    return;
}

$application->jeu()->afficherSelectionPersonnage();
