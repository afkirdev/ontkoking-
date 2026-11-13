<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Je recepten — Ontkoking</title>
    <link rel="stylesheet" href="../CSS/Home.css">
    <link rel="stylesheet" href="../CSS/ReceptOverzicht.css">
    <script src="../scripts/home.js" defer></script>
</head>
<body class="recepten-pagina">
    <?php $terug = '../'; $paginaTitel = 'je recepten'; include __DIR__ . '/../includes/Header.php'; ?>

    <div class="page-body">

    <main class="recepten-inhoud">
        <?php foreach ([array_slice($recepten, 0, 3), array_slice($recepten, 3)] as $groepIndex => $groep): ?>
            <?php if ($groep): ?>
            <section class="recepten-grid <?= $groepIndex === 0 ? 'recepten-uitgelicht' : 'recepten-overig' ?>" aria-label="<?= $groepIndex === 0 ? 'Je nieuwste recepten' : 'Je overige recepten' ?>">
                <?php foreach ($groep as $recept): ?>
                <article class="recept-kaart">
                    <a class="recept-foto" href="recept.php?id=<?= (int)$recept['id'] ?>">
                        <img src="../<?= e($recept['foto'] ?: $recept['foto2'] ?: 'assets/hero.png') ?>" alt="<?= e($recept['titel']) ?>">
                    </a>
                    <div class="recept-acties">
                        <a href="Aanmakken.php?id=<?= (int)$recept['id'] ?>" aria-label="<?= e($recept['titel']) ?> bewerken">bewerken</a>
                        <a href="../Data/verwijder.php?id=<?= (int)$recept['id'] ?>" aria-label="<?= e($recept['titel']) ?> verwijderen" onclick="return confirm('Weet je zeker dat je dit recept wilt verwijderen?')">verwijderen</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </section>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (!$recepten): ?>
        <p class="lege-recepten">Je hebt nog geen recepten. Deel je eerste recept!</p>
        <?php endif; ?>

        <a class="recept-toevoegen" href="Aanmakken.php">
            <span>Deel een nieuw recept</span>
            <span class="toevoegen-plus" aria-hidden="true">+</span>
        </a>
    </main>

    </div>

    <?php $footerBasis = '../'; include __DIR__ . '/../includes/Footer.php'; ?>
</body>
</html>
