<footer class="site-footer recepten-footer">
    <div class="footer-inhoud">
        <div class="footer-intro">
            <p><strong>Ontkoking</strong></p>
            <p>Recepten uit verschillende culturen om samen met familie te maken en te eten.</p>
        </div>
        <nav class="site-footer-nav" aria-label="Footernavigatie">
            <h2 class="footer-kop">Ontdek</h2>
            <a href="<?= $footerBasis ?>index.php">Home</a>
            <a href="<?= $footerBasis ?>pages/ReceptOverzicht.php">Je recepten</a>
            <a href="<?= $footerBasis ?>pages/Aanmakken.php">Recept aanmaken</a>
        </nav>
        <div class="footer-maaltijden">
            <h2 class="footer-kop">Voor elk moment</h2>
            <ul aria-label="Maaltijden">
                <li>Ontbijt</li>
                <li>Lunch</li>
                <li>Avondeten</li>
            </ul>
        </div>
    </div>
    <div class="footer-onderkant">
        <p>Samen koken, samen genieten.</p>
        <button class="muziek-knop" id="muziekKnop" type="button" aria-controls="achtergrondmuziek" aria-pressed="false" hidden>
            Muziek afspelen
        </button>
    </div>
    <audio id="achtergrondmuziek" autoplay loop preload="none">
        <source src="<?= $footerBasis ?>assets/audio/restaurant-jazz.mp3" type="audio/mpeg">
    </audio>
</footer>
