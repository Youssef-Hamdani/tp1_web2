<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Characters\CharacterDefinition;
use App\Models\User;

final class GameSessionService
{
    private const USER_KEY = 'auth_user';
    private const GAME_KEY = 'active_game';
    private const GAME_VERSION = 3;

    public function estAuthentifie(): bool
    {
        return isset($_SESSION[self::USER_KEY]);
    }

    public function ouvrirSessionUtilisateur(User $user): void
    {
        $_SESSION[self::USER_KEY] = array(
            'id' => $user->obtenirId(),
            'username' => $user->obtenirNomUtilisateur(),
        );

        session_regenerate_id(true);
    }

    public function fermerSessionUtilisateur(): void
    {
        unset($_SESSION[self::USER_KEY], $_SESSION[self::GAME_KEY], $_SESSION['_flash_messages']);
        session_regenerate_id(true);
    }

    public function obtenirUtilisateurCourant(): ?array
    {
        return $_SESSION[self::USER_KEY] ?? null;
    }

    public function aUnePartie(): bool
    {
        return isset($_SESSION[self::GAME_KEY]);
    }

    public function obtenirPartie(): ?array
    {
        $game = $_SESSION[self::GAME_KEY] ?? null;

        if ($game !== null && (($game['version'] ?? 0) !== self::GAME_VERSION)) {
            $this->effacerPartie();

            return null;
        }

        return $game;
    }

    public function sauvegarderPartie(array $game): void
    {
        $_SESSION[self::GAME_KEY] = $game;
    }

    public function effacerPartie(): void
    {
        unset($_SESSION[self::GAME_KEY]);
    }

    public function obtenirPageCouranteJeu(): string
    {
        if (! $this->estAuthentifie()) {
            return 'connexion';
        }

        $game = $this->obtenirPartie();

        if ($game === null) {
            return 'personnages';
        }

        if (($game['status'] ?? 'doors') === 'in_combat') {
            return 'combat';
        }

        if (($game['status'] ?? 'doors') === 'finished') {
            return 'fin';
        }

        return 'portes';
    }

    public function demarrerNouvellePartie(CharacterDefinition $character): array
    {
        $game = array(
            'version' => self::GAME_VERSION,
            'status' => 'doors',
            'player' => $character->creerEtat(),
            'doors' => $this->genererPortes(),
            'combat' => null,
            'result' => null,
            'feedback' => null,
        );

        $this->sauvegarderPartie($game);

        return $game;
    }

    public function consommerRetour(array &$game): ?array
    {
        $feedback = $game['feedback'] ?? null;
        $game['feedback'] = null;

        return $feedback;
    }

    private function genererPortes(): array
    {
        $types = array('combat', 'bonus', 'bonus', 'malus', 'malus');
        $variantRoll = random_int(1, 10);

        if ($variantRoll === 1) {
            $types = array('combat', 'bonus', 'bonus', 'bonus', 'malus');
        } elseif ($variantRoll === 2) {
            $types = array('combat', 'bonus', 'malus', 'malus', 'malus');
        }

        shuffle($types);

        $doors = array();

        foreach ($types as $index => $type) {
            $doors[] = array(
                'number' => $index + 1,
                'type' => $type,
                'opened' => false,
                'effect' => $type === 'combat' ? null : $this->genererEffet($type),
            );
        }

        return $doors;
    }

    private function genererEffet(string $type): array
    {
        if ($type === 'bonus') {
            $effects = array(
                array('mode' => 'heal', 'amount' => random_int(4, 7), 'headline' => 'Smoothie vitamine'),
                array('mode' => 'force', 'amount' => random_int(2, 4), 'headline' => 'Shot tropical'),
                array('mode' => 'defense', 'amount' => random_int(2, 4), 'headline' => 'Peau renforcee'),
                array('mode' => 'max_hp', 'amount' => random_int(3, 5), 'headline' => 'Reserve de jus'),
            );

            return $effects[array_rand($effects)];
        }

        $effects = array(
            array('mode' => 'damage', 'amount' => random_int(3, 6), 'headline' => 'Hachoir glissant'),
            array('mode' => 'force', 'amount' => -random_int(1, 3), 'headline' => 'Gel du frigo'),
            array('mode' => 'defense', 'amount' => -random_int(1, 3), 'headline' => 'Pelure fragile'),
            array('mode' => 'damage', 'amount' => random_int(2, 4), 'headline' => 'Mixeur surprise'),
        );

        return $effects[array_rand($effects)];
    }
}
