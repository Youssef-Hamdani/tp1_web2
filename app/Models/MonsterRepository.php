<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use RuntimeException;

final class MonsterRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function count(): int
    {
        $result = $this->pdo->query('SELECT COUNT(*) AS count FROM monsters')->fetch();

        return (int) $result['count'];
    }

    public function seed(array $monsters): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO monsters (name, description, image_path, attack_power, armor, hp)
             VALUES (:name, :description, :image_path, :attack_power, :armor, :hp)'
        );

        foreach ($monsters as $monster) {
            $statement->execute($monster);
        }
    }

    public function findRandom(): Monster
    {
        $row = $this->pdo->query(
            'SELECT id, name, description, image_path, attack_power, armor, hp
             FROM monsters
             ORDER BY RAND()
             LIMIT 1'
        )->fetch();

        if ($row === false) {
            throw new RuntimeException('Aucun monstre disponible dans la base de données.');
        }

        return new Monster(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['description'],
            (string) $row['image_path'],
            (int) $row['attack_power'],
            (int) $row['armor'],
            (int) $row['hp']
        );
    }
}
