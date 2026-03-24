<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

final class NyanCat extends CharacterDefinition
{
    public function getId(): string
    {
        return 'nyan';
    }

    public function getName(): string
    {
        return 'Nova';
    }

    public function getTitle(): string
    {
        return 'Étoile filante';
    }

    public function getImagePath(): string
    {
        return 'images/characters/nova.jpg';
    }

    public function getBaseForce(): int
    {
        return 15;
    }

    public function getBaseDefense(): int
    {
        return 9;
    }

    public function getBaseHp(): int
    {
        return 26;
    }

    public function getPowerName(): string
    {
        return 'Éclair astral';
    }

    public function getPowerDescription(): string
    {
        return 'Une frappe puissante avec +18 attaque et un soin de 4 PV. 2 utilisations.';
    }

    protected function getInitialPowerState(): array
    {
        return array(
            'cooldown' => 0,
            'charges' => 2,
        );
    }

    public function usePower(array &$player, array &$monster, CombatService $combatService): array
    {
        $player['power_state']['charges']--;

        $details = $combatService->resolveAttack($player, $monster, 18, 0);
        $before = $player['hp'];
        $player['hp'] = min($player['max_hp'], $player['hp'] + 4);
        $healed = $player['hp'] - $before;

        return array(
            $this->buildLog(
                'success',
                sprintf(
                    'Arc-en-ciel supersonique: %s lance %d, %s bloque %d, %s. +%d PV.',
                    $player['name'],
                    $details['attack'],
                    $monster['name'],
                    $details['guard'],
                    $combatService->formatDamage($details['damage']),
                    $healed
                )
            ),
        );
    }
}
