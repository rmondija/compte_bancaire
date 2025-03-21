<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../app/models/Database.php';

// ✅ Récupération de l'ID du client dans l'URL
$clientId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($clientId > 0) {
    try {
        $db = Database::connect();

        // ✅ Vérification de l'existence du client
        $stmt = $db->prepare("SELECT * FROM client WHERE id = :id");
        $stmt->execute(['id' => $clientId]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$client) {
            die("Erreur : Client introuvable.");
        }

        // ✅ Suppression du client
        $stmt = $db->prepare("DELETE FROM client WHERE id = :id");
        $stmt->execute(['id' => $clientId]);

        // ✅ Redirection après suppression
        header("Location: " . BASE_URL . "index.php?url=dashboard");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    die("ID client invalide.");
}