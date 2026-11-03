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

$bovenste = array_slice($recepten, 0, 4);
$rest = array_slice($recepten, 4);

include __DIR__ . '/index_view.php';
