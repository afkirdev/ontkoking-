<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $registreren ? 'Registreren' : 'Inloggen' ?> — Ontkoking</title>
    <link rel="stylesheet" href="../CSS/Home.css">
    <script src="../scripts/home.js" defer></script>
</head>
<body>
    <?php $terug = '../'; $paginaTitel = $registreren ? 'Registreren' : 'Inloggen'; include __DIR__ . '/../includes/Header.php'; ?>
    <main class="page-body">
        <div class="account-layout">
            <aside class="account-intro">
                <p class="sectie-label">Jouw eigen kookboek</p>
                <h2>Dat ene recept?<br>Bewaar het hier.</h2>
                <p>Deel wat je graag kookt, met je eigen foto’s en tips voor aan tafel.</p>
                <img src="../assets/recepten/pasta.jpg" alt="Pasta met pesto en tomaat" width="1000" height="667">
            </aside>
            <section class="account-formulier">
                <nav class="account-tabs" aria-label="Account">
                    <a href="login.php" <?= !$registreren ? 'aria-current="page"' : '' ?>>Inloggen</a>
                    <a href="login.php?actie=registreren" <?= $registreren ? 'aria-current="page"' : '' ?>>Registreren</a>
                </nav>
                <h2><?= $registreren ? 'Maak een account' : 'Welkom terug' ?></h2>
                <p><?= $registreren ? 'Een account is genoeg om je eigen recepten te plaatsen.' : 'Log in om verder te gaan met je recepten.' ?></p>
                <?php if ($melding): ?><p class="formulier-melding" role="status"><?= e($melding) ?></p><?php endif; ?>
                <?php if ($fout): ?><p class="formulier-melding" role="alert"><?= e($fout) ?></p><?php endif; ?>
                <form method="post" class="net-formulier">
                    <input type="hidden" name="token" value="<?= e($_SESSION['formulier_token']) ?>">
                    <input type="hidden" name="actie" value="<?= $registreren ? 'registreren' : 'inloggen' ?>">
                    <label for="gebruikersnaam">Gebruikersnaam</label>
                    <input id="gebruikersnaam" name="gebruikersnaam" value="<?= e($naam) ?>" autocomplete="username" maxlength="50" <?= $registreren ? 'minlength="3"' : '' ?> required>
                    <label for="wachtwoord">Wachtwoord</label>
                    <input type="password" id="wachtwoord" name="wachtwoord" autocomplete="<?= $registreren ? 'new-password' : 'current-password' ?>" <?= $registreren ? 'minlength="8" aria-describedby="wachtwoord-hulp"' : '' ?> required>
                    <?php if ($registreren): ?>
                        <p id="wachtwoord-hulp" class="veld-hulp">Gebruik minstens 8 tekens.</p>
                        <label for="herhaling">Herhaal je wachtwoord</label>
                        <input type="password" id="herhaling" name="herhaling" autocomplete="new-password" minlength="8" required>
                    <?php endif; ?>
                    <button class="zoek-knop" type="submit"><?= $registreren ? 'Account maken' : 'Inloggen' ?></button>
                </form>
            </section>
        </div>
    </main>
    <?php $footerBasis = '../'; include __DIR__ . '/../includes/Footer.php'; ?>
</body>
</html>
