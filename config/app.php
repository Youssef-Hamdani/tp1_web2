<?php

declare(strict_types=1);

return array(
    'app_name' => 'Chatssassins',
    'views_path' => __DIR__ . '/../app/Views',
    'database' => array(
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'u6269176_tp1',
        'user' => getenv('DB_USER') ?: 'u6269176_codexdb',
        'password' => getenv('DB_PASSWORD') ?: 'Tp1Codex6269176Db2026!',
        'charset' => 'utf8mb4',
    ),
);

