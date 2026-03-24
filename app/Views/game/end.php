<?php $result = $game['result']; ?>

<section class="hero">
    <div>
        <h1>Fin de partie</h1>
        <p><?= e($result['title']) ?></p>
    </div>

    <form method="post" action="<?= e(url('logout')) ?>">
        <button type="submit" class="button button-ghost">Déconnexion</button>
    </form>
</section>

<section class="panel end-panel">
    <div class="result-badge result-badge-<?= e($result['outcome']) ?>">
        <?= e($result['outcome'] === 'victory' ? 'Victoire' : 'Défaite') ?>
    </div>

    <h2><?= e($result['title']) ?></h2>
    <p class="end-copy"><?= e($result['message']) ?></p>

    <div class="summary-grid">
        <article class="summary-card">
            <img src="<?= e(asset($game['player']['image'])) ?>" alt="<?= e($game['player']['name']) ?>" class="avatar">
            <strong><?= e($game['player']['name']) ?></strong>
            <span>PV finaux: <?= e((string) $game['player']['hp']) ?> / <?= e((string) $game['player']['max_hp']) ?></span>
        </article>

        <article class="summary-card">
            <img src="<?= e(asset($game['combat']['monster']['image'])) ?>" alt="<?= e($game['combat']['monster']['name']) ?>" class="avatar">
            <strong><?= e($game['combat']['monster']['name']) ?></strong>
            <span>PV finaux: <?= e((string) $game['combat']['monster']['hp']) ?> / <?= e((string) $game['combat']['monster']['max_hp']) ?></span>
        </article>
    </div>

    <div class="combat-actions">
        <form method="post" action="<?= e(url('replay')) ?>">
            <button type="submit" class="button button-primary">Rejouer</button>
        </form>

        <form method="post" action="<?= e(url('logout')) ?>">
            <button type="submit" class="button button-outline">Se déconnecter</button>
        </form>
    </div>
</section>

