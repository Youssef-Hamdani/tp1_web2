<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\CombatApiController;
use App\Controllers\GameController;
use App\Domain\Characters\CharacterCatalog;
use App\Models\MonsterRepository;
use App\Models\UserRepository;
use App\Services\CombatService;
use App\Services\DatabaseInitializer;
use App\Services\GameSessionService;

final class Application
{
    private AuthController $authController;
    private GameController $gameController;
    private CombatApiController $combatApiController;
    private GameSessionService $gameSession;

    public function __construct(array $config)
    {
        $pdo = Database::connect($config['database']);
        $view = new View($config['views_path'], $config['app_name'], $config['image_credits'] ?? array());
        $flash = new Flash();
        $this->gameSession = new GameSessionService();
        $characterCatalog = new CharacterCatalog();
        $userRepository = new UserRepository($pdo);
        $monsterRepository = new MonsterRepository($pdo);
        $databaseInitializer = new DatabaseInitializer($pdo, $monsterRepository);
        $databaseInitializer->initialiser();
        $combatService = new CombatService();

        $this->authController = new AuthController($view, $flash, $this->gameSession, $userRepository);
        $this->gameController = new GameController($view, $flash, $this->gameSession, $characterCatalog, $monsterRepository);
        $this->combatApiController = new CombatApiController($view, $flash, $this->gameSession, $combatService, $characterCatalog);
    }

    public static function depuisEnvironnement(): self
    {
        return new self(
            array(
                'app_name' => 'fruityloops',
                'views_path' => dirname(__DIR__) . '/Views',
                'image_credits' => array(
                    array(
                        'label' => 'Fraise',
                        'url' => 'https://commons.wikimedia.org/wiki/File:Closeup_of_a_strawberry.jpg',
                    ),
                    array(
                        'label' => 'Banane',
                        'url' => 'https://commons.wikimedia.org/wiki/File:Bananas_fruit.jpg',
                    ),
                    array(
                        'label' => 'Ananas',
                        'url' => 'https://commons.wikimedia.org/wiki/File:Pineapple_(Unsplash).jpg',
                    ),
                    array(
                        'label' => 'Brocoli',
                        'url' => 'https://commons.wikimedia.org/wiki/File:Broccoli.jpg',
                    ),
                    array(
                        'label' => 'Citrouille',
                        'url' => 'https://commons.wikimedia.org/wiki/File:Pumpkin.jpg',
                    ),
                    array(
                        'label' => 'Piment',
                        'url' => 'https://commons.wikimedia.org/wiki/File:Red_Chili_Pepper.jpg',
                    ),
                    array(
                        'label' => 'Aubergine',
                        'url' => 'https://commons.wikimedia.org/wiki/File:Eggplant.jpg',
                    ),
                ),
                'database' => array(
                    'host' => getenv('DB_HOST') ?: 'localhost',
                    'port' => (int) (getenv('DB_PORT') ?: 3306),
                    'name' => getenv('DB_NAME') ?: 'u6269176_tp1',
                    'user' => getenv('DB_USER') ?: 'u6269176_codexdb',
                    'password' => getenv('DB_PASSWORD') ?: 'Tp1Codex6269176Db2026!',
                    'charset' => 'utf8mb4',
                ),
            )
        );
    }

    public function auth(): AuthController
    {
        return $this->authController;
    }

    public function jeu(): GameController
    {
        return $this->gameController;
    }

    public function apiCombat(): CombatApiController
    {
        return $this->combatApiController;
    }

    public function sessionJeu(): GameSessionService
    {
        return $this->gameSession;
    }
}
