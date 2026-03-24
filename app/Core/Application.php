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
        $view = new View($config['views_path'], $config['app_name']);
        $flash = new Flash();
        $this->gameSession = new GameSessionService();
        $characterCatalog = new CharacterCatalog();
        $userRepository = new UserRepository($pdo);
        $monsterRepository = new MonsterRepository($pdo);
        $databaseInitializer = new DatabaseInitializer($pdo, $monsterRepository);
        $databaseInitializer->initialize();
        $combatService = new CombatService();

        $this->authController = new AuthController($view, $flash, $this->gameSession, $userRepository);
        $this->gameController = new GameController($view, $flash, $this->gameSession, $characterCatalog, $monsterRepository);
        $this->combatApiController = new CombatApiController($view, $flash, $this->gameSession, $combatService, $characterCatalog);
    }

    public function handle(): void
    {
        $page = (string) ($_GET['page'] ?? '');
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

        if ($page === '') {
            if (! $this->gameSession->isAuthenticated()) {
                $this->authController->showLogin();

                return;
            }

            header('Location: ' . url($this->gameSession->currentGamePage()));
            exit;
        }

        if ($page === 'login' && $method === 'GET') {
            $this->authController->showLogin();

            return;
        }

        if ($page === 'login' && $method === 'POST') {
            $this->authController->login();

            return;
        }

        if ($page === 'register' && $method === 'GET') {
            $this->authController->showRegister();

            return;
        }

        if ($page === 'register' && $method === 'POST') {
            $this->authController->register();

            return;
        }

        if ($page === 'logout' && $method === 'POST') {
            $this->authController->logout();

            return;
        }

        if ($page === 'character' && $method === 'GET') {
            $this->gameController->showCharacterSelection();

            return;
        }

        if ($page === 'character' && $method === 'POST') {
            $this->gameController->chooseCharacter();

            return;
        }

        if ($page === 'doors' && $method === 'GET') {
            $this->gameController->showDoors();

            return;
        }

        if ($page === 'open-door' && $method === 'POST') {
            $this->gameController->openDoor();

            return;
        }

        if ($page === 'combat' && $method === 'GET') {
            $this->gameController->showCombat();

            return;
        }

        if ($page === 'api-combat' && $method === 'POST') {
            $this->combatApiController->turn();

            return;
        }

        if ($page === 'end' && $method === 'GET') {
            $this->gameController->showEnd();

            return;
        }

        if ($page === 'replay' && $method === 'POST') {
            $this->gameController->replay();

            return;
        }

        http_response_code(404);
        echo 'Page introuvable.';
    }
}
