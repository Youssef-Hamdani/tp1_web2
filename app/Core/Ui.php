<?php

declare(strict_types=1);

namespace App\Core;

final class Ui
{
    public function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public function asset(string $path): string
    {
        return '/public/assets/' . ltrim($path, '/');
    }

    public function url(string $page = '', array $params = array()): string
    {
        return Url::page($page, $params);
    }
}

