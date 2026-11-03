<?php
require __DIR__ . '/../Data/db.php';
moetInloggen();

    $userStmt = $db->prepare('SELECT rol FROM users WHERE id = ?');
    $userStmt->execute([$_SESSION['user_id']]);

    $isAdmin = $userStmt->fetchColumn() === 'admin';

    if ($isAdmin) {
        $stmt = $db->prepare('SELECT * FROM recepten ORDER BY id DESC');
        $stmt->execute();
    } else {
        $stmt = $db->prepare('SELECT * FROM recepten WHERE user_id = ? ORDER BY id DESC');
        $stmt->execute([$_SESSION['user_id']]);
    }


$recepten = $stmt->fetchAll();

include 'ReceptOverzicht_view.php';
