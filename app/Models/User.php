<?php

declare(strict_types=1);

namespace App\Models;

final class User
{
    private int $id;
    private string $username;
    private string $passwordHash;

    public function __construct(int $id, string $username, string $passwordHash)
    {
        $this->id = $id;
        $this->username = $username;
        $this->passwordHash = $passwordHash;
    }

    public function obtenirId(): int
    {
        return $this->id;
    }

    public function obtenirNomUtilisateur(): string
    {
        return $this->username;
    }

    public function obtenirMotDePasseHache(): string
    {
        return $this->passwordHash;
    }
}
