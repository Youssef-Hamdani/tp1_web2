<?php

declare(strict_types=1);

namespace App\Models;

final class Monster
{
    private int $id;
    private string $name;
    private string $description;
    private string $imagePath;
    private int $force;
    private int $defense;
    private int $hp;

    public function __construct(
        int $id,
        string $name,
        string $description,
        string $imagePath,
        int $force,
        int $defense,
        int $hp
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->force = $force;
        $this->defense = $defense;
        $this->hp = $hp;
    }

    public function versTableau(): array
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->imagePath,
            'force' => $this->force,
            'defense' => $this->defense,
            'hp' => $this->hp,
            'max_hp' => $this->hp,
        );
    }
}
