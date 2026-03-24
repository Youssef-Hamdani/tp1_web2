<section class="hero">
    <div>
        <div class="brand-line">
            <div class="brand-mark">F</div>
            <div>
                <h1>fruityloops</h1>
                <p>Une porte cache un legume ennemi, les autres modifient vos stats.</p>
            </div>
        </div>
    </div>

    <form method="post" action="<?= $ui->echapper($ui->lien('deconnexion')) ?>">
        <button type="submit" class="button button-ghost">Deconnexion</button>
    </form>
</section>

<?php if ($feedback !== null) : ?>
    <div class="notice notice-<?= $ui->echapper($feedback['tone'] === 'danger' ? 'danger' : 'success') ?>">
        <?= $ui->echapper($feedback['text']) ?>
    </div>
<?php endif; ?>

<section class="panel game-panel">
    <div class="panel-heading">
        <h2>Portes mysteres</h2>
        <p>Chaque porte ne peut etre ouverte qu'une seule fois.</p>
    </div>

    <form method="post" action="<?= $ui->echapper($ui->lien('ouvrir_porte')) ?>" class="stack">
        <div class="choice-grid choice-grid-doors">
            <?php $selected = false; ?>
            <?php foreach ($game['doors'] as $door) : ?>
                <label class="choice-card choice-card-door <?= $door['opened'] ? 'choice-card-opened' : '' ?>">
                    <input
                        type="radio"
                        name="door_number"
                        value="<?= $ui->echapper((string) $door['number']) ?>"
                        <?= (! $door['opened'] && ! $selected) ? 'checked' : '' ?>
                        <?= $door['opened'] ? 'disabled' : '' ?>
                    >
                    <span class="choice-card-body choice-card-door-body">
                        <span class="door-icon"><?= $door['opened'] ? 'OK' : '?' ?></span>
                        <strong>Porte <?= $ui->echapper((string) $door['number']) ?></strong>
                        <span><?= $door['opened'] ? 'Deja ouverte' : 'Mystere total' ?></span>
                    </span>
                </label>
                <?php if (! $door['opened'] && ! $selected) : ?>
                    <?php $selected = true; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="button button-primary button-block">Ouvrir la porte</button>
    </form>
</section>
