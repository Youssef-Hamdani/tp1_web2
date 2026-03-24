<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\View;
use App\Domain\Characters\CharacterCatalog;
use App\Services\CombatService;
use App\Services\GameSessionService;

final class CombatApiController extends Controller
{
    private CombatService $combatService;
    private CharacterCatalog $characterCatalog;

    public function __construct(
        View $view,
        Flash $flash,
        GameSessionService $gameSession,
        CombatService $combatService,
        CharacterCatalog $characterCatalog
    ) {
        parent::__construct($view, $flash, $gameSession);
        $this->combatService = $combatService;
        $this->characterCatalog = $characterCatalog;
    }

    public function turn(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (! $this->gameSession->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(array('ok' => false, 'message' => 'Authentification requise.'));

            return;
        }

        $game = $this->gameSession->getGame();

        if ($game === null || ($game['status'] ?? '') !== 'in_combat') {
            http_response_code(403);
            echo json_encode(array('ok' => false, 'message' => 'Aucun combat actif.'));

            return;
        }

        $character = $this->characterCatalog->find($game['player']['character_id']);

        if ($character === null) {
            http_response_code(500);
            echo json_encode(array('ok' => false, 'message' => 'Personnage introuvable.'));

            return;
        }

        $action = (string) ($_POST['action'] ?? 'attack');
        $updatedGame = $this->combatService->processTurn($game, $character, $action === 'power' ? 'power' : 'attack');
        $this->gameSession->saveGame($updatedGame);

        echo json_encode(
            array(
                'ok' => true,
                'finished' => ($updatedGame['status'] ?? '') === 'finished',
                'redirectUrl' => ($updatedGame['status'] ?? '') === 'finished' ? url('end') : null,
                'player' => $updatedGame['player'],
                'monster' => $updatedGame['combat']['monster'],
                'logs' => $updatedGame['combat']['logs'],
                'powerAvailable' => $character->canUsePower($updatedGame['player']),
            )
        );
    }
}

