<?php
require 'db.php';
moetInloggen();

// je kan alleen je eigen recepten verwijderen
$userStmt = $db->prepare('SELECT rol FROM users WHERE id = ?');
$userStmt->execute([$_SESSION['user_id']]);
$isAdmin = $userStmt->fetchColumn() === 'admin';

if ($isAdmin) {
    $stmt = $db->prepare('DELETE FROM recepten WHERE id = ?');
    $stmt->execute([$_GET['id'] ?? 0]);
} else {
    $stmt = $db->prepare('DELETE FROM recepten WHERE id = ? AND user_id = ?');
    $stmt->execute([$_GET['id'] ?? 0, $_SESSION['user_id']]);
}

header('Location: ../pages/ReceptOverzicht.php');
