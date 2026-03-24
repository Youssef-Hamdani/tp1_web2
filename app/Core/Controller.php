<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\GameSessionService;

abstract class Controller
{
    protected View $view;
    protected Flash $flash;
    protected GameSessionService $gameSession;

    public function __construct(View $view, Flash $flash, GameSessionService $gameSession)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->gameSession = $gameSession;
    }

    protected function afficherVue(string $template, array $data = array()): void
    {
        $data['flashMessages'] = $this->flash->retirer();
        $data['isAuthenticated'] = $this->gameSession->estAuthentifie();
        $data['currentUser'] = $this->gameSession->obtenirUtilisateurCourant();

        $this->view->afficher($template, $data);
    }

    protected function rediriger(string $route = 'accueil', array $params = array()): void
    {
        header('Location: ' . Url::vers($route, $params));
        exit;
    }

    protected function exigerAuthentification(): void
    {
        if (! $this->gameSession->estAuthentifie()) {
            $this->flash->ajouter('error', 'Veuillez vous connecter pour continuer.');
            $this->rediriger('connexion');
        }
    }

    protected function exigerInvite(): void
    {
        if ($this->gameSession->estAuthentifie()) {
            $this->rediriger($this->gameSession->obtenirPageCouranteJeu());
        }
    }
}
