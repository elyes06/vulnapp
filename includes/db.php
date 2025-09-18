<?php
// includes/db.php
require_once __DIR__ . '/../config.php';

// Connexion SQLite
try {
    $conn = new PDO('sqlite:' . __DIR__ . '/../' . DB_NAME);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Encodage UTF-8
    $conn->exec("PRAGMA encoding = 'UTF-8';");
} catch (PDOException $e) {
    die("SQLite connexion échouée : " . $e->getMessage());
}
