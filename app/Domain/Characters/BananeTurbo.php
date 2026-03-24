<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

final class BananeTurbo extends CharacterDefinition
{
    public function obtenirId(): string
    {
        return 'banane';
    }

    public function obtenirNom(): string
    {
        return 'Banane Turbo';
    }

    public function obtenirTitre(): string
    {
        return 'Sprinteuse doree';
    }

    public function obtenirCheminImage(): string
    {
        return 'images/characters/banane.jpg';
    }

    public function obtenirForceBase(): int
    {
        return 13;
    }

    public function obtenirDefenseBase(): int
    {
        return 11;
    }

    public function obtenirVieBase(): int
    {
        return 24;
    }

    public function obtenirNomPouvoir(): string
    {
        return 'Glissade eclair';
    }

    public function obtenirDescriptionPouvoir(): string
    {
        return 'Gagne +14 defense pendant 2 tours et recupere 4 PV. Recharge: 3 tours.';
    }

    protected function obtenirEtatPouvoirInitial(): array
    {
        return array(
            'cooldown' => 0,
            'garde_tours' => 0,
        );
    }

    public function obtenirBonusDefense(array $player): int
    {
        return ((int) $player['power_state']['garde_tours'] > 0) ? 14 : 0;
    }

    public function utiliserPouvoir(array &$player, array &$monster, CombatService $combatService): array
    {
        $player['power_state']['cooldown'] = 3;
        $player['power_state']['garde_tours'] = 2;

        $before = $player['hp'];
        $player['hp'] = min($player['max_hp'], $player['hp'] + 4);
        $healed = $player['hp'] - $before;

        return array(
            $this->creerJournal(
                'success',
                sprintf('%s derape juste a temps: +14 defense pendant 2 tours et +%d PV.', $player['name'], $healed)
            ),
        );
    }
}
