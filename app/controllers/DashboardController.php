<?php

class DashboardController {

    public function index() {
        try {
            // ✅ Connexion à la base de données
            $db = Database::connect();

            // ✅ Requête pour récupérer la liste des clients
            $stmt = $db->prepare("SELECT * FROM client");
            $stmt->execute();
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des clients : " . $e->getMessage());
            $clients = [];
        }

        // ✅ Inclusion correcte du fichier de vue
        require_once __DIR__ . '/../views/dashboard.php';
    }
}