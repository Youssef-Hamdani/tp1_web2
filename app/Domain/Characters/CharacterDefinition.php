<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

abstract class CharacterDefinition
{
    abstract public function obtenirId(): string;

    abstract public function obtenirNom(): string;

    abstract public function obtenirTitre(): string;

    abstract public function obtenirCheminImage(): string;

    abstract public function obtenirForceBase(): int;

    abstract public function obtenirDefenseBase(): int;

    abstract public function obtenirVieBase(): int;

    abstract public function obtenirNomPouvoir(): string;

    abstract public function obtenirDescriptionPouvoir(): string;

    abstract protected function obtenirEtatPouvoirInitial(): array;

    abstract public function utiliserPouvoir(array &$player, array &$monster, CombatService $combatService): array;

    public function creerEtat(): array
    {
        return array(
            'character_id' => $this->obtenirId(),
            'name' => $this->obtenirNom(),
            'title' => $this->obtenirTitre(),
            'image' => $this->obtenirCheminImage(),
            'force' => $this->obtenirForceBase(),
            'defense' => $this->obtenirDefenseBase(),
            'hp' => $this->obtenirVieBase(),
            'max_hp' => $this->obtenirVieBase(),
            'power_name' => $this->obtenirNomPouvoir(),
            'power_description' => $this->obtenirDescriptionPouvoir(),
            'power_state' => $this->obtenirEtatPouvoirInitial(),
        );
    }

    public function peutUtiliserPouvoir(array $player): bool
    {
        $powerState = $player['power_state'];
        $cooldown = (int) ($powerState['cooldown'] ?? 0);

        if ($cooldown > 0) {
            return false;
        }

        if (isset($powerState['charges']) && (int) $powerState['charges'] <= 0) {
            return false;
        }

        return $player['hp'] > 0;
    }

    public function obtenirBonusAttaque(array $player): int
    {
        return 0;
    }

    public function obtenirBonusDefense(array $player): int
    {
        return 0;
    }

    public function apresTour(array &$player): void
    {
        foreach ($player['power_state'] as $key => $value) {
            if (! is_int($value) || $value <= 0) {
                continue;
            }

            if (str_ends_with($key, '_tours') || $key === 'cooldown') {
                $player['power_state'][$key]--;
            }
        }
    }

    protected function creerJournal(string $tone, string $text): array
    {
        return array(
            'tone' => $tone,
            'text' => $text,
        );
    }
}
