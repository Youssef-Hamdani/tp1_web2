<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$config = require __DIR__ . '/config/app.php';
$application = new App\Core\Application($config);
$application->handle();

