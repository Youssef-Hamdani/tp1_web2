<?php if (! empty($flashMessages)) : ?>
    <div class="stack stack-sm">
        <?php foreach ($flashMessages as $flash) : ?>
            <div class="notice notice-<?= $ui->echapper($flash['type']) ?>">
                <?= $ui->echapper($flash['message']) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
