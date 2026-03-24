<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\View;
use App\Models\UserRepository;
use App\Services\GameSessionService;

final class AuthController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(
        View $view,
        Flash $flash,
        GameSessionService $gameSession,
        UserRepository $userRepository
    ) {
        parent::__construct($view, $flash, $gameSession);
        $this->userRepository = $userRepository;
    }

    public function showLogin(array $data = array()): void
    {
        $this->requireGuest();

        $this->render(
            'auth.login',
            array_merge(
                array(
                    'title' => 'Connexion',
                    'old' => array('username' => ''),
                ),
                $data
            )
        );
    }

    public function login(): void
    {
        $this->requireGuest();

        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $this->showLogin(
                array(
                    'error' => 'Veuillez remplir tous les champs.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        $user = $this->userRepository->findByUsername($username);

        if ($user === null || ! password_verify($password, $user->getPasswordHash())) {
            $this->showLogin(
                array(
                    'error' => 'Nom d\'utilisateur ou mot de passe invalide.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        $this->gameSession->login($user);
        $this->flash->add('success', sprintf('Bon retour, %s.', $user->getUsername()));
        $this->redirect($this->gameSession->currentGamePage());
    }

    public function showRegister(array $data = array()): void
    {
        $this->requireGuest();

        $this->render(
            'auth.register',
            array_merge(
                array(
                    'title' => 'Créer un compte',
                    'old' => array('username' => ''),
                ),
                $data
            )
        );
    }

    public function register(): void
    {
        $this->requireGuest();

        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['password_confirmation'] ?? '');

        if ($username === '' || $password === '' || $confirmation === '') {
            $this->showRegister(
                array(
                    'error' => 'Veuillez remplir tous les champs.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        if (mb_strlen($username) < 3) {
            $this->showRegister(
                array(
                    'error' => 'Le nom d\'utilisateur doit contenir au moins 3 caractères.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        if ($password !== $confirmation) {
            $this->showRegister(
                array(
                    'error' => 'Les mots de passe ne correspondent pas.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        if ($this->userRepository->findByUsername($username) !== null) {
            $this->showRegister(
                array(
                    'error' => 'Un compte existe déjà avec ce nom d\'utilisateur.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        $user = $this->userRepository->create($username, password_hash($password, PASSWORD_DEFAULT));
        $this->gameSession->login($user);
        $this->flash->add('success', 'Compte créé avec succès.');
        $this->redirect('character');
    }

    public function logout(): void
    {
        $this->requireAuthentication();
        $this->gameSession->logout();
        $this->flash->add('success', 'Vous avez été déconnecté.');
        $this->redirect('login');
    }
}
