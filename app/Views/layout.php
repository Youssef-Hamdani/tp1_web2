<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $ui->echapper($title) ?> | <?= $ui->echapper($appName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="<?= $ui->echapper($ui->ressource('css/app.css')) ?>">
</head>
<body>
    <div class="page-backdrop"></div>
    <main class="page-shell">
        <?php require __DIR__ . '/partials/alerts.php'; ?>
        <?php require $contentTemplate; ?>
    </main>

</body>
</html>
