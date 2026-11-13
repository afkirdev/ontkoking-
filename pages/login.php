<?php
require __DIR__ . '/../Data/db.php';

$fout = '';
$registreren = ($_GET['actie'] ?? '') === 'registreren';
$naam = '';
if (empty($_SESSION['formulier_token'])) $_SESSION['formulier_token'] = bin2hex(random_bytes(32));
$melding = $_SESSION['login_melding'] ?? '';
unset($_SESSION['login_melding']);
$naLogin = $_SESSION['na_login'] ?? 'ReceptOverzicht.php';
if (!in_array($naLogin, ['ReceptOverzicht.php', 'Aanmakken.php'], true)) {
    $naLogin = 'ReceptOverzicht.php';
}

if (!empty($_SESSION['user_id'])) {
    header('Location: ' . $naLogin);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    $registreren = ($_POST['actie'] ?? '') === 'registreren';
    if (!hash_equals($_SESSION['formulier_token'], (string)($_POST['token'] ?? ''))) {
        $fout = 'Dit formulier is verlopen. Probeer het opnieuw.';
    } elseif ($naam === '' || $wachtwoord === '') {
        $fout = 'Vul je gebruikersnaam en wachtwoord in.';
    } elseif ($registreren && (strlen($naam) > 50 || strlen($naam) < 3)) {
        $fout = 'Kies een gebruikersnaam van 3 tot 50 tekens.';
    } elseif ($registreren && strlen($wachtwoord) < 8) {
        $fout = 'Kies een wachtwoord van minstens 8 tekens.';
    } elseif ($registreren && $wachtwoord !== ($_POST['herhaling'] ?? '')) {
        $fout = 'De wachtwoorden zijn niet hetzelfde.';
    } elseif ($registreren) {
        // nieuw account maken
        try {
            $stmt = $db->prepare('INSERT INTO users (gebruikersnaam, wachtwoord) VALUES (?, ?)');
            $stmt->execute([$naam, password_hash($wachtwoord, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['user_id'] = $db->lastInsertId();
            unset($_SESSION['na_login']);
            header('Location: ' . $naLogin);
            exit;
        } catch (PDOException $ex) {
            $fout = 'Die gebruikersnaam bestaat al.';
        }
    } else {
        // inloggen
        $stmt = $db->prepare('SELECT * FROM users WHERE gebruikersnaam = ?');
        $stmt->execute([$naam]);
        $user = $stmt->fetch();
        if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            unset($_SESSION['na_login']);
            header('Location: ' . $naLogin);
            exit;
        }
        $fout = 'Verkeerde gebruikersnaam of wachtwoord.';
    }
}

include 'login_view.php';
