<?php
session_start();

// maakt de database aan als hij nog niet bestaat
$nieuw = !file_exists(__DIR__ . '/ontkoking.db');
$db = new PDO('sqlite:' . __DIR__ . '/ontkoking.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// SQLite controleert foreign keys alleen als je dit aanzet
$db->exec('PRAGMA foreign_keys = ON');

// de tabellen uit het ERD
// koppeltabellen (veel-op-veel) hebben een gecombineerde sleutel, geen eigen id
// SQLite kent geen ENUM, daarom een CHECK met de keuzes
if ($nieuw) {
    $db->exec(<<<'SQL'
CREATE TABLE users (
  id             INTEGER PRIMARY KEY AUTOINCREMENT,
  gebruikersnaam VARCHAR(50)  NOT NULL UNIQUE,
  wachtwoord     VARCHAR(255) NOT NULL,
  rol            VARCHAR(10)  NOT NULL DEFAULT 'gebruiker' CHECK (rol IN ('gebruiker', 'admin')),
  aangemaakt     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recepten (
  id             INTEGER PRIMARY KEY AUTOINCREMENT,
  titel          VARCHAR(100) NOT NULL,
  beschrijving   TEXT,
  blog           TEXT,
  foto           VARCHAR(255),
  foto2          VARCHAR(255),
  user_id        INTEGER      NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  bereidingstijd INTEGER,
  status         VARCHAR(15)  NOT NULL DEFAULT 'gepubliceerd' CHECK (status IN ('concept', 'gepubliceerd', 'gearchiveerd')),
  aangemaakt     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  bijgewerkt     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ingredienten (
  id   INTEGER PRIMARY KEY AUTOINCREMENT,
  naam VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE recept_ingredienten (
  recept_id     INTEGER NOT NULL REFERENCES recepten(id) ON DELETE CASCADE,
  ingredient_id INTEGER NOT NULL REFERENCES ingredienten(id),
  hoeveelheid   VARCHAR(20),
  eenheid       VARCHAR(20),
  positie       INTEGER,
  PRIMARY KEY (recept_id, ingredient_id)
);

CREATE TABLE categorieen (
  id   INTEGER PRIMARY KEY AUTOINCREMENT,
  naam VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE recept_categorieen (
  recept_id    INTEGER NOT NULL REFERENCES recepten(id) ON DELETE CASCADE,
  categorie_id INTEGER NOT NULL REFERENCES categorieen(id) ON DELETE CASCADE,
  PRIMARY KEY (recept_id, categorie_id)
);

CREATE TABLE reacties (
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  recept_id  INTEGER     NOT NULL REFERENCES recepten(id) ON DELETE CASCADE,
  user_id    INTEGER     NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  type       VARCHAR(10) NOT NULL DEFAULT 'reactie' CHECK (type IN ('reactie', 'tip', 'review')),
  inhoud     TEXT        NOT NULL,
  aangemaakt DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE recept_stemmen (
  recept_id  INTEGER    NOT NULL REFERENCES recepten(id) ON DELETE CASCADE,
  user_id    INTEGER    NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  stem       VARCHAR(6) NOT NULL CHECK (stem IN ('omhoog', 'omlaag')),
  aangemaakt DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (recept_id, user_id)
);

INSERT INTO categorieen (naam) VALUES ('Ontbijt'), ('Lunch'), ('Avondeten');
SQL);
}

function e($tekst) {
    return htmlspecialchars((string)$tekst, ENT_QUOTES, 'UTF-8');
}

// stuurt je naar login als je niet ingelogd bent
function moetInloggen() {
    global $db;

    // bestaat de gebruiker nog in de database? (bijvoorbeeld na een nieuwe database)
    if (!empty($_SESSION['user_id'])) {
        $stmt = $db->prepare('SELECT id FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            unset($_SESSION['user_id']);
        }
    }

    if (empty($_SESSION['user_id'])) {
        $pagina = basename($_SERVER['SCRIPT_NAME'] ?? '');
        if (in_array($pagina, ['ReceptOverzicht.php', 'Aanmakken.php'], true)) {
            $_SESSION['na_login'] = $pagina;
        }
        $_SESSION['login_melding'] = 'Log eerst in of maak een account aan om je recepten te bekijken of een recept aan te maken.';
        header('Location: ../pages/login.php');
        exit;
    }
}

// slaat een geuploade foto op in assets/uploads en geeft het pad terug
function uploadFoto($naam) {
    if (empty($_FILES[$naam]['name']) || $_FILES[$naam]['error'] !== 0) {
        return null;
    }
    $ext = strtolower(pathinfo($_FILES[$naam]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        return null;
    }
    $pad = 'Uploads/' . uniqid() . '.' . $ext;
    move_uploaded_file($_FILES[$naam]['tmp_name'], __DIR__ . '/../' . $pad);
    return $pad;
}
