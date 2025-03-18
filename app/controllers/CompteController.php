<?php

class CompteController {

    // Modifier un compte

    public function index() {
        // Implementation of the index method
    }
    public function edit($id) {
        $db = Database::connect();

        // Vérification si le compte existe
        $stmt = $db->prepare("SELECT * FROM compte WHERE id_compte = :id");
        $stmt->execute(['id' => intval($id)]);
        $compte = $stmt->fetch();

        if (!$compte) {
            die("Compte introuvable !");
        }

        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? null;
            $solde = $_POST['solde'] ?? null;
            $client_id = $_POST['client_id'] ?? null;

            // Validation des données
            if (empty($type) || empty($solde) || empty($client_id)) {
                die("Tous les champs sont obligatoires !");
            }

            // Vérification du type de compte
            $typesAutorises = ['courant', 'épargne'];
            if (!in_array($type, $typesAutorises)) {
                die("Type de compte invalide !");
            }

            // Vérification du solde
            if (!is_numeric($solde) || $solde < 0) {
                die("Le solde doit être un nombre positif !");
            }

            // Mise à jour dans la base de données
            $stmt = $db->prepare("UPDATE compte SET type_compte = :type, solde = :solde WHERE id_compte = :id");
            $stmt->execute([
                'type' => htmlspecialchars($type),
                'solde' => floatval($solde),
                'id' => intval($id)
            ]);

            // Redirection après la mise à jour
            header('Location: index.php?url=client-details&id=' . intval($client_id));
            exit();
        }

        require_once 'app/views/comptes/edit.php';
    }

    // Supprimer un compte
    public function delete($id) {
        $db = Database::connect();

        // Vérification si le compte existe
        $stmt = $db->prepare("SELECT * FROM compte WHERE id_compte = :id");
        $stmt->execute(['id' => intval($id)]);
        $compte = $stmt->fetch();

        if (!$compte) {
            die("Compte introuvable !");
        }

        // Suppression dans la base de données
        $stmt = $db->prepare("DELETE FROM compte WHERE id_compte = :id");
        $stmt->execute(['id' => intval($id)]);

        // Redirection après suppression
        header('Location: index.php?url=clients');
        exit();
    }
}