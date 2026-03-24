<section class="auth-shell">
    <header class="hero hero-center">
        <div class="brand-mark">F</div>
        <h1>fruityloops</h1>
        <p>Creez votre compte avant d'entrer dans le couloir des ingredients hostiles.</p>
    </header>

    <section class="panel auth-panel">
        <div class="panel-heading">
            <h2>Creer un compte</h2>
            <p>Votre progression reste dans la session active jusqu'a la fin du duel.</p>
        </div>

        <?php if (! empty($error)) : ?>
            <div class="notice notice-danger"><?= $ui->echapper($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= $ui->echapper($ui->lien('inscription')) ?>" class="stack">
            <label class="field">
                <span>Nom d'utilisateur</span>
                <input type="text" name="username" value="<?= $ui->echapper($old['username'] ?? '') ?>" autocomplete="username" required>
            </label>

            <label class="field">
                <span>Mot de passe</span>
                <input type="password" name="password" autocomplete="new-password" required>
            </label>

            <label class="field">
                <span>Confirmer le mot de passe</span>
                <input type="password" name="password_confirmation" autocomplete="new-password" required>
            </label>

            <button type="submit" class="button button-primary button-block">Creer mon compte</button>
        </form>

        <p class="switch-link">
            Deja inscrit?
            <a href="<?= $ui->echapper($ui->lien('connexion')) ?>">Se connecter</a>
        </p>
    </section>
</section>
