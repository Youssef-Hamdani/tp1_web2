<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

final class FraiseFurie extends CharacterDefinition
{
    public function obtenirId(): string
    {
        return 'fraise';
    }

    public function obtenirNom(): string
    {
        return 'Fraise Furie';
    }

    public function obtenirTitre(): string
    {
        return 'Eclair rouge';
    }

    public function obtenirCheminImage(): string
    {
        return 'images/characters/fraise.jpg';
    }

    public function obtenirForceBase(): int
    {
        return 15;
    }

    public function obtenirDefenseBase(): int
    {
        return 9;
    }

    public function obtenirVieBase(): int
    {
        return 26;
    }

    public function obtenirNomPouvoir(): string
    {
        return 'Coulis critique';
    }

    public function obtenirDescriptionPouvoir(): string
    {
        return 'Une frappe explosive avec +18 attaque et un soin de 4 PV. 2 utilisations.';
    }

    protected function obtenirEtatPouvoirInitial(): array
    {
        return array(
            'cooldown' => 0,
            'charges' => 2,
        );
    }

    public function utiliserPouvoir(array &$player, array &$monster, CombatService $combatService): array
    {
        $player['power_state']['charges']--;

        $details = $combatService->resoudreAttaque($player, $monster, 18, 0);
        $before = $player['hp'];
        $player['hp'] = min($player['max_hp'], $player['hp'] + 4);
        $healed = $player['hp'] - $before;

        return array(
            $this->creerJournal(
                'success',
                sprintf(
                    'Coulis critique: %s lance %d, %s bloque %d, %s. +%d PV.',
                    $player['name'],
                    $details['attack'],
                    $monster['name'],
                    $details['guard'],
                    $combatService->formaterDegats($details['damage']),
                    $healed
                )
            ),
        );
    }
}
