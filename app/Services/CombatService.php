<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Characters\CharacterDefinition;

final class CombatService
{
    public function processTurn(array $game, CharacterDefinition $character, string $action): array
    {
        $player = &$game['player'];
        $monster = &$game['combat']['monster'];
        $logs = array();

        if ($action === 'power') {
            if ($character->canUsePower($player)) {
                $logs = array_merge($logs, $character->usePower($player, $monster, $this));
            } else {
                $logs[] = array(
                    'tone' => 'danger',
                    'text' => 'Le pouvoir n\'est pas disponible pour le moment.',
                );
            }
        } else {
            $details = $this->resolveAttack($player, $monster, $character->getAttackBonus($player), 0);
            $logs[] = array(
                'tone' => 'success',
                'text' => sprintf(
                    '%s lance %d, %s bloque %d, %s.',
                    $player['name'],
                    $details['attack'],
                    $monster['name'],
                    $details['guard'],
                    $this->formatDamage($details['damage'])
                ),
            );
        }

        if ($monster['hp'] > 0) {
            $monsterDetails = $this->resolveAttack($monster, $player, 0, $character->getDefenseBonus($player));
            $logs[] = array(
                'tone' => 'danger',
                'text' => sprintf(
                    '%s lance %d, %s bloque %d, %s.',
                    $monster['name'],
                    $monsterDetails['attack'],
                    $player['name'],
                    $monsterDetails['guard'],
                    $this->formatDamage($monsterDetails['damage'])
                ),
            );
        }

        $character->afterRound($player);
        $game['combat']['logs'] = array_slice(array_merge($logs, $game['combat']['logs']), 0, 4);

        if ($monster['hp'] <= 0) {
            $game['status'] = 'finished';
            $game['result'] = array(
                'outcome' => 'victory',
                'title' => 'Victoire féline',
                'message' => sprintf('%s a vaincu %s et règne désormais sur le couloir.', $player['name'], $monster['name']),
            );
        } elseif ($player['hp'] <= 0) {
            $game['status'] = 'finished';
            $game['result'] = array(
                'outcome' => 'defeat',
                'title' => 'Défaite',
                'message' => sprintf('%s a été renversé par %s.', $player['name'], $monster['name']),
            );
        }

        return $game;
    }

    public function resolveAttack(array $attacker, array &$defender, int $attackBonus, int $guardBonus): array
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

    public function formatDamage(int $damage): string
    {
        if ($damage <= 0) {
            return 'aucun dégât';
        }

        return sprintf('%d dégâts', $damage);
    }
}
