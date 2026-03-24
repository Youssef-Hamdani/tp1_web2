<section class="auth-shell">
    <header class="hero hero-center">
        <div class="brand-mark">C</div>
        <h1>Nuit des Neuf Vies</h1>
        <p>Créez votre compte avant d'affronter les pièges et les chiens de garde.</p>
    </header>

    <section class="panel auth-panel">
        <div class="panel-heading">
            <h2>Créer un compte</h2>
            <p>Votre progression de partie restera dans la session en cours.</p>
        </div>

        <?php if (! empty($error)) : ?>
            <div class="notice notice-danger"><?= $ui->e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= $ui->e($ui->url('register')) ?>" class="stack">
            <label class="field">
                <span>Nom d'utilisateur</span>
                <input type="text" name="username" value="<?= $ui->e($old['username'] ?? '') ?>" autocomplete="username" required>
            </label>

            <label class="field">
                <span>Mot de passe</span>
                <input type="password" name="password" autocomplete="new-password" required>
            </label>

            <label class="field">
                <span>Confirmer le mot de passe</span>
                <input type="password" name="password_confirmation" autocomplete="new-password" required>
            </label>

            <button type="submit" class="button button-primary button-block">Créer mon compte</button>
        </form>

        <p class="switch-link">
            Déjà inscrit?
            <a href="<?= $ui->e($ui->url('login')) ?>">Se connecter</a>
        </p>
    </section>
</section>
