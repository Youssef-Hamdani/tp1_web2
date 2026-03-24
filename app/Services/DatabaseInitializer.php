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

    public function initialize(): void
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

        if ($this->shouldRefreshMonsters()) {
            $this->pdo->exec('TRUNCATE TABLE monsters');
        }

        $this->monsterRepository->seed(
            array(
                array(
                    'name' => 'Chihuahua',
                    'description' => 'Petit, nerveux et imprévisible.',
                    'image_path' => 'images/monsters/chihuahua.jpg',
                    'attack_power' => 22,
                    'armor' => 9,
                    'hp' => 24,
                ),
                array(
                    'name' => 'Shiba Inu',
                    'description' => 'Un rival majestueux à la mâchoire solide.',
                    'image_path' => 'images/monsters/shiba.jpg',
                    'attack_power' => 20,
                    'armor' => 12,
                    'hp' => 28,
                ),
                array(
                    'name' => 'Corgi',
                    'description' => 'Bas sur pattes, mais très résistant.',
                    'image_path' => 'images/monsters/corgi.jpg',
                    'attack_power' => 17,
                    'armor' => 15,
                    'hp' => 30,
                ),
                array(
                    'name' => 'Husky',
                    'description' => 'Rapide, bruyant et capable de gros dégâts.',
                    'image_path' => 'images/monsters/husky.jpg',
                    'attack_power' => 24,
                    'armor' => 10,
                    'hp' => 26,
                ),
            )
        );
    }

    private function shouldRefreshMonsters(): bool
    {
        $row = $this->pdo->query(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN image_path LIKE '%.svg' THEN 1 ELSE 0 END) AS svg_count
             FROM monsters"
        )->fetch();

        if ($row === false) {
            return true;
        }

        return (int) $row['total'] !== 4 || (int) $row['svg_count'] > 0;
    }
}
