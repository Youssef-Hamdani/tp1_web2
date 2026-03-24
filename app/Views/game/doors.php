<section class="hero">
    <div>
        <div class="brand-line">
            <div class="brand-mark">C</div>
            <div>
                <h1>Nuit des Neuf Vies</h1>
                <p>Choisissez une porte... un gardien canin se cache derrière l'une d'elles</p>
            </div>
        </div>
    </div>

    <form method="post" action="<?= $ui->e($ui->url('logout')) ?>">
        <button type="submit" class="button button-ghost">Déconnexion</button>
    </form>
</section>

<?php if ($feedback !== null) : ?>
    <div class="notice notice-<?= $ui->e($feedback['tone'] === 'danger' ? 'danger' : 'success') ?>">
        <?= $ui->e($feedback['text']) ?>
    </div>
<?php endif; ?>

<section class="panel game-panel">
    <div class="panel-heading">
        <h2>Portes</h2>
        <p>Chaque porte ne peut être ouverte qu'une seule fois.</p>
    </div>

    <form method="post" action="<?= $ui->e($ui->url('open-door')) ?>" class="stack">
        <div class="choice-grid choice-grid-doors">
            <?php $selected = false; ?>
            <?php foreach ($game['doors'] as $door) : ?>
                <label class="choice-card choice-card-door <?= $door['opened'] ? 'choice-card-opened' : '' ?>">
                    <input
                        type="radio"
                        name="door_number"
                        value="<?= $ui->e((string) $door['number']) ?>"
                        <?= (! $door['opened'] && ! $selected) ? 'checked' : '' ?>
                        <?= $door['opened'] ? 'disabled' : '' ?>
                    >
                    <span class="choice-card-body choice-card-door-body">
                        <span class="door-icon"><?= $door['opened'] ? '✓' : '🚪' ?></span>
                        <strong><?= $ui->e((string) $door['number']) ?></strong>
                        <span><?= $door['opened'] ? 'Déjà ouverte' : 'Mystère' ?></span>
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
