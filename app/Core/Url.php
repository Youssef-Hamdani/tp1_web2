<?php

declare(strict_types=1);

namespace App\Core;

final class Url
{
    private const ROUTES = array(
        'accueil' => 'index.php',
        'connexion' => 'login.php',
        'inscription' => 'register.php',
        'personnages' => 'personnages.php',
        'portes' => 'portes.php',
        'ouvrir_porte' => 'ouvrir_porte.php',
        'combat' => 'combat.php',
        'api_combat' => 'api_combat.php',
        'fin' => 'fin.php',
        'rejouer' => 'rejouer.php',
        'deconnexion' => 'deconnexion.php',
    );

    public static function vers(string $route = 'accueil', array $params = array()): string
    {
        $cible = self::ROUTES[$route] ?? self::ROUTES['accueil'];

        if ($params === array()) {
            return $cible;
        }

        return $cible . '?' . http_build_query($params);
    }
}
