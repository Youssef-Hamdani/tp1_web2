<section class="hero">
    <div>
        <div class="brand-line">
            <div class="brand-mark">C</div>
            <div>
                <h1>Nuit des Neuf Vies</h1>
                <p>Choisissez votre gardien nocturne</p>
            </div>
        </div>
    </div>

    <form method="post" action="<?= $ui->e($ui->url('logout')) ?>">
        <button type="submit" class="button button-ghost">Déconnexion</button>
    </form>
</section>

<section class="panel game-panel">
    <div class="panel-heading">
        <h2>Personnage</h2>
        <p>Sélectionnez une classe avec un pouvoir distinct avant d'ouvrir les portes.</p>
    </div>

    <form method="post" action="<?= $ui->e($ui->url('character')) ?>" class="stack">
        <div class="choice-grid choice-grid-characters">
            <?php $first = true; ?>
            <?php foreach ($characters as $character) : ?>
                <label class="choice-card">
                    <input type="radio" name="character_id" value="<?= $ui->e($character->getId()) ?>" <?= $first ? 'checked' : '' ?>>
                    <span class="choice-card-body">
                        <img src="<?= $ui->e($ui->asset($character->getImagePath())) ?>" alt="<?= $ui->e($character->getName()) ?>" class="avatar avatar-large">
                        <strong><?= $ui->e($character->getName()) ?></strong>
                        <span><?= $ui->e($character->getTitle()) ?></span>
                        <small><?= $ui->e($character->getPowerName()) ?>: <?= $ui->e($character->getPowerDescription()) ?></small>
                    </span>
                </label>
                <?php $first = false; ?>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="button button-primary button-block">Choisir</button>
    </form>
</section>
