<?php

declare(strict_types=1);

namespace App\Core;

final class Ui
{
    public function echapper(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public function ressource(string $path): string
    {
        return '/public/assets/' . ltrim($path, '/');
    }

    public function lien(string $route = 'accueil', array $params = array()): string
    {
        return Url::vers($route, $params);
    }
}
