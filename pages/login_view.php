<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/Home.css">
    <script src="../scripts/home.js" defer></script>
</head>
<body>
    <?php $terug = '../'; $paginaTitel = 'login'; include __DIR__ . '/../includes/Header.php'; ?>

    <div class="page-body">

    <div class="content">
        <div class="card">
            <h1>Login</h1>
            <?php if ($melding): ?><p role="status"><?= e($melding) ?></p><?php endif; ?>
            <?php if ($fout): ?><p><?= e($fout) ?></p><?php endif; ?>
            <form method="post">
                Gebruikersnaam:<br>
                <input type="text" name="gebruikersnaam" required><br>
                Wachtwoord:<br>
                <input type="password" name="wachtwoord" required><br><br>
                <input type="submit" value="Inloggen">
                <input type="submit" name="registreren" value="Registreren">
            </form>
        </div>
    </div>
    </div>

    <?php $footerBasis = '../'; include __DIR__ . '/../includes/Footer.php'; ?>
</body>
</html>
