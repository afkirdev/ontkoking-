<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $recept['id'] ? 'Recept bewerken' : 'Recept delen' ?> — Ontkoking</title>
    <link rel="stylesheet" href="../CSS/Home.css">
    <link rel="stylesheet" href="../CSS/Aanmakken.css">
    <script src="../scripts/home.js" defer></script>
</head>
<body>
    <?php $terug = '../'; $paginaTitel = 'Je keuken'; include __DIR__ . '/../includes/Header.php'; ?>
    <main class="page-body">
        <form class="recept-formulier" action="../Data/updateverwerk.php" method="post" enctype="multipart/form-data">
            <p class="sectie-label">Uit jouw keuken</p>
            <h1><?= $recept['id'] ? 'Nog een beetje bijschaven.' : 'Een goed recept is er om te delen.' ?></h1>
            <p>Vertel wat je maakt en hoe. Een eigen foto maakt het af.</p>
            <input type="hidden" name="id" value="<?= e($recept['id']) ?>">
            <div class="net-formulier">
                <label for="titel">Naam van je gerecht</label>
                <input id="titel" type="text" name="titel" maxlength="100" required placeholder="Bijvoorbeeld: oma’s tomatensoep" value="<?= e($recept['titel']) ?>">
                <label for="beschrijving">In één zin <span class="veld-hulp">(optioneel)</span></label>
                <input id="beschrijving" name="beschrijving" maxlength="250" value="<?= e($recept['beschrijving'] ?? '') ?>" placeholder="Wat maakt dit recept zo lekker?">
                <div class="recept-form-rij">
                    <div>
                        <label for="maaltijd">Maaltijd</label>
                        <select id="maaltijd" name="maaltijd" required>
                            <option value="">Kies een maaltijd</option>
                            <?php foreach (['ontbijt' => 'Ontbijt', 'lunch' => 'Lunch', 'avondeten' => 'Avondeten'] as $waarde => $label): ?>
                            <option value="<?= $waarde ?>" <?= ($recept['maaltijd'] ?? '') === $waarde ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="bereidingstijd">Tijd in minuten <span class="veld-hulp">(optioneel)</span></label>
                        <input id="bereidingstijd" type="number" name="bereidingstijd" min="1" max="1440" value="<?= e($recept['bereidingstijd'] ?? '') ?>" placeholder="30">
                    </div>
                </div>
                <div class="recept-form-rij recept-teksten">
                    <div>
                        <label for="ingredienten">Ingrediënten</label>
                        <p id="ingredienten-uitleg" class="veld-hulp">Eén per regel. Gebruik voor hoeveelheden: 200 | g | pasta.</p>
                        <textarea id="ingredienten" name="ingredienten" required rows="10" aria-describedby="ingredienten-uitleg" placeholder="200 | g | pasta&#10;2 | stuks | tomaat"><?= e($recept['ingredienten']) ?></textarea>
                    </div>
                    <div>
                        <label for="blog">Zo maak je het</label>
                        <p id="bereiding-uitleg" class="veld-hulp">Schrijf de stappen op. Zet er gerust je eigen tips bij.</p>
                        <textarea id="blog" name="blog" required rows="10" aria-describedby="bereiding-uitleg" placeholder="1. Zet een pan water op…"><?= e($recept['blog']) ?></textarea>
                    </div>
                </div>
                <fieldset class="foto-keuze">
                    <legend>Foto’s van je gerecht</legend>
                    <p class="veld-hulp">JPG, PNG, WebP of GIF. Bij bewerken blijven je huidige foto’s staan als je niets kiest.</p>
                    <div class="recept-form-rij">
                        <?php foreach (['picture' => ['foto', 'Hoofdfoto'], 'picture2' => ['foto2', 'Extra foto (optioneel)']] as $veld => [$kolom, $label]): ?>
                        <div>
                            <label for="<?= $veld ?>"><?= $label ?></label>
                            <?php if (!empty($recept[$kolom])): ?><img class="upload-preview" src="../<?= e($recept[$kolom]) ?>" alt="Huidige <?= e(strtolower($label)) ?>"><?php endif; ?>
                            <input type="file" id="<?= $veld ?>" name="<?= $veld ?>" accept="image/jpeg,image/png,image/webp,image/gif">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <div class="formulier-acties">
                    <button class="zoek-knop" type="submit"><?= $recept['id'] ? 'Wijzigingen bewaren' : 'Recept plaatsen' ?></button>
                    <a class="tekst-link" href="ReceptOverzicht.php">Annuleren</a>
                </div>
            </div>
        </form>
    </main>
    <?php $footerBasis = '../'; include __DIR__ . '/../includes/Footer.php'; ?>
</body>
</html>
