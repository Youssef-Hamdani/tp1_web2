<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\View;
use App\Domain\Characters\CharacterCatalog;
use App\Domain\Characters\CharacterDefinition;
use App\Models\MonsterRepository;
use App\Services\GameSessionService;

final class GameController extends Controller
{
    private CharacterCatalog $characterCatalog;
    private MonsterRepository $monsterRepository;

    public function __construct(
        View $view,
        Flash $flash,
        GameSessionService $gameSession,
        CharacterCatalog $characterCatalog,
        MonsterRepository $monsterRepository
    ) {
        parent::__construct($view, $flash, $gameSession);
        $this->characterCatalog = $characterCatalog;
        $this->monsterRepository = $monsterRepository;
    }

    public function showCharacterSelection(): void
    {
        $this->requireAuthentication();

        if ($this->gameSession->hasGame()) {
            $this->redirect($this->gameSession->currentGamePage());
        }

        $this->render(
            'game.character-select',
            array(
                'title' => 'Choisir un personnage',
                'characters' => $this->characterCatalog->all(),
            )
        );
    }

    public function chooseCharacter(): void
    {
        $this->requireAuthentication();

        $characterId = (string) ($_POST['character_id'] ?? '');
        $character = $this->characterCatalog->find($characterId);

        if (! $character instanceof CharacterDefinition) {
            $this->flash->add('error', 'Veuillez choisir un personnage valide.');
            $this->redirect('character');
        }

        $this->gameSession->startNewGame($character);
        $this->redirect('doors');
    }

    public function showDoors(): void
    {
        $this->requireAuthentication();
        $game = $this->requireGame('doors');
        $feedback = $this->gameSession->consumeFeedback($game);
        $this->gameSession->saveGame($game);

        $this->render(
            'game.doors',
            array(
                'title' => 'Choisir une porte',
                'game' => $game,
                'feedback' => $feedback,
            )
        );
    }

    public function openDoor(): void
    {
        $this->requireAuthentication();
        $game = $this->requireGame('doors');
        $doorNumber = (int) ($_POST['door_number'] ?? 0);
        $doorIndex = $this->findDoorIndex($game['doors'], $doorNumber);

        if ($doorIndex === -1) {
            $this->flash->add('error', 'Cette porte n\'existe pas.');
            $this->redirect('doors');
        }

        if ($game['doors'][$doorIndex]['opened']) {
            $this->flash->add('error', 'Cette porte a déjà été ouverte.');
            $this->redirect('doors');
        }

        $game['doors'][$doorIndex]['opened'] = true;

        if ($game['doors'][$doorIndex]['type'] === 'combat') {
            $monster = $this->monsterRepository->findRandom();
            $monsterData = $monster->toArray();
            $game['status'] = 'in_combat';
            $game['combat'] = array(
                'monster' => $monsterData,
                'logs' => array(
                    array(
                        'tone' => 'danger',
                        'text' => sprintf('La porte s\'ouvre: %s surgit et réclame un duel.', $monsterData['name']),
                    ),
                ),
            );
            $game['feedback'] = null;
            $this->gameSession->saveGame($game);
            $this->redirect('combat');
        }

        $game['feedback'] = $this->applyDoorEffect($game, $game['doors'][$doorIndex]['effect']);
        $this->gameSession->saveGame($game);
        $this->redirect('doors');
    }

    public function showCombat(): void
    {
        $this->requireAuthentication();
        $game = $this->requireGame('in_combat');
        $character = $this->characterCatalog->find($game['player']['character_id']);

        $this->render(
            'game.combat',
            array(
                'title' => 'Combat',
                'game' => $game,
                'character' => $character,
            )
        );
    }

    public function showEnd(): void
    {
        $this->requireAuthentication();
        $game = $this->requireGame('finished');

        $this->render(
            'game.end',
            array(
                'title' => 'Fin de partie',
                'game' => $game,
            )
        );
    }

    public function replay(): void
    {
        $this->requireAuthentication();
        $this->gameSession->clearGame();
        $this->redirect('character');
    }

    private function requireGame(string $expectedStatus): array
    {
        $game = $this->gameSession->getGame();

        if ($game === null) {
            $this->flash->add('error', 'Commencez une nouvelle partie.');
            $this->redirect('character');
        }

        $status = $game['status'] ?? 'doors';

        if ($status !== $expectedStatus) {
            $this->redirect($this->gameSession->currentGamePage());
        }

        return $game;
    }

    private function findDoorIndex(array $doors, int $doorNumber): int
    {
        foreach ($doors as $index => $door) {
            if ((int) $door['number'] === $doorNumber) {
                return (int) $index;
            }
        }

        return -1;
    }

    private function applyDoorEffect(array &$game, array $effect): array
    {
        $player = &$game['player'];
        $mode = $effect['mode'];
        $amount = (int) $effect['amount'];
        $headline = (string) $effect['headline'];

        if ($mode === 'heal') {
            $before = $player['hp'];
            $player['hp'] = min($player['max_hp'], $player['hp'] + $amount);
            $gain = $player['hp'] - $before;

            return array(
                'tone' => 'success',
                'text' => sprintf('%s: +%d PV (PV: %d/%d).', $headline, $gain, $player['hp'], $player['max_hp']),
            );
        }

        if ($mode === 'max_hp') {
            $player['max_hp'] += $amount;
            $player['hp'] = min($player['max_hp'], $player['hp'] + $amount);

            return array(
                'tone' => 'success',
                'text' => sprintf('%s: +%d vie maximale (PV: %d/%d).', $headline, $amount, $player['hp'], $player['max_hp']),
            );
        }

        if ($mode === 'damage') {
            $player['hp'] = max(1, $player['hp'] - $amount);

            return array(
                'tone' => 'danger',
                'text' => sprintf('%s: -%d PV (PV: %d/%d).', $headline, $amount, $player['hp'], $player['max_hp']),
            );
        }

        if ($mode === 'force') {
            $player['force'] = max(4, $player['force'] + $amount);

            return array(
                'tone' => $amount > 0 ? 'success' : 'danger',
                'text' => sprintf('%s: %s%d force (Force: %d).', $headline, $amount > 0 ? '+' : '', $amount, $player['force']),
            );
        }

        $player['defense'] = max(4, $player['defense'] + $amount);

        return array(
            'tone' => $amount > 0 ? 'success' : 'danger',
            'text' => sprintf('%s: %s%d défense (Défense: %d).', $headline, $amount > 0 ? '+' : '', $amount, $player['defense']),
        );
    }
}
