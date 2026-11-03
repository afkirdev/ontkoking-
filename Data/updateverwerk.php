<?php
require 'db.php';
moetInloggen();

$id = $_POST['id'] ?? '';
$titel = trim($_POST['titel'] ?? '');
$ingredienten = trim($_POST['ingredienten'] ?? '');
$blog = trim($_POST['blog'] ?? '');
$maaltijd = $_POST['maaltijd'] ?? '';

if (!in_array($maaltijd, ['ontbijt', 'lunch', 'avondeten'], true)) {
    http_response_code(400);
    exit('Kies ontbijt, lunch of avondeten. Ga terug naar het formulier en kies een maaltijd.');
}

if ($titel == '') {
    header('Location: ../pages/Aanmakken.php?id=' . $id);
    exit;
}

$foto = uploadFoto('picture');
$foto2 = uploadFoto('picture2');

if ($id == '') {
    // nieuw recept
    $stmt = $db->prepare('INSERT INTO recepten (titel, blog, foto, foto2, user_id) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$titel, $blog, $foto, $foto2, $_SESSION['user_id']]);
    $id = $db->lastInsertId();
} else {
    // recept bewerken alleen je eigen recepten

    $userStmt = $db->prepare('SELECT rol FROM users WHERE id = ?');
    $userStmt->execute([$_SESSION['user_id']]);
    $isAdmin = $userStmt->fetchColumn() === 'admin';

    if ($isAdmin) {
        $stmt = $db->prepare('UPDATE recepten SET titel = ?, blog = ?, bijgewerkt = CURRENT_TIMESTAMP WHERE id = ?');
        $stmt->execute([$titel, $blog, $id]);
    } else {
        $stmt = $db->prepare('UPDATE recepten SET titel = ?, blog = ?, bijgewerkt = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?');
        $stmt->execute([$titel, $blog, $id, $_SESSION['user_id']]);
    }



    // niks aangepast betekent dat het niet jouw recept is
    if ($stmt->rowCount() == 0) {
        header('Location: ../pages/ReceptOverzicht.php');
        exit;
    }

    // fotos alleen vervangen als er een nieuwe is
    if ($foto) {
        $db->prepare('UPDATE recepten SET foto = ? WHERE id = ? AND user_id = ?')->execute([$foto, $id, $_SESSION['user_id']]);
    }
    if ($foto2) {
        $db->prepare('UPDATE recepten SET foto2 = ? WHERE id = ? AND user_id = ?')->execute([$foto2, $id, $_SESSION['user_id']]);
    }

    // oude maaltijd en ingredienten weghalen, hieronder komen ze er opnieuw in
    $db->prepare('DELETE FROM recept_categorieen WHERE recept_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM recept_ingredienten WHERE recept_id = ?')->execute([$id]);
}

// maaltijd opslaan in de koppeltabel recept_categorieen
$stmt = $db->prepare('SELECT id FROM categorieen WHERE LOWER(naam) = ?');
$stmt->execute([$maaltijd]);
$categorieId = $stmt->fetchColumn();
$db->prepare('INSERT INTO recept_categorieen (recept_id, categorie_id) VALUES (?, ?)')->execute([$id, $categorieId]);

// ingredienten: een per regel, "hoeveelheid | eenheid | naam" of alleen de naam
$positie = 1;
foreach (explode("\n", $ingredienten) as $regel) {
    $delen = explode('|', $regel);
    if (count($delen) == 3) {
        $hoeveelheid = trim($delen[0]);
        $eenheid = trim($delen[1]);
        $naam = trim($delen[2]);
    } else {
        $hoeveelheid = '';
        $eenheid = '';
        $naam = trim($regel);
    }

    if ($naam == '') {
        continue;
    }

    // ingredient toevoegen als het nog niet bestaat en dan het id ophalen
    $db->prepare('INSERT OR IGNORE INTO ingredienten (naam) VALUES (?)')->execute([$naam]);
    $stmt = $db->prepare('SELECT id FROM ingredienten WHERE naam = ?');
    $stmt->execute([$naam]);
    $ingredientId = $stmt->fetchColumn();

    // OR IGNORE: hetzelfde ingredient twee keer in een recept kan niet door de gecombineerde sleutel
    $stmt = $db->prepare('INSERT OR IGNORE INTO recept_ingredienten (recept_id, ingredient_id, hoeveelheid, eenheid, positie) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$id, $ingredientId, $hoeveelheid, $eenheid, $positie]);
    $positie++;
}

header('Location: ../pages/ReceptOverzicht.php');
