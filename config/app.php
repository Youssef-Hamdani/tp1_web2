<?php

declare(strict_types=1);

return array(
    'app_name' => 'Fruits en Furie',
    'views_path' => __DIR__ . '/../app/Views',
    'image_credits' => array(
        array(
            'label' => 'Fraise',
            'url' => 'https://commons.wikimedia.org/wiki/File:Closeup_of_a_strawberry.jpg',
        ),
        array(
            'label' => 'Banane',
            'url' => 'https://commons.wikimedia.org/wiki/File:Bananas_fruit.jpg',
        ),
        array(
            'label' => 'Ananas',
            'url' => 'https://commons.wikimedia.org/wiki/File:Pineapple_(Unsplash).jpg',
        ),
        array(
            'label' => 'Brocoli',
            'url' => 'https://commons.wikimedia.org/wiki/File:Broccoli.jpg',
        ),
        array(
            'label' => 'Citrouille',
            'url' => 'https://commons.wikimedia.org/wiki/File:Pumpkin.jpg',
        ),
        array(
            'label' => 'Piment',
            'url' => 'https://commons.wikimedia.org/wiki/File:Red_Chili_Pepper.jpg',
        ),
        array(
            'label' => 'Aubergine',
            'url' => 'https://commons.wikimedia.org/wiki/File:Eggplant.jpg',
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
