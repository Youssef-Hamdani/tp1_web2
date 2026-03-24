<?php

declare(strict_types=1);

namespace App\Core;

final class Url
{
    public static function page(string $page = '', array $params = array()): string
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
}

