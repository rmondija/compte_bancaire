<?php
// ✅ Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'compte_bancaire');
define('DB_USER', 'root');
define('DB_PASS', '');

// ✅ Définir automatiquement le chemin de base
define('BASE_URL', 'http://localhost/compte_bancaire/');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=compte_bancaire', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
// ✅ Fonction globale pour la connexion PDO
function getDatabaseConnection() {
    global $pdo;
    return $pdo;
}