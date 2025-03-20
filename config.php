<?php
$host = 'localhost';
$db = 'citoyens_agites';
$user = 'root';
$pass = '';

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>