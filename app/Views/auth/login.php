<section class="auth-shell">
    <header class="hero hero-center">
        <div class="brand-mark">C</div>
        <h1>Chatssassins</h1>
        <p>Connectez-vous pour lancer votre mission féline.</p>
    </header>

    <section class="panel auth-panel">
        <div class="panel-heading">
            <h2>Connexion</h2>
            <p>Accédez à votre compte pour choisir votre chat de combat.</p>
        </div>

        <?php if (! empty($error)) : ?>
            <div class="notice notice-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('login')) ?>" class="stack">
            <label class="field">
                <span>Nom d'utilisateur</span>
                <input type="text" name="username" value="<?= e($old['username'] ?? '') ?>" autocomplete="username" required>
            </label>

            <label class="field">
                <span>Mot de passe</span>
                <input type="password" name="password" autocomplete="current-password" required>
            </label>

            <button type="submit" class="button button-primary button-block">Se connecter</button>
        </form>

        <p class="switch-link">
            Pas encore de compte?
            <a href="<?= e(url('register')) ?>">Créer un compte</a>
        </p>
    </section>
</section>

