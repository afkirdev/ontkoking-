<?php
// $terug is '' op index.php en '../' op de pagina's in pages/
// $paginaTitel geeft op vervolgpagina's aan waar je bent.
$terug = $terug ?? '';
$paginaTitel = $paginaTitel ?? 'Ontkoking';
?>
    <header class="topbar recepten-topbar">
        <div class="topbar-left">
            <button class="menu-button" id="menuButton" aria-label="Open menu" aria-expanded="false" aria-controls="navMenu">
                <span></span>
            </button>
            <div class="header-identiteit">
                <a class="header-merk" href="<?= $terug ?>index.php" aria-label="Ontkoking — home">Ontkoking<span aria-hidden="true">.</span></a>
                <?php if ($paginaTitel !== 'Ontkoking'): ?>
                    <h1 class="header-paginanaam"><?= e($paginaTitel) ?></h1>
                <?php else: ?>
                    <p class="header-slogan">Een beetje inspiratie. Iets lekkers op tafel.</p>
                <?php endif; ?>
            </div>
            <?php $menuBasis = $terug; include __DIR__ . '/menu.php'; ?>
        </div>
        <?php if (empty($_SESSION['user_id'])): ?>
        <a class="header-link" href="<?= $terug ?>pages/login.php">login</a>
        <?php else: ?>
        <a class="header-link" href="<?= $terug ?>Data/uitloggen.php">uitloggen</a>
        <?php endif; ?>
    </header>
