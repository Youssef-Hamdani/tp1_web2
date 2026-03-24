<?php $result = $game['result']; ?>

<section class="hero">
    <div>
        <h1>Fin de partie</h1>
        <p><?= $ui->e($result['title']) ?></p>
    </div>

    <form method="post" action="<?= $ui->e($ui->url('logout')) ?>">
        <button type="submit" class="button button-ghost">Déconnexion</button>
    </form>
</section>

<section class="panel end-panel">
    <div class="result-badge result-badge-<?= $ui->e($result['outcome']) ?>">
        <?= $ui->e($result['outcome'] === 'victory' ? 'Victoire' : 'Défaite') ?>
    </div>

    <h2><?= $ui->e($result['title']) ?></h2>
    <p class="end-copy"><?= $ui->e($result['message']) ?></p>

    <div class="summary-grid">
        <article class="summary-card">
            <img src="<?= $ui->e($ui->asset($game['player']['image'])) ?>" alt="<?= $ui->e($game['player']['name']) ?>" class="avatar">
            <strong><?= $ui->e($game['player']['name']) ?></strong>
            <span>PV finaux: <?= $ui->e((string) $game['player']['hp']) ?> / <?= $ui->e((string) $game['player']['max_hp']) ?></span>
        </article>

        <article class="summary-card">
            <img src="<?= $ui->e($ui->asset($game['combat']['monster']['image'])) ?>" alt="<?= $ui->e($game['combat']['monster']['name']) ?>" class="avatar">
            <strong><?= $ui->e($game['combat']['monster']['name']) ?></strong>
            <span>PV finaux: <?= $ui->e((string) $game['combat']['monster']['hp']) ?> / <?= $ui->e((string) $game['combat']['monster']['max_hp']) ?></span>
        </article>
    </div>

    <div class="combat-actions">
        <form method="post" action="<?= $ui->e($ui->url('replay')) ?>">
            <button type="submit" class="button button-primary">Rejouer</button>
        </form>

        <form method="post" action="<?= $ui->e($ui->url('logout')) ?>">
            <button type="submit" class="button button-outline">Se déconnecter</button>
        </form>
    </div>
</section>
