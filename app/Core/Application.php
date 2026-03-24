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
