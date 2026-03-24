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

    public function afficherSelectionPersonnage(): void
    {
        $this->exigerAuthentification();

        if ($this->gameSession->aUnePartie()) {
            $this->rediriger($this->gameSession->obtenirPageCouranteJeu());
        }

        $this->afficherVue(
            'game.character-select',
            array(
                'title' => 'Choisir un fruit',
                'characters' => $this->characterCatalog->tous(),
            )
        );
    }

    public function choisirPersonnage(): void
    {
        $this->exigerAuthentification();

        $characterId = (string) ($_POST['character_id'] ?? '');
        $character = $this->characterCatalog->trouver($characterId);

        if (! $character instanceof CharacterDefinition) {
            $this->flash->ajouter('error', 'Veuillez choisir un fruit valide.');
            $this->rediriger('personnages');
        }

        $this->gameSession->demarrerNouvellePartie($character);
        $this->rediriger('portes');
    }

    public function afficherPortes(): void
    {
        $this->exigerAuthentification();
        $game = $this->exigerPartie('doors');
        $feedback = $this->gameSession->consommerRetour($game);
        $this->gameSession->sauvegarderPartie($game);

        $this->afficherVue(
            'game.doors',
            array(
                'title' => 'Choisir une porte',
                'game' => $game,
                'feedback' => $feedback,
            )
        );
    }

    public function ouvrirPorte(): void
    {
        $this->exigerAuthentification();
        $game = $this->exigerPartie('doors');
        $doorNumber = (int) ($_POST['door_number'] ?? 0);
        $doorIndex = $this->trouverIndexPorte($game['doors'], $doorNumber);

        if ($doorIndex === -1) {
            $this->flash->ajouter('error', 'Cette porte n\'existe pas.');
            $this->rediriger('portes');
        }

        if ($game['doors'][$doorIndex]['opened']) {
            $this->flash->ajouter('error', 'Cette porte a deja ete ouverte.');
            $this->rediriger('portes');
        }

        $game['doors'][$doorIndex]['opened'] = true;

        if ($game['doors'][$doorIndex]['type'] === 'combat') {
            $monster = $this->monsterRepository->trouverAuHasard();
            $monsterData = $monster->versTableau();
            $game['status'] = 'in_combat';
            $game['combat'] = array(
                'monster' => $monsterData,
                'logs' => array(
                    array(
                        'tone' => 'danger',
                        'text' => sprintf('La porte s\'ouvre: %s surgit et reclame un duel.', $monsterData['name']),
                    ),
                ),
            );
            $game['feedback'] = null;
            $this->gameSession->sauvegarderPartie($game);
            $this->rediriger('combat');
        }

        $game['feedback'] = $this->appliquerEffetPorte($game, $game['doors'][$doorIndex]['effect']);
        $this->gameSession->sauvegarderPartie($game);
        $this->rediriger('portes');
    }

    public function afficherCombat(): void
    {
        $this->exigerAuthentification();
        $game = $this->exigerPartie('in_combat');
        $character = $this->characterCatalog->trouver($game['player']['character_id']);

        $this->afficherVue(
            'game.combat',
            array(
                'title' => 'Combat',
                'game' => $game,
                'character' => $character,
            )
        );
    }

    public function afficherFin(): void
    {
        $this->exigerAuthentification();
        $game = $this->exigerPartie('finished');

        $this->afficherVue(
            'game.end',
            array(
                'title' => 'Fin de partie',
                'game' => $game,
            )
        );
    }

    public function recommencer(): void
    {
        $this->exigerAuthentification();
        $this->gameSession->effacerPartie();
        $this->rediriger('personnages');
    }

    private function exigerPartie(string $expectedStatus): array
    {
        $game = $this->gameSession->obtenirPartie();

        if ($game === null) {
            $this->flash->ajouter('error', 'Commencez une nouvelle partie.');
            $this->rediriger('personnages');
        }

        $status = $game['status'] ?? 'doors';

        if ($status !== $expectedStatus) {
            $this->rediriger($this->gameSession->obtenirPageCouranteJeu());
        }

        return $game;
    }

    private function trouverIndexPorte(array $doors, int $doorNumber): int
    {
        foreach ($doors as $index => $door) {
            if ((int) $door['number'] === $doorNumber) {
                return (int) $index;
            }
        }

        return -1;
    }

    private function appliquerEffetPorte(array &$game, array $effect): array
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
            'text' => sprintf('%s: %s%d defense (Defense: %d).', $headline, $amount > 0 ? '+' : '', $amount, $player['defense']),
        );
    }
}
