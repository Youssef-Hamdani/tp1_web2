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

    public function afficherConnexion(array $data = array()): void
    {
        $this->exigerInvite();

        $this->afficherVue(
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

    public function traiterConnexion(): void
    {
        $this->exigerInvite();

        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $this->afficherConnexion(
                array(
                    'error' => 'Veuillez remplir tous les champs.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        $user = $this->userRepository->trouverParNomUtilisateur($username);

        if ($user === null || ! password_verify($password, $user->obtenirMotDePasseHache())) {
            $this->afficherConnexion(
                array(
                    'error' => 'Nom d\'utilisateur ou mot de passe invalide.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        $this->gameSession->ouvrirSessionUtilisateur($user);
        $this->flash->ajouter('success', sprintf('Bon retour, %s.', $user->obtenirNomUtilisateur()));
        $this->rediriger($this->gameSession->obtenirPageCouranteJeu());
    }

    public function afficherInscription(array $data = array()): void
    {
        $this->exigerInvite();

        $this->afficherVue(
            'auth.register',
            array_merge(
                array(
                    'title' => 'Creer un compte',
                    'old' => array('username' => ''),
                ),
                $data
            )
        );
    }

    public function traiterInscription(): void
    {
        $this->exigerInvite();

        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmation = (string) ($_POST['password_confirmation'] ?? '');

        if ($username === '' || $password === '' || $confirmation === '') {
            $this->afficherInscription(
                array(
                    'error' => 'Veuillez remplir tous les champs.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        if (mb_strlen($username) < 3) {
            $this->afficherInscription(
                array(
                    'error' => 'Le nom d\'utilisateur doit contenir au moins 3 caracteres.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        if ($password !== $confirmation) {
            $this->afficherInscription(
                array(
                    'error' => 'Les mots de passe ne correspondent pas.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        if ($this->userRepository->trouverParNomUtilisateur($username) !== null) {
            $this->afficherInscription(
                array(
                    'error' => 'Un compte existe deja avec ce nom d\'utilisateur.',
                    'old' => array('username' => $username),
                )
            );

            return;
        }

        $user = $this->userRepository->creer($username, password_hash($password, PASSWORD_DEFAULT));
        $this->gameSession->ouvrirSessionUtilisateur($user);
        $this->flash->ajouter('success', 'Compte cree avec succes.');
        $this->rediriger('personnages');
    }

    public function deconnecter(): void
    {
        $this->exigerAuthentification();
        $this->gameSession->fermerSessionUtilisateur();
        $this->flash->ajouter('success', 'Vous avez ete deconnecte.');
        $this->rediriger('connexion');
    }
}
