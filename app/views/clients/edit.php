<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../app/models/Database.php';

// ✅ Récupération de l'ID du client dans l'URL
$clientId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($clientId > 0) {
    $db = Database::connect();

    // ✅ Sélectionner le client par son ID
    $stmt = $db->prepare("SELECT * FROM client WHERE id = :id");
    $stmt->bindValue(':id', $clientId, PDO::PARAM_INT);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Erreur : Client non trouvé.");
    }
}

// ✅ Mise à jour des informations après soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($telephone) && !empty($adresse)) {
        try {
            $stmt = $db->prepare("
                UPDATE client 
                SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, adresse = :adresse 
                WHERE id = :id
            ");
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindValue(':adresse', $adresse, PDO::PARAM_STR);
            $stmt->bindValue(':id', $clientId, PDO::PARAM_INT);
            $stmt->execute();

            // ✅ Fermeture de la connexion
            $db = null;

            // ✅ Redirection après mise à jour
            header("Location: " . BASE_URL . "index.php?url=dashboard");
            exit();

        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    } else {
        $error = "Tous les champs sont requis.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Client</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
</head>
<body>

<!-- ✅ Barre de navigation -->
<nav class="navbar">
    <div class="container">
        <a href="<?= BASE_URL ?>index.php" class="logo">Tableau de Bord</a>
        <div class="nav-links">
            <a href="<?= BASE_URL ?>app/views/clients/create.php" class="btn-add">
                <i class="fas fa-plus"></i> Nouveau Client
            </a>
            <a href="<?= BASE_URL ?>logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="table-title">Modifier le Client</h1>

    <!-- ✅ Message d'erreur -->
    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="form-container">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($client['nom'] ?? '') ?>" required>

        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($client['prenom'] ?? '') ?>" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($client['email'] ?? '') ?>" required>

        <label for="telephone">Téléphone</label>
        <input type="text" name="telephone" id="telephone" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>" required>

        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars($client['adresse'] ?? '') ?>" required>

        <div class="action-buttons">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Modifier
            </button>
            <a href="<?= BASE_URL ?>index.php?url=dashboard" class="btn-cancel">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

</body>
</html>