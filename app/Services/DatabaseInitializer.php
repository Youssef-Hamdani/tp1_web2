<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MonsterRepository;
use PDO;

final class DatabaseInitializer
{
    private PDO $pdo;
    private MonsterRepository $monsterRepository;

    public function __construct(PDO $pdo, MonsterRepository $monsterRepository)
    {
        $this->pdo = $pdo;
        $this->monsterRepository = $monsterRepository;
    }

    public function initialiser(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS monsters (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(80) NOT NULL,
                description VARCHAR(160) NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                attack_power INT UNSIGNED NOT NULL,
                armor INT UNSIGNED NOT NULL,
                hp INT UNSIGNED NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        if ($this->doitActualiserMonstres()) {
            $this->pdo->exec('TRUNCATE TABLE monsters');
            $this->monsterRepository->ensemencer(
                array(
                    array(
                        'name' => 'Brocoli Brutal',
                        'description' => 'Toujours pret a encaisser et a repousser les charges.',
                        'image_path' => 'images/monsters/brocoli.jpg',
                        'attack_power' => 17,
                        'armor' => 15,
                        'hp' => 30,
                    ),
                    array(
                        'name' => 'Citrouille Colossale',
                        'description' => 'Massive, lente et tres difficile a fissurer.',
                        'image_path' => 'images/monsters/citrouille.jpg',
                        'attack_power' => 19,
                        'armor' => 14,
                        'hp' => 32,
                    ),
                    array(
                        'name' => 'Piment Feroce',
                        'description' => 'Petit, vif et capable de piquer tres fort.',
                        'image_path' => 'images/monsters/piment.jpg',
                        'attack_power' => 24,
                        'armor' => 10,
                        'hp' => 25,
                    ),
                    array(
                        'name' => 'Aubergine Nocturne',
                        'description' => 'Elegante, sombre et pleine de surprises.',
                        'image_path' => 'images/monsters/aubergine.jpg',
                        'attack_power' => 21,
                        'armor' => 12,
                        'hp' => 28,
                    ),
                )
            );
        }
    }

    private function doitActualiserMonstres(): bool
    {
        $row = $this->pdo->query(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN name IN ('Chihuahua', 'Shiba Inu', 'Corgi', 'Husky') THEN 1 ELSE 0 END) AS old_count
             FROM monsters"
        )->fetch();

        if ($row === false) {
            return true;
        }

        return (int) $row['total'] !== 4 || (int) $row['old_count'] > 0;
    }
}
