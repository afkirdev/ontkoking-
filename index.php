<?php
require_once __DIR__ . '/Data/db.php';

$filter = $_GET['filter'] ?? '';

if ($filter != '') {
    $stmt = $db->prepare('SELECT recepten.* FROM recepten
                          INNER JOIN recept_categorieen ON recept_categorieen.recept_id = recepten.id
                          INNER JOIN categorieen ON categorieen.id = recept_categorieen.categorie_id
                          WHERE categorieen.naam = ?
                          ORDER BY recepten.id DESC');
    $stmt->execute([$filter]);
    $recepten = $stmt->fetchAll();
} else {
    $recepten = $db->query('SELECT * FROM recepten ORDER BY id DESC')->fetchAll();
}

$zoek = $_GET['zoek'] ?? '';
if ($zoek != '') {
    $gevonden = [];
    foreach ($recepten as $recept) {
        $stmt = $db->prepare('SELECT ingredienten.naam FROM recept_ingredienten INNER JOIN ingredienten ON ingredienten.id = recept_ingredienten.ingredient_id WHERE recept_ingredienten.recept_id = ?');
        $stmt->execute([$recept['id']]);
        $ingredienten = implode(' ', $stmt->fetchAll(PDO::FETCH_COLUMN));

        if (stripos($recept['titel'] . ' ' . $ingredienten, $zoek) !== false) {
            $gevonden[] = $recept;
        }
    }
    $recepten = $gevonden;
}

$bovenste = array_slice($recepten, 0, 4);
$rest = array_slice($recepten, 4);

include __DIR__ . '/index_view.php';
