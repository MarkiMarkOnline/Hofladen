<?php
/**
 * Datenbankverbindung (PDO)
 * 
 * Diese Datei wird einmalig eingebunden und stellt $pdo bereit.
 */

$host = "127.0.0.1";
$port = 3306;
$db   = "db_hofladen";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB-Verbindung fehlgeschlagen: " . $e->getMessage());
}
