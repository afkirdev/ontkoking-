<?php
// Eenmalig uitvoeren via PHP CLI; bestaande recepten worden nooit overschreven.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require __DIR__ . '/../Data/db.php';
$voorbeelden = [
    [
        'titel' => 'Pasta met pesto en tomaat', 'maaltijd' => 'Avondeten', 'tijd' => 20,
        'beschrijving' => 'Een pan pasta, een lepel pesto en zoete tomaatjes. Meer hoeft het niet te zijn.',
        'foto' => 'assets/recepten/pasta.jpg',
        'ingredienten' => [['200', 'g', 'pasta'], ['3', 'el', 'groene pesto'], ['200', 'g', 'cherrytomaat'], ['40', 'g', 'rucola'], ['30', 'g', 'Parmezaanse kaas'], ['1', 'snuf', 'zout']],
        'blog' => "Voor 2 personen.\n\n1. Breng een ruime pan water aan de kook, voeg zout toe en kook de pasta volgens de verpakking.\n\n2. Halveer ondertussen de tomaatjes. Was de rucola en laat goed uitlekken.\n\n3. Bewaar een kopje kookwater en giet de pasta af. Meng de warme pasta met de pesto en een klein scheutje kookwater, zodat de saus zich goed verdeelt.\n\n4. Schep de tomaatjes en rucola erdoor. Verdeel over twee borden en rasp de kaas erover.\n\nOok lekker als lunch: laat de pasta afkoelen en voeg de rucola pas vlak voor het eten toe.",
    ],
    [
        'titel' => 'Kleine pannenkoeken met banaan', 'maaltijd' => 'Ontbijt', 'tijd' => 25,
        'beschrijving' => 'Een stapeltje voor een ochtend waarop je wat meer tijd hebt.',
        'foto' => 'assets/recepten/pannenkoeken.jpg',
        'ingredienten' => [['150', 'g', 'bloem'], ['200', 'ml', 'melk'], ['1', 'stuk', 'ei'], ['1', 'tl', 'bakpoeder'], ['1', 'el', 'suiker'], ['1', 'stuk', 'banaan'], ['75', 'g', 'blauwe bessen'], ['20', 'g', 'boter'], ['2', 'el', 'ahornsiroop']],
        'blog' => "Voor 2 personen, ongeveer 8 kleine pannenkoeken.\n\n1. Meng bloem, bakpoeder en suiker in een kom. Klop in een andere kom het ei met de melk los.\n\n2. Giet het melkmengsel bij de bloem en roer kort tot een dik beslag. Een paar kleine klontjes mogen blijven. Laat 5 minuten staan.\n\n3. Verwarm een koekenpan op middelhoog vuur met een beetje boter. Schep per pannenkoek twee eetlepels beslag in de pan.\n\n4. Draai om zodra er belletjes aan de bovenkant verschijnen en de randjes steviger worden. Bak de andere kant nog ongeveer een minuut, tot het midden gaar is. Herhaal met de rest van het beslag.\n\n5. Snijd de banaan in plakjes. Serveer de pannenkoeken met banaan, gewassen bessen en ahornsiroop.",
    ],
    [
        'titel' => 'Toast met avocado en ei', 'maaltijd' => 'Lunch', 'tijd' => 10,
        'beschrijving' => 'Knapperig brood, avocado en een gebakken eitje erbovenop.',
        'foto' => 'assets/recepten/ontbijt.jpg',
        'ingredienten' => [['2', 'sneetjes', 'volkorenbrood'], ['1', 'stuk', 'avocado'], ['2', 'stuks', 'ei'], ['1', 'tl', 'citroensap'], ['1', 'el', 'olijfolie'], ['1', 'snuf', 'chilivlokken'], ['1', 'snuf', 'zout'], ['1', 'snuf', 'peper']],
        'blog' => "Voor 2 toasts.\n\n1. Rooster het brood in een broodrooster of droge koekenpan.\n\n2. Halveer de avocado en verwijder de pit. Schep het vruchtvlees in een kom en prak met citroensap, zout en peper.\n\n3. Verhit de olijfolie in een koekenpan. Breek de eieren erin en bak tot het eiwit gestold is. Bak de dooier naar smaak mee.\n\n4. Verdeel de avocado over het brood en leg op elke toast een ei. Strooi er wat chilivlokken over en eet meteen, terwijl het brood nog knapperig is.",
    ],
];
$db->beginTransaction();
try {
    $zoekAuteur = $db->prepare('SELECT id FROM users WHERE gebruikersnaam = ?');
    $zoekAuteur->execute(['Ontkoking']);
    $auteur = $zoekAuteur->fetchColumn();
    if (!$auteur) {
        // Alleen een auteursnaam voor de voorbeelden; geen gedeeld inlogwachtwoord of adminrol.
        $db->prepare('INSERT INTO users (gebruikersnaam, wachtwoord) VALUES (?, ?)')->execute(['Ontkoking', password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT)]);
        $auteur = $db->lastInsertId();
    }
    $aantal = 0;
    foreach ($voorbeelden as $voorbeeld) {
        $bestaand = $db->prepare('SELECT id FROM recepten WHERE titel = ? AND user_id = ?');
        $bestaand->execute([$voorbeeld['titel'], $auteur]);
        if ($bestaand->fetchColumn()) continue;
        $db->prepare('INSERT INTO recepten (titel, beschrijving, blog, foto, user_id, bereidingstijd) VALUES (?, ?, ?, ?, ?, ?)')->execute([$voorbeeld['titel'], $voorbeeld['beschrijving'], $voorbeeld['blog'], $voorbeeld['foto'], $auteur, $voorbeeld['tijd']]);
        $id = $db->lastInsertId();
        $categorie = $db->prepare('SELECT id FROM categorieen WHERE naam = ?');
        $categorie->execute([$voorbeeld['maaltijd']]);
        $db->prepare('INSERT INTO recept_categorieen VALUES (?, ?)')->execute([$id, $categorie->fetchColumn()]);
        foreach ($voorbeeld['ingredienten'] as $positie => [$hoeveelheid, $eenheid, $naam]) {
            $db->prepare('INSERT OR IGNORE INTO ingredienten (naam) VALUES (?)')->execute([$naam]);
            $zoek = $db->prepare('SELECT id FROM ingredienten WHERE naam = ?');
            $zoek->execute([$naam]);
            $db->prepare('INSERT INTO recept_ingredienten (recept_id, ingredient_id, hoeveelheid, eenheid, positie) VALUES (?, ?, ?, ?, ?)')->execute([$id, $zoek->fetchColumn(), $hoeveelheid, $eenheid, $positie + 1]);
        }
        $aantal++;
    }
    $db->commit();
    echo "$aantal voorbeeldrecepten toegevoegd.\n";
} catch (Throwable $fout) {
    $db->rollBack();
    throw $fout;
}
