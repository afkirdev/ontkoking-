<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bekijken</title>
    <link rel="stylesheet" href="../CSS/Home.css">
    <link rel="stylesheet" href="../CSS/Grid.css">
    <link rel="stylesheet" href="../CSS/Aanmakken.css">
    <script src="../scripts/home.js" defer></script>
</head>
    <body>
    <?php $terug = '../'; include __DIR__ . '/../includes/Header.php'; ?>

    <div class="page-body">

    <form action="../Data/updateverwerk.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= e($recept['id']) ?>">
                Titel:<br>
                <input type="text" name="titel" required value="<?= e($recept['titel']) ?>">

                <div class="maaltijd-keuze">
                    <label for="maaltijd">Voor welke maaltijd is je recept?</label>
                    <select id="maaltijd" name="maaltijd" required>
                        <option value="">Kies een maaltijd</option>
                        <?php foreach (['ontbijt' => 'Ontbijt', 'lunch' => 'Lunch', 'avondeten' => 'Avondeten'] as $waarde => $label): ?>
                        <option value="<?= $waarde ?>" <?= ($recept['maaltijd'] ?? '') === $waarde ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="grid2">

                <div>
                <div>
                ingredienten:
                <textarea id="ingredienten" name="ingredienten" required cols="30" rows="20"><?= e($recept['ingredienten']) ?></textarea>
                </div>
                Nieuwe foto (laat leeg om de huidige te behouden):<br>
                <input type="file" name="picture" accept="image/*">
            </div>

                <div>
                Blog:<br>
                <textarea name="blog" required cols="70" rows="55"><?= e($recept['blog']) ?></textarea>
                        </div>
                            <div>
                                Nieuwe foto (laat leeg om de huidige te behouden):<br>
                <input type="file" name="picture2" accept="image/*">
        </div>
            </div>
        <input type="submit" value="<?= $recept['id'] ? 'Bewerken' : 'Aanmaken' ?>">
    </form>



    </div>

    <?php $footerBasis = '../'; include __DIR__ . '/../includes/Footer.php'; ?>
    </body>
</html>
