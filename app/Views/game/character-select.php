<section class="hero">
    <div>
        <div class="brand-line">
            <div class="brand-mark">F</div>
            <div>
                <h1>fruityloops</h1>
                <p>Choisissez votre fruit de combat</p>
            </div>
        </div>
    </div>

    <form method="post" action="<?= $ui->echapper($ui->lien('deconnexion')) ?>">
        <button type="submit" class="button button-ghost">Deconnexion</button>
    </form>
</section>

<section class="panel game-panel">
    <div class="panel-heading">
        <h2>Equipe de fruits</h2>
        <p>Chaque fruit a un pouvoir special. Choisissez-en un avant d'ouvrir les portes.</p>
    </div>

    <form method="post" action="<?= $ui->echapper($ui->lien('personnages')) ?>" class="stack">
        <div class="choice-grid choice-grid-characters">
            <?php $first = true; ?>
            <?php foreach ($characters as $character) : ?>
                <label class="choice-card">
                    <input type="radio" name="character_id" value="<?= $ui->echapper($character->obtenirId()) ?>" <?= $first ? 'checked' : '' ?>>
                    <span class="choice-card-body">
                        <img src="<?= $ui->echapper($ui->ressource($character->obtenirCheminImage())) ?>" alt="<?= $ui->echapper($character->obtenirNom()) ?>" class="avatar avatar-large">
                        <strong><?= $ui->echapper($character->obtenirNom()) ?></strong>
                        <span><?= $ui->echapper($character->obtenirTitre()) ?></span>
                        <small><?= $ui->echapper($character->obtenirNomPouvoir()) ?>: <?= $ui->echapper($character->obtenirDescriptionPouvoir()) ?></small>
                    </span>
                </label>
                <?php $first = false; ?>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="button button-primary button-block">Choisir ce fruit</button>
    </form>
</section>
