<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Characters\CharacterDefinition;
use App\Models\User;

final class GameSessionService
{
    private const USER_KEY = 'auth_user';
    private const GAME_KEY = 'active_game';
    private const GAME_VERSION = 2;

    public function isAuthenticated(): bool
    {
        return isset($_SESSION[self::USER_KEY]);
    }

    public function login(User $user): void
    {
        $_SESSION[self::USER_KEY] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
        );

        session_regenerate_id(true);
    }

    public function logout(): void
    {
        unset($_SESSION[self::USER_KEY], $_SESSION[self::GAME_KEY], $_SESSION['_flash_messages']);
        session_regenerate_id(true);
    }

    public function getCurrentUser(): ?array
    {
        return $_SESSION[self::USER_KEY] ?? null;
    }

    public function hasGame(): bool
    {
        return isset($_SESSION[self::GAME_KEY]);
    }

    public function getGame(): ?array
    {
        $game = $_SESSION[self::GAME_KEY] ?? null;

        if ($game !== null && (($game['version'] ?? 0) !== self::GAME_VERSION)) {
            $this->clearGame();

            return null;
        }

        return $game;
    }

    public function saveGame(array $game): void
    {
        $_SESSION[self::GAME_KEY] = $game;
    }

    public function clearGame(): void
    {
        unset($_SESSION[self::GAME_KEY]);
    }

    public function currentGamePage(): string
    {
        if (! $this->isAuthenticated()) {
            return 'login';
        }

        $game = $this->getGame();

        if ($game === null) {
            return 'character';
        }

        if (($game['status'] ?? 'doors') === 'in_combat') {
            return 'combat';
        }

        if (($game['status'] ?? 'doors') === 'finished') {
            return 'end';
        }

        return 'doors';
    }

    public function startNewGame(CharacterDefinition $character): array
    {
        $game = array(
            'version' => self::GAME_VERSION,
            'status' => 'doors',
            'player' => $character->createState(),
            'doors' => $this->generateDoors(),
            'combat' => null,
            'result' => null,
            'feedback' => null,
        );

        $this->saveGame($game);

        return $game;
    }

    public function consumeFeedback(array &$game): ?array
    {
        $feedback = $game['feedback'] ?? null;
        $game['feedback'] = null;

        return $feedback;
    }

    private function generateDoors(): array
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
                'effect' => $type === 'combat' ? null : $this->generateEffect($type),
            );
        }

        return $doors;
    }

    private function generateEffect(string $type): array
    {
        if ($type === 'bonus') {
            $effects = array(
                array('mode' => 'heal', 'amount' => random_int(4, 7), 'headline' => 'Bol de lait'),
                array('mode' => 'force', 'amount' => random_int(2, 4), 'headline' => 'Croquettes épiques'),
                array('mode' => 'defense', 'amount' => random_int(2, 4), 'headline' => 'Coussin blindé'),
                array('mode' => 'max_hp', 'amount' => random_int(3, 5), 'headline' => 'Sieste réparatrice'),
            );

            return $effects[array_rand($effects)];
        }

        $effects = array(
            array('mode' => 'damage', 'amount' => random_int(3, 6), 'headline' => 'Piège à litière'),
            array('mode' => 'force', 'amount' => -random_int(1, 3), 'headline' => 'Collier encombrant'),
            array('mode' => 'defense', 'amount' => -random_int(1, 3), 'headline' => 'Bain humiliant'),
            array('mode' => 'damage', 'amount' => random_int(2, 4), 'headline' => 'Aspiration surprise'),
        );

        return $effects[array_rand($effects)];
    }
}
