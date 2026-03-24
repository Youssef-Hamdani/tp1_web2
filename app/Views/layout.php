<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $ui->e($title) ?> | <?= $ui->e($appName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $ui->e($ui->asset('css/app.css')) ?>">
</head>
<body>
    <div class="page-backdrop"></div>
    <main class="page-shell">
        <?php require __DIR__ . '/partials/alerts.php'; ?>
        <?php require $contentTemplate; ?>
    </main>
    <footer class="credits-bar">
        <span>Images réelles via Wikimedia Commons :</span>
        <?php foreach ($imageCredits as $index => $credit) : ?>
            <a href="<?= $ui->e($credit['url']) ?>" target="_blank" rel="noreferrer"><?= $ui->e($credit['label']) ?></a><?= $index < count($imageCredits) - 1 ? '<span class="credits-sep">•</span>' : '' ?>
        <?php endforeach; ?>
    </footer>
</body>
</html>
