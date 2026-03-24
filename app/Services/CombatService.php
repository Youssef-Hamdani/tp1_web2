<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Characters\CharacterDefinition;

final class CombatService
{
    public function traiterTour(array $game, CharacterDefinition $character, string $action): array
    {
        $player = &$game['player'];
        $monster = &$game['combat']['monster'];
        $logs = array();

        if ($action === 'power') {
            if ($character->peutUtiliserPouvoir($player)) {
                $logs = array_merge($logs, $character->utiliserPouvoir($player, $monster, $this));
            } else {
                $logs[] = array(
                    'tone' => 'danger',
                    'text' => 'Le pouvoir n\'est pas disponible pour le moment.',
                );
            }
        } else {
            $details = $this->resoudreAttaque($player, $monster, $character->obtenirBonusAttaque($player), 0);
            $logs[] = array(
                'tone' => 'success',
                'text' => sprintf(
                    '%s lance %d, %s bloque %d, %s.',
                    $player['name'],
                    $details['attack'],
                    $monster['name'],
                    $details['guard'],
                    $this->formaterDegats($details['damage'])
                ),
            );
        }

        if ($monster['hp'] > 0) {
            $monsterDetails = $this->resoudreAttaque($monster, $player, 0, $character->obtenirBonusDefense($player));
            $logs[] = array(
                'tone' => 'danger',
                'text' => sprintf(
                    '%s lance %d, %s bloque %d, %s.',
                    $monster['name'],
                    $monsterDetails['attack'],
                    $player['name'],
                    $monsterDetails['guard'],
                    $this->formaterDegats($monsterDetails['damage'])
                ),
            );
        }

        $character->apresTour($player);
        $game['combat']['logs'] = array_slice(array_merge($logs, $game['combat']['logs']), 0, 4);

        if ($monster['hp'] <= 0) {
            $game['status'] = 'finished';
            $game['result'] = array(
                'outcome' => 'victory',
                'title' => 'Victoire juteuse',
                'message' => sprintf('%s a ecrase %s et prend le controle du marche.', $player['name'], $monster['name']),
            );
        } elseif ($player['hp'] <= 0) {
            $game['status'] = 'finished';
            $game['result'] = array(
                'outcome' => 'defeat',
                'title' => 'Defaite',
                'message' => sprintf('%s a ete renverse par %s.', $player['name'], $monster['name']),
            );
        }

        return $game;
    }

    public function resoudreAttaque(array $attacker, array &$defender, int $attackBonus, int $guardBonus): array
    {
        $attack = $attacker['force'] + $attackBonus + random_int(1, 12);
        $guard = $defender['defense'] + $guardBonus + random_int(1, 12);
        $damage = max(0, $attack - $guard);

        $defender['hp'] = max(0, $defender['hp'] - $damage);

        return array(
            'attack' => $attack,
            'guard' => $guard,
            'damage' => $damage,
        );
    }

    public function formaterDegats(int $damage): string
    {
        if ($damage <= 0) {
            return 'aucun degat';
        }

        return sprintf('%d degats', $damage);
    }
}
