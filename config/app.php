<?php

declare(strict_types=1);

return array(
    'app_name' => 'Nuit des Neuf Vies',
    'views_path' => __DIR__ . '/../app/Views',
    'image_credits' => array(
        array(
            'label' => 'Minuit',
            'url' => 'https://commons.wikimedia.org/wiki/File:American_shorthair_cat_Portrait.jpg',
        ),
        array(
            'label' => 'Safran',
            'url' => 'https://commons.wikimedia.org/wiki/File:Orange_cat_PHOTO.jpg',
        ),
        array(
            'label' => 'Nova',
            'url' => 'https://commons.wikimedia.org/wiki/File:Closeup_of_a_black_cat.jpg',
        ),
        array(
            'label' => 'Chihuahua',
            'url' => 'https://commons.wikimedia.org/wiki/File:Chihuahua_dog.jpg',
        ),
        array(
            'label' => 'Corgi',
            'url' => 'https://commons.wikimedia.org/wiki/File:Welsh_Pembroke_Corgi.jpg',
        ),
        array(
            'label' => 'Husky',
            'url' => 'https://commons.wikimedia.org/wiki/File:Siberian-husky.jpg',
        ),
        array(
            'label' => 'Shiba Inu',
            'url' => 'https://commons.wikimedia.org/wiki/File:Shiba_Inu.jpg',
        ),
    ),
    'database' => array(
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'u6269176_tp1',
        'user' => getenv('DB_USER') ?: 'u6269176_codexdb',
        'password' => getenv('DB_PASSWORD') ?: 'Tp1Codex6269176Db2026!',
        'charset' => 'utf8mb4',
    ),
);
