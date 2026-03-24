<?php

declare(strict_types=1);

namespace App\Domain\Characters;

final class CharacterCatalog
{
    /**
     * @var array<string, CharacterDefinition>
     */
    private array $characters;

    public function __construct()
    {
        $this->characters = array(
            'fraise' => new FraiseFurie(),
            'banane' => new BananeTurbo(),
            'ananas' => new AnanasRoyal(),
        );
    }

    public function tous(): array
    {
        return $this->characters;
    }

    public function trouver(string $id): ?CharacterDefinition
    {
        return $this->characters[$id] ?? null;
    }
}
