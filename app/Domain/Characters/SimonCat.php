<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

final class SimonCat extends CharacterDefinition
{
    public function getId(): string
    {
        return 'simon';
    }

    public function getName(): string
    {
        return 'Minuit';
    }

    public function getTitle(): string
    {
        return 'Ombre rapide';
    }

    public function getImagePath(): string
    {
        return 'images/characters/minuit.jpg';
    }

    public function getBaseForce(): int
    {
        return 13;
    }

    public function getBaseDefense(): int
    {
        return 11;
    }

    public function getBaseHp(): int
    {
        return 24;
    }

    public function getPowerName(): string
    {
        return 'Pas nocturne';
    }

    public function getPowerDescription(): string
    {
        return 'Gagne +14 défense pendant 2 tours et récupère 4 PV. Recharge: 3 tours.';
    }

    protected function getInitialPowerState(): array
    {
        return array(
            'cooldown' => 0,
            'guard_turns' => 0,
        );
    }

    public function getDefenseBonus(array $player): int
    {
        return ((int) $player['power_state']['guard_turns'] > 0) ? 14 : 0;
    }

    public function usePower(array &$player, array &$monster, CombatService $combatService): array
    {
        $player['power_state']['cooldown'] = 3;
        $player['power_state']['guard_turns'] = 2;

        $before = $player['hp'];
        $player['hp'] = min($player['max_hp'], $player['hp'] + 4);
        $healed = $player['hp'] - $before;

        return array(
            $this->buildLog(
                'success',
                sprintf('%s anticipe l\'attaque: +14 défense pendant 2 tours et +%d PV.', $player['name'], $healed)
            ),
        );
    }
}
