<?php
require_once __DIR__ . '/Data/db.php';

$filter = is_string($_GET['filter'] ?? null) ? trim($_GET['filter']) : '';
$zoekterm = is_string($_GET['zoek'] ?? null) ? trim($_GET['zoek']) : '';
$ingredientenFilter = is_string($_GET['ingredienten'] ?? null) ? trim($_GET['ingredienten']) : '';
$voorwaarden = [];
$parameters = [];

if ($filter !== '') {
    $voorwaarden[] = 'EXISTS (SELECT 1 FROM recept_categorieen rc JOIN categorieen c ON c.id = rc.categorie_id WHERE rc.recept_id = recepten.id AND c.naam = ?)';
    $parameters[] = $filter;
}

if ($zoekterm !== '') {
    $voorwaarden[] = "(instr(lower(titel), lower(?)) > 0 OR instr(lower(COALESCE(beschrijving, '')), lower(?)) > 0)";
    array_push($parameters, $zoekterm, $zoekterm);
}

// Elk opgegeven ingrediënt moet in het recept voorkomen.
foreach (array_unique(array_filter(array_map('trim', explode(',', $ingredientenFilter)), static fn($naam) => $naam !== '')) as $ingredient) {
    $voorwaarden[] = 'EXISTS (SELECT 1 FROM recept_ingredienten ri JOIN ingredienten i ON i.id = ri.ingredient_id WHERE ri.recept_id = recepten.id AND instr(lower(i.naam), lower(?)) > 0)';
    $parameters[] = $ingredient;
}

$sql = 'SELECT recepten.*, (SELECT c.naam FROM categorieen c JOIN recept_categorieen rc ON rc.categorie_id = c.id WHERE rc.recept_id = recepten.id LIMIT 1) AS categorie FROM recepten';
if ($voorwaarden) $sql .= ' WHERE ' . implode(' AND ', $voorwaarden);
$stmt = $db->prepare($sql . ' ORDER BY recepten.id DESC');
$stmt->execute($parameters);
$recepten = $stmt->fetchAll();

include __DIR__ . '/index_view.php';
