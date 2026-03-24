<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?User
    {
        $statement = $this->pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1');
        $statement->execute(array('username' => $username));
        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return new User((int) $row['id'], (string) $row['username'], (string) $row['password_hash']);
    }

    public function create(string $username, string $passwordHash): User
    {
        $statement = $this->pdo->prepare('INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)');
        $statement->execute(
            array(
                'username' => $username,
                'password_hash' => $passwordHash,
            )
        );

        return new User((int) $this->pdo->lastInsertId(), $username, $passwordHash);
    }
}

