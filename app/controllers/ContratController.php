<?php

class ContratController {

    public function index() {
        // Implementation of the index method
        echo "ContratController index method called";
    }
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $montant = $_POST['montant'];
            $duree = $_POST['duree'];

            $stmt = Database::connect()->prepare("UPDATE contrat SET type = :type, montant = :montant, duree = :duree WHERE id_contrat = :id");
            $stmt->execute([
                'type' => $type,
                'montant' => $montant,
                'duree' => $duree,
                'id' => $id
            ]);

            header('Location: index.php?url=client-details&id=' . $_POST['client_id']);
            exit();
        }

        $stmt = Database::connect()->prepare("SELECT * FROM contrat WHERE id_contrat = :id");
        $stmt->execute(['id' => $id]);
        $contrat = $stmt->fetch();

        require_once 'app/views/contrats/edit.php';
    }

    public function delete($id) {
        $stmt = Database::connect()->prepare("DELETE FROM contrat WHERE id_contrat = :id");
        $stmt->execute(['id' => $id]);

        header('Location: index.php?url=clients');
        exit();
    }
}