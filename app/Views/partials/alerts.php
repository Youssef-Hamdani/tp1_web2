<?php if (! empty($flashMessages)) : ?>
    <div class="stack stack-sm">
        <?php foreach ($flashMessages as $flash) : ?>
            <div class="notice notice-<?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

