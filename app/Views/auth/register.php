<section class="auth-shell">
    <header class="hero hero-center">
        <div class="brand-mark">C</div>
        <h1>Chatssassins</h1>
        <p>Créez votre compte avant d'entrer dans le couloir des portes.</p>
    </header>

    <section class="panel auth-panel">
        <div class="panel-heading">
            <h2>Créer un compte</h2>
            <p>Votre progression de partie restera dans la session en cours.</p>
        </div>

        <?php if (! empty($error)) : ?>
            <div class="notice notice-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('register')) ?>" class="stack">
            <label class="field">
                <span>Nom d'utilisateur</span>
                <input type="text" name="username" value="<?= e($old['username'] ?? '') ?>" autocomplete="username" required>
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
            <a href="<?= e(url('login')) ?>">Se connecter</a>
        </p>
    </section>
</section>

