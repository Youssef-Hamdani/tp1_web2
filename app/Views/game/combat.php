<section class="hero">
    <div>
        <h1>Combat</h1>
        <p><?= $ui->echapper($game['player']['name']) ?> contre <?= $ui->echapper($game['combat']['monster']['name']) ?> dans l'arene du marche.</p>
    </div>

    <form method="post" action="<?= $ui->echapper($ui->lien('deconnexion')) ?>">
        <button type="submit" class="button button-ghost">Deconnexion</button>
    </form>
</section>

<section class="stack" id="combat-app" data-end-url="<?= $ui->echapper($ui->lien('fin')) ?>" data-api-url="<?= $ui->echapper($ui->lien('api_combat')) ?>">
    <div class="message-stack" id="combat-logs">
        <?php foreach ($game['combat']['logs'] as $log) : ?>
            <div class="notice notice-<?= $ui->echapper($log['tone'] === 'danger' ? 'danger' : 'success') ?>">
                <?= $ui->echapper($log['text']) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="combat-grid">
        <article class="panel combat-card">
            <div class="combat-card-head">
                <img src="<?= $ui->echapper($ui->ressource($game['player']['image'])) ?>" alt="<?= $ui->echapper($game['player']['name']) ?>" class="avatar">
                <div>
                    <h2 id="player-name"><?= $ui->echapper($game['player']['name']) ?></h2>
                    <p><?= $ui->echapper($game['player']['title']) ?></p>
                </div>
            </div>

            <dl class="stats-list">
                <div><dt>Force</dt><dd id="player-force"><?= $ui->echapper((string) $game['player']['force']) ?></dd></div>
                <div><dt>Defense</dt><dd id="player-defense"><?= $ui->echapper((string) $game['player']['defense']) ?></dd></div>
                <div><dt>Vie</dt><dd id="player-hp-label"><?= $ui->echapper((string) $game['player']['hp']) ?> / <?= $ui->echapper((string) $game['player']['max_hp']) ?></dd></div>
            </dl>

            <div class="health-bar">
                <span id="player-hp-bar" style="width: <?= $ui->echapper((string) max(0, min(100, (int) round(($game['player']['hp'] / max(1, $game['player']['max_hp'])) * 100)))) ?>%;"></span>
            </div>
        </article>

        <article class="panel combat-card">
            <div class="combat-card-head">
                <img src="<?= $ui->echapper($ui->ressource($game['combat']['monster']['image'])) ?>" alt="<?= $ui->echapper($game['combat']['monster']['name']) ?>" class="avatar">
                <div>
                    <h2 id="monster-name"><?= $ui->echapper($game['combat']['monster']['name']) ?></h2>
                    <p><?= $ui->echapper($game['combat']['monster']['description']) ?></p>
                </div>
            </div>

            <dl class="stats-list">
                <div><dt>Force</dt><dd id="monster-force"><?= $ui->echapper((string) $game['combat']['monster']['force']) ?></dd></div>
                <div><dt>Defense</dt><dd id="monster-defense"><?= $ui->echapper((string) $game['combat']['monster']['defense']) ?></dd></div>
                <div><dt>Vie</dt><dd id="monster-hp-label"><?= $ui->echapper((string) $game['combat']['monster']['hp']) ?> / <?= $ui->echapper((string) $game['combat']['monster']['max_hp']) ?></dd></div>
            </dl>

            <div class="health-bar">
                <span id="monster-hp-bar" style="width: <?= $ui->echapper((string) max(0, min(100, (int) round(($game['combat']['monster']['hp'] / max(1, $game['combat']['monster']['max_hp'])) * 100)))) ?>%;"></span>
            </div>
        </article>
    </div>

    <div class="combat-actions">
        <button type="button" class="button button-primary" data-combat-action="attack">Attaquer</button>
        <button
            type="button"
            class="button button-outline"
            data-combat-action="power"
            id="power-button"
            <?= ($character !== null && $character->peutUtiliserPouvoir($game['player'])) ? '' : 'disabled' ?>
        >
            Pouvoir
        </button>
    </div>
</section>

<script>
const combatApp = document.getElementById('combat-app');
const apiUrl = combatApp.dataset.apiUrl;
const endUrl = combatApp.dataset.endUrl;
const logsElement = document.getElementById('combat-logs');
const actionButtons = combatApp.querySelectorAll('[data-combat-action]');
const powerButton = document.getElementById('power-button');

function renderHealth(prefix, fighter) {
    document.getElementById(prefix + '-force').textContent = fighter.force;
    document.getElementById(prefix + '-defense').textContent = fighter.defense;
    document.getElementById(prefix + '-hp-label').textContent = fighter.hp + ' / ' + fighter.max_hp;
    document.getElementById(prefix + '-hp-bar').style.width = Math.max(0, Math.min(100, Math.round((fighter.hp / Math.max(1, fighter.max_hp)) * 100))) + '%';
}

function renderLogs(logs) {
    logsElement.innerHTML = '';

    logs.forEach(function (log) {
        const div = document.createElement('div');
        div.className = 'notice notice-' + (log.tone === 'danger' ? 'danger' : 'success');
        div.textContent = log.text;
        logsElement.appendChild(div);
    });
}

function setBusy(isBusy) {
    actionButtons.forEach(function (button) {
        button.disabled = isBusy || (button === powerButton && powerButton.dataset.available === 'false');
    });
}

actionButtons.forEach(function (button) {
    button.addEventListener('click', async function () {
        const action = button.dataset.combatAction;
        const body = new URLSearchParams();
        body.set('action', action);

        setBusy(true);

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: body.toString()
            });

            const data = await response.json();

            if (!data.ok) {
                window.location.href = endUrl;
                return;
            }

            renderHealth('player', data.player);
            renderHealth('monster', data.monster);
            renderLogs(data.logs);
            powerButton.dataset.available = data.powerAvailable ? 'true' : 'false';
            powerButton.disabled = !data.powerAvailable;

            if (data.finished && data.redirectUrl) {
                window.location.href = data.redirectUrl;
            }
        } catch (error) {
            window.location.reload();
        } finally {
            setBusy(false);
        }
    });
});

powerButton.dataset.available = powerButton.disabled ? 'false' : 'true';
</script>
