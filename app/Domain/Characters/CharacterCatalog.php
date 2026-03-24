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
            'simon' => new SimonCat(),
            'nyan' => new NyanCat(),
            'nonono' => new NononoCat(),
        );
    }

    public function all(): array
    {
        return $this->characters;
    }

    public function find(string $id): ?CharacterDefinition
    {
        return $this->characters[$id] ?? null;
    }
}

