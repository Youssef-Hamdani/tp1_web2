<?php $result = $game['result']; ?>

<section class="hero">
    <div>
        <h1>Fin de partie</h1>
        <p><?= $ui->echapper($result['title']) ?></p>
    </div>

    <form method="post" action="<?= $ui->echapper($ui->lien('deconnexion')) ?>">
        <button type="submit" class="button button-ghost">Deconnexion</button>
    </form>
</section>

<section class="panel end-panel">
    <div class="result-badge result-badge-<?= $ui->echapper($result['outcome']) ?>">
        <?= $ui->echapper($result['outcome'] === 'victory' ? 'Victoire' : 'Defaite') ?>
    </div>

    <h2><?= $ui->echapper($result['title']) ?></h2>
    <p class="end-copy"><?= $ui->echapper($result['message']) ?></p>

    <div class="summary-grid">
        <article class="summary-card">
            <img src="<?= $ui->echapper($ui->ressource($game['player']['image'])) ?>" alt="<?= $ui->echapper($game['player']['name']) ?>" class="avatar">
            <strong><?= $ui->echapper($game['player']['name']) ?></strong>
            <span>PV finaux: <?= $ui->echapper((string) $game['player']['hp']) ?> / <?= $ui->echapper((string) $game['player']['max_hp']) ?></span>
        </article>

        <article class="summary-card">
            <img src="<?= $ui->echapper($ui->ressource($game['combat']['monster']['image'])) ?>" alt="<?= $ui->echapper($game['combat']['monster']['name']) ?>" class="avatar">
            <strong><?= $ui->echapper($game['combat']['monster']['name']) ?></strong>
            <span>PV finaux: <?= $ui->echapper((string) $game['combat']['monster']['hp']) ?> / <?= $ui->echapper((string) $game['combat']['monster']['max_hp']) ?></span>
        </article>
    </div>

    <div class="combat-actions">
        <form method="post" action="<?= $ui->echapper($ui->lien('rejouer')) ?>">
            <button type="submit" class="button button-primary">Rejouer</button>
        </form>

        <form method="post" action="<?= $ui->echapper($ui->lien('deconnexion')) ?>">
            <button type="submit" class="button button-outline">Se deconnecter</button>
        </form>
    </div>
</section>
