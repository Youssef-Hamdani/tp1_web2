<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return '/public/assets/' . ltrim($path, '/');
}

function url(string $page = '', array $params = array()): string
{
    $query = $params;

    if ($page !== '') {
        $query = array_merge(array('page' => $page), $params);
    }

    if ($query === array()) {
        return 'index.php';
    }

    return 'index.php?' . http_build_query($query);
}

