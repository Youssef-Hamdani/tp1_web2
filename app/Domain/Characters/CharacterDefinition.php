<?php

declare(strict_types=1);

namespace App\Domain\Characters;

use App\Services\CombatService;

abstract class CharacterDefinition
{
    abstract public function getId(): string;

    abstract public function getName(): string;

    abstract public function getTitle(): string;

    abstract public function getImagePath(): string;

    abstract public function getBaseForce(): int;

    abstract public function getBaseDefense(): int;

    abstract public function getBaseHp(): int;

    abstract public function getPowerName(): string;

    abstract public function getPowerDescription(): string;

    abstract protected function getInitialPowerState(): array;

    abstract public function usePower(array &$player, array &$monster, CombatService $combatService): array;

    public function createState(): array
    {
        return array(
            'character_id' => $this->getId(),
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'image' => $this->getImagePath(),
            'force' => $this->getBaseForce(),
            'defense' => $this->getBaseDefense(),
            'hp' => $this->getBaseHp(),
            'max_hp' => $this->getBaseHp(),
            'power_name' => $this->getPowerName(),
            'power_description' => $this->getPowerDescription(),
            'power_state' => $this->getInitialPowerState(),
        );
    }

    public function canUsePower(array $player): bool
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

    public function getAttackBonus(array $player): int
    {
        return 0;
    }

    public function getDefenseBonus(array $player): int
    {
        return 0;
    }

    public function afterRound(array &$player): void
    {
        foreach ($player['power_state'] as $key => $value) {
            if (is_int($value) && $value > 0 && (strpos($key, '_turns') !== false || $key === 'cooldown')) {
                $player['power_state'][$key]--;
            }
        }
    }

    protected function buildLog(string $tone, string $text): array
    {
        return array(
            'tone' => $tone,
            'text' => $text,
        );
    }
}

