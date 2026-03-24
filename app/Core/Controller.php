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

    protected function render(string $template, array $data = array()): void
    {
        $data['flashMessages'] = $this->flash->pull();
        $data['isAuthenticated'] = $this->gameSession->isAuthenticated();
        $data['currentUser'] = $this->gameSession->getCurrentUser();

        $this->view->render($template, $data);
    }

    protected function redirect(string $page = '', array $params = array()): void
    {
        header('Location: ' . Url::page($page, $params));
        exit;
    }

    protected function requireAuthentication(): void
    {
        if (! $this->gameSession->isAuthenticated()) {
            $this->flash->add('error', 'Veuillez vous connecter pour continuer.');
            $this->redirect('login');
        }
    }

    protected function requireGuest(): void
    {
        if ($this->gameSession->isAuthenticated()) {
            $this->redirect($this->gameSession->currentGamePage());
        }
    }
}
