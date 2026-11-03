<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./CSS/Home.css">
    <link rel="stylesheet" href="./CSS/Grid.css">
    <script src="./scripts/home.js" defer></script>

</head>
<body class="home-pagina">
    <?php include __DIR__ . '/includes/Header.php'; ?>

    <div class="page-body">

    <div class="hero">
        <select id="options">
            <option value="">Alles</option>
            <option value="Ontbijt" <?= $filter == 'Ontbijt' ? 'selected' : '' ?>>Ontbijt</option>
            <option value="Lunch" <?= $filter == 'Lunch' ? 'selected' : '' ?>>Lunch</option>
            <option value="Avondeten" <?= $filter == 'Avondeten' ? 'selected' : '' ?>>Avondeten</option>
        </select>
    </div>

    <!--de eerste 4 recepten-->
    <div id="grid1">
        <?php foreach ($bovenste as $recept): ?>
        <a class="grid-item" href="pages/recept.php?id=<?= $recept['id'] ?>"><img src="<?= e($recept['foto'] ?: $recept['foto2'] ?: 'assets/hero.png') ?>" alt="<?= e($recept['titel']) ?>">
        <div><h5><?= e($recept['titel']) ?></h5></div></a>
        <?php endforeach; ?>
    </div>

    <!--de rest van de recepten-->
        <div id="grid2">
        <?php foreach ($rest as $recept): ?>
        <a class="grid-item" href="pages/recept.php?id=<?= $recept['id'] ?>"><img src="<?= e($recept['foto'] ?: $recept['foto2'] ?: 'assets/hero.png') ?>" alt="<?= e($recept['titel']) ?>">
        <div><h5><?= e($recept['titel']) ?></h5></div></a>
        <?php endforeach; ?>
    </div>
    </div>

    <?php $footerBasis = ''; include __DIR__ . '/includes/Footer.php'; ?>
</body>
</html>
