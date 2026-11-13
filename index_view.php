<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ontkoking — recepten voor elke dag</title>
    <link rel="stylesheet" href="./CSS/Home.css">
    <link rel="stylesheet" href="./CSS/Grid.css">
    <script src="./scripts/home.js" defer></script>

</head>
<body class="home-pagina">
    <?php include __DIR__ . '/includes/Header.php'; ?>

    <div class="page-body">

    <section class="kook-intro" aria-labelledby="intro-titel">
        <div class="kook-intro-tekst">
            <p class="sectie-label">Het kookboek voor elke dag</p>
            <h1 id="intro-titel">Wat eten<br>we vandaag?</h1>
            <p>Een snelle lunch of rustig koken op zondag. Hier vind je iets voor op tafel.</p>
            <a class="tekst-link" href="pages/Aanmakken.php">Deel je eigen recept <span aria-hidden="true">↗</span></a>
        </div>
        <img src="assets/hero.png" alt="Een gedekte tafel met brood, tapas en kleine gerechten" width="890" height="500" fetchpriority="high">
    </section>
    <section class="zoek-strook" aria-label="Vind je volgende recept">
        <form class="recept-zoeken" method="get" action="index.php" role="search" aria-label="Recepten zoeken">
            <div class="zoek-basis">
                <div class="zoek-veld">
                    <label for="zoek" class="visueel-verborgen">Zoek een recept</label>
                    <input type="search" id="zoek" name="zoek" value="<?= e($zoekterm) ?>" placeholder="Zoek een recept…">
                </div>
                <button type="submit" class="zoek-knop">Zoeken</button>
            </div>
            <details class="zoek-filters" <?= $ingredientenFilter !== '' || $filter !== '' ? 'open' : '' ?>>
                <summary>Filters<?= $ingredientenFilter !== '' || $filter !== '' ? ' · actief' : '' ?></summary>
                <div class="zoek-velden">
                <div class="zoek-veld">
                    <label for="ingredienten">Ingrediënten</label>
                    <input type="text" id="ingredienten" name="ingredienten" value="<?= e($ingredientenFilter) ?>" placeholder="Bijvoorbeeld tomaat, kaas" aria-describedby="ingredienten-hulp">
                </div>
                <div class="zoek-veld">
                    <label for="options">Maaltijd</label>
        <select id="options" name="filter">
            <option value="">Alles</option>
            <option value="Ontbijt" <?= $filter == 'Ontbijt' ? 'selected' : '' ?>>Ontbijt</option>
            <option value="Lunch" <?= $filter == 'Lunch' ? 'selected' : '' ?>>Lunch</option>
            <option value="Avondeten" <?= $filter == 'Avondeten' ? 'selected' : '' ?>>Avondeten</option>
        </select>
                </div>
                </div>
                <p id="ingredienten-hulp">Scheid ingrediënten met komma’s, bijvoorbeeld tomaat, kaas.</p>
                <button type="submit" class="zoek-knop filter-toepassen">Filters toepassen</button>
            </details>
        </form>
    </section>

    <div class="zoek-resultaten" role="status">
        <p><?= count($recepten) ?> <?= count($recepten) === 1 ? 'recept gevonden' : 'recepten gevonden' ?></p>
        <?php if ($zoekterm !== '' || $ingredientenFilter !== '' || $filter !== ''): ?>
            <a href="index.php">Wis zoekopdracht en filters</a>
        <?php endif; ?>
    </div>
    <?php if (!$recepten): ?>
        <p class="geen-resultaten">Geen recepten gevonden. Probeer een andere zoekterm of minder ingrediënten.</p>
    <?php endif; ?>

    <!-- Alle recepten in één doorlopend overzicht. -->
    <div id="grid1">
        <?php foreach ($recepten as $recept): ?>
        <a class="grid-item" href="pages/recept.php?id=<?= $recept['id'] ?>"><img loading="lazy" src="<?= e($recept['foto'] ?: $recept['foto2'] ?: 'assets/hero.png') ?>" alt="<?= e($recept['titel']) ?>">
        <div><p class="recept-meta"><?= $recept['bereidingstijd'] ? (int)$recept['bereidingstijd'] . ' min · ' : '' ?><?= e($recept['categorie'] ?? '') ?></p><h2><?= e($recept['titel']) ?></h2><p><?= e($recept['beschrijving'] ?? '') ?></p></div></a>
        <?php endforeach; ?>
    </div>

    </div>

    <?php $footerBasis = ''; include __DIR__ . '/includes/Footer.php'; ?>
</body>
</html>
