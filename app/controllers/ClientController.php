<?php

class ClientController {

    //  Affichage des détails d'un client
    public function details($id) {
        $db = Database::connect();

        $stmt = $db->prepare("SELECT * FROM client WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$client) {
            http_response_code(404);
            echo "Client introuvable.";
            return;
        }

        //  Récupération des comptes associés
        $stmtComptes = $db->prepare("SELECT * FROM compte WHERE client_id = :client_id");
        $stmtComptes->execute(['client_id' => $id]);
        $comptes = $stmtComptes->fetchAll(PDO::FETCH_ASSOC) ?: [];

        //  Récupération des contrats associés
        $stmtContrats = $db->prepare("
            SELECT contrat.* 
            FROM contrat 
            INNER JOIN compte ON contrat.compte_id = compte.id 
            WHERE compte.client_id = :client_id
        ");
        $stmtContrats->execute(['client_id' => $id]);
        $contrats = $stmtContrats->fetchAll(PDO::FETCH_ASSOC) ?: [];

        require_once __DIR__ . '/../views/clients/details.php';
    }

    //  Modification du client
    public function edit($id) {
    $db = Database::connect();

    //  Récupérer les informations du client
    $stmt = $db->prepare("SELECT * FROM client WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        http_response_code(404);
        echo "Client introuvable.";
        return;
    }

    //  Mise à jour du client après soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $telephone = htmlspecialchars(trim($_POST['telephone']));
        $adresse = htmlspecialchars(trim($_POST['adresse']));

        if ($nom && $prenom && $email && $telephone && $adresse) {
            $stmt = $db->prepare("
                UPDATE client 
                SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, adresse = :adresse
                WHERE id = :id
            ");
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'adresse' => $adresse,
                'id' => $id
            ]);

            header('Location: ' . BASE_URL . 'index.php?url=dashboard');
            exit;
        }
    }

    require_once __DIR__ . '/../views/clients/edit.php';
}

public function create() {
    $db = Database::connect();

    // ✅ Récupération automatique du premier administrateur existant
    $stmt = $db->prepare("SELECT id FROM administrateur LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        die("Erreur : Aucun administrateur trouvé. Veuillez en créer un d'abord.");
    }

    $administrateur_id = $admin['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $telephone = htmlspecialchars(trim($_POST['telephone']));
        $adresse = htmlspecialchars(trim($_POST['adresse']));

        if ($nom && $prenom && $email && $telephone && $adresse) {
            $stmt = $db->prepare("
                INSERT INTO client (administrateur_id, nom, prenom, email, telephone, adresse) 
                VALUES (:administrateur_id, :nom, :prenom, :email, :telephone, :adresse)
            ");
            $stmt->execute([
                'administrateur_id' => $administrateur_id,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'adresse' => $adresse
            ]);

            header('Location: ' . BASE_URL . 'index.php?url=dashboard');
            exit;
        } else {
            echo "Tous les champs sont obligatoires.";
        }
    }

    require_once __DIR__ . '/../views/clients/create.php';
}

    // ✅ Suppression d'un client
    public function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM client WHERE id = :id");
        $stmt->execute(['id' => $id]);
        header('Location: ' . BASE_URL . 'index.php?url=dashboard');
        exit;
    }
}