<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

final class AnanasRoyal extends CharacterDefinition
{
    public function obtenirId(): string
    {
        return 'ananas';
    }

    public function obtenirNom(): string
    {
        return 'Ananas Royal';
    }

    public function obtenirTitre(): string
    {
        return 'Tank tropical';
    }

    public function obtenirCheminImage(): string
    {
        return 'images/characters/ananas.jpg';
    }

    public function obtenirForceBase(): int
    {
        return 11;
    }

    public function obtenirDefenseBase(): int
    {
        return 13;
    }

    public function obtenirVieBase(): int
    {
        return 30;
    }

    public function obtenirNomPouvoir(): string
    {
        return 'Couronne epineuse';
    }

    public function obtenirDescriptionPouvoir(): string
    {
        return 'Gagne +8 force et +8 defense pendant 3 tours, puis inflige 2 a 5 degats. Recharge: 4 tours.';
    }

    protected function obtenirEtatPouvoirInitial(): array
    {
        return array(
            'cooldown' => 0,
            'rage_tours' => 0,
            'ecorce_tours' => 0,
        );
    }

    public function obtenirBonusAttaque(array $player): int
    {
        return ((int) $player['power_state']['rage_tours'] > 0) ? 8 : 0;
    }

    public function obtenirBonusDefense(array $player): int
    {
        return ((int) $player['power_state']['ecorce_tours'] > 0) ? 8 : 0;
    }

    public function utiliserPouvoir(array &$player, array &$monster, CombatService $combatService): array
    {
        $player['power_state']['cooldown'] = 4;
        $player['power_state']['rage_tours'] = 3;
        $player['power_state']['ecorce_tours'] = 3;

        $impact = random_int(2, 5);
        $monster['hp'] = max(0, $monster['hp'] - $impact);

        return array(
            $this->creerJournal(
                'success',
                sprintf(
                    '%s libere sa couronne: +8 force, +8 defense pendant 3 tours et %d degats immediats.',
                    $player['name'],
                    $impact
                )
            ),
        );
    }
}
