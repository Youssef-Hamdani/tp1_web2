<section class="hero">
    <div>
        <div class="brand-line">
            <div class="brand-mark">C</div>
            <div>
                <h1>Chatssassins</h1>
                <p>Choisissez votre félin de combat</p>
            </div>
        </div>
    </div>

    <form method="post" action="<?= e(url('logout')) ?>">
        <button type="submit" class="button button-ghost">Déconnexion</button>
    </form>
</section>

<section class="panel game-panel">
    <div class="panel-heading">
        <h2>Personnage</h2>
        <p>Sélectionnez une classe avec un pouvoir distinct avant d'ouvrir les portes.</p>
    </div>

    <form method="post" action="<?= e(url('character')) ?>" class="stack">
        <div class="choice-grid choice-grid-characters">
            <?php $first = true; ?>
            <?php foreach ($characters as $character) : ?>
                <label class="choice-card">
                    <input type="radio" name="character_id" value="<?= e($character->getId()) ?>" <?= $first ? 'checked' : '' ?>>
                    <span class="choice-card-body">
                        <img src="<?= e(asset($character->getImagePath())) ?>" alt="<?= e($character->getName()) ?>" class="avatar avatar-large">
                        <strong><?= e($character->getName()) ?></strong>
                        <span><?= e($character->getTitle()) ?></span>
                        <small><?= e($character->getPowerName()) ?>: <?= e($character->getPowerDescription()) ?></small>
                    </span>
                </label>
                <?php $first = false; ?>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="button button-primary button-block">Choisir</button>
    </form>
</section>

