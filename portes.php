<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$application = App\Core\Application::depuisEnvironnement();
$application->jeu()->afficherPortes();
