<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

final class NononoCat extends CharacterDefinition
{
    public function getId(): string
    {
        return 'nonono';
    }

    public function getName(): string
    {
        return 'Nonono Cat';
    }

    public function getTitle(): string
    {
        return 'Féroce et tenace';
    }

    public function getImagePath(): string
    {
        return 'images/characters/nonono-cat.svg';
    }

    public function getBaseForce(): int
    {
        return 11;
    }

    public function getBaseDefense(): int
    {
        return 13;
    }

    public function getBaseHp(): int
    {
        return 30;
    }

    public function getPowerName(): string
    {
        return 'Miaulement obstiné';
    }

    public function getPowerDescription(): string
    {
        return 'Gagne +8 force et +8 défense pendant 3 tours, puis griffe pour 2 à 5 dégâts. Recharge: 4 tours.';
    }

    protected function getInitialPowerState(): array
    {
        return array(
            'cooldown' => 0,
            'rage_turns' => 0,
            'fortify_turns' => 0,
        );
    }

    public function getAttackBonus(array $player): int
    {
        return ((int) $player['power_state']['rage_turns'] > 0) ? 8 : 0;
    }

    public function getDefenseBonus(array $player): int
    {
        return ((int) $player['power_state']['fortify_turns'] > 0) ? 8 : 0;
    }

    public function usePower(array &$player, array &$monster, CombatService $combatService): array
    {
        $player['power_state']['cooldown'] = 4;
        $player['power_state']['rage_turns'] = 3;
        $player['power_state']['fortify_turns'] = 3;

        $scratch = random_int(2, 5);
        $monster['hp'] = max(0, $monster['hp'] - $scratch);

        return array(
            $this->buildLog(
                'success',
                sprintf(
                    '%s pousse un miaulement furieux: +8 force, +8 défense pendant 3 tours et %d dégâts immédiats.',
                    $player['name'],
                    $scratch
                )
            ),
        );
    }
}

