<?php
require __DIR__ . '/../Data/db.php';

$stmt = $db->prepare('SELECT recepten.*, users.gebruikersnaam FROM recepten JOIN users ON users.id = recepten.user_id WHERE recepten.id = ?');
$stmt->execute([$_GET['id'] ?? 0]);
$recept = $stmt->fetch();

if (!$recept) {
    header('Location: ../index.php');
    exit;
}

// maaltijd ophalen uit de koppeltabel
$stmt = $db->prepare('SELECT categorieen.naam FROM recept_categorieen
                      INNER JOIN categorieen ON categorieen.id = recept_categorieen.categorie_id
                      WHERE recept_categorieen.recept_id = ?');
$stmt->execute([$recept['id']]);
$recept['maaltijd'] = $stmt->fetchColumn();

// ingredienten ophalen, een per regel
$stmt = $db->prepare('SELECT recept_ingredienten.hoeveelheid, recept_ingredienten.eenheid, ingredienten.naam FROM recept_ingredienten
                      INNER JOIN ingredienten ON ingredienten.id = recept_ingredienten.ingredient_id
                      WHERE recept_ingredienten.recept_id = ?
                      ORDER BY recept_ingredienten.positie');
$stmt->execute([$recept['id']]);
$recept['ingredienten'] = '';
foreach ($stmt->fetchAll() as $ingredient) {
    $recept['ingredienten'] .= trim($ingredient['hoeveelheid'] . ' ' . $ingredient['eenheid'] . ' ' . $ingredient['naam']) . "\n";
}

include 'recept_view.php';
