<?php
require __DIR__ . '/../Data/db.php';
moetInloggen();

// als er een id is dan bewerk je een recept, anders maak je een nieuwe
$recept = ['id' => '', 'titel' => '', 'ingredienten' => '', 'blog' => '', 'maaltijd' => ''];
if (!empty($_GET['id'])) {
    $userStmt = $db->prepare('SELECT rol FROM users WHERE id = ?');
    $userStmt->execute([$_SESSION['user_id']]);

    $isAdmin = $userStmt->fetchColumn() === 'admin';

    if ($isAdmin) {
        $stmt = $db->prepare('SELECT * FROM recepten WHERE id = ?');
        $stmt->execute([$_GET['id']]);
    } else {
        $stmt = $db->prepare('SELECT * FROM recepten WHERE id = ? AND user_id = ?');
        $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    }

    $recept = $stmt->fetch();
    if (!$recept) {
        header('Location: ReceptOverzicht.php');
        exit;
    }

    // maaltijd ophalen uit de koppeltabel
    $stmt = $db->prepare('SELECT LOWER(categorieen.naam) FROM recept_categorieen
                          INNER JOIN categorieen ON categorieen.id = recept_categorieen.categorie_id
                          WHERE recept_categorieen.recept_id = ?');
    $stmt->execute([$recept['id']]);
    $recept['maaltijd'] = $stmt->fetchColumn();

    // ingredienten terug in het tekstvak zetten, een per regel
    $stmt = $db->prepare('SELECT recept_ingredienten.hoeveelheid, recept_ingredienten.eenheid, ingredienten.naam FROM recept_ingredienten
                          INNER JOIN ingredienten ON ingredienten.id = recept_ingredienten.ingredient_id
                          WHERE recept_ingredienten.recept_id = ?
                          ORDER BY recept_ingredienten.positie');
    $stmt->execute([$recept['id']]);
    $recept['ingredienten'] = '';
    foreach ($stmt->fetchAll() as $ingredient) {
        if ($ingredient['hoeveelheid'] != '' || $ingredient['eenheid'] != '') {
            $recept['ingredienten'] .= $ingredient['hoeveelheid'] . ' | ' . $ingredient['eenheid'] . ' | ' . $ingredient['naam'] . "\n";
        } else {
            $recept['ingredienten'] .= $ingredient['naam'] . "\n";
        }
    }
}

include 'Aanmakken_view.php';
