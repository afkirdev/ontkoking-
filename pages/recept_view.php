<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($recept['titel']) ?></title>
    <link rel="stylesheet" href="../CSS/Home.css">
    <script src="../scripts/home.js" defer></script>
</head>
<body>
    <?php $terug = '../'; $paginaTitel = $recept['titel']; include __DIR__ . '/../includes/Header.php'; ?>

    <div class="page-body">

    <div class="content">
        <div class="card">
            <h1><?= e($recept['titel']) ?></h1>
            <p>door <?= e($recept['gebruikersnaam']) ?></p>
            <?php if (!empty($recept['maaltijd'])): ?>
            <p>Maaltijd: <?= e(ucfirst($recept['maaltijd'])) ?></p>
            <?php endif; ?>
            <?php if ($recept['foto']): ?>
            <img src="../<?= e($recept['foto']) ?>" alt="" style="width: 100%;">
            <?php endif; ?>

            <h3>ingredienten</h3>
            <p><?= nl2br(e($recept['ingredienten'])) ?></p>

            <h3>Blog</h3>
            <p><?= nl2br(e($recept['blog'])) ?></p>

            <?php if ($recept['foto2']): ?>
            <img src="../<?= e($recept['foto2']) ?>" alt="" style="width: 100%;">
            <?php endif; ?>
        </div>
    </div>
    </div>

    <?php $footerBasis = '../'; include __DIR__ . '/../includes/Footer.php'; ?>
</body>
</html>
