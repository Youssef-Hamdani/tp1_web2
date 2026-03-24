<section class="auth-shell">
    <header class="hero hero-center">
        <div class="brand-mark">F</div>
        <h1>Fruits en Furie</h1>
        <p>Entrez dans l'arene du marche et menez votre fruit jusqu'au duel final.</p>
    </header>

    <section class="panel auth-panel">
        <div class="panel-heading">
            <h2>Connexion</h2>
            <p>Connectez-vous pour choisir votre combattant et ouvrir les portes.</p>
        </div>

        <?php if (! empty($error)) : ?>
            <div class="notice notice-danger"><?= $ui->echapper($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= $ui->echapper($ui->lien('connexion')) ?>" class="stack">
            <label class="field">
                <span>Nom d'utilisateur</span>
                <input type="text" name="username" value="<?= $ui->echapper($old['username'] ?? '') ?>" autocomplete="username" required>
            </label>

            <label class="field">
                <span>Mot de passe</span>
                <input type="password" name="password" autocomplete="current-password" required>
            </label>

            <button type="submit" class="button button-primary button-block">Se connecter</button>
        </form>

        <p class="switch-link">
            Pas encore de compte?
            <a href="<?= $ui->echapper($ui->lien('inscription')) ?>">Creer un compte</a>
        </p>
    </section>
</section>
