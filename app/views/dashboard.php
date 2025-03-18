<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/models/Database.php';

$db = Database::connect();

try {
    $stmt = $db->prepare("SELECT * FROM client");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des clients : " . $e->getMessage());
    $clients = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="container">
    <h1 class="table-title">Liste des Clients</h1>

    <!-- ✅ Bouton Ajouter un client -->
    <div class="add-client">
        <a href="<?= BASE_URL ?>index.php?url=create" class="btn-add">
            <i class="fas fa-user-plus"></i> Ajouter un client
        </a>
    </div>

    <?php if (count($clients) > 0): ?>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['nom'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['prenom'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['telephone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['adresse'] ?? '') ?></td>
                        <td class="table-actions">
                            <!-- ✅ Bouton Voir -->
                            <a href="<?= BASE_URL ?>index.php?url=details&id=<?= $client['id']; ?>" class="btn-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <!-- ✅ Bouton Modifier -->
                            <a href="<?= BASE_URL ?>index.php?url=edit&id=<?= $client['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <!-- ✅ Bouton Supprimer -->
                            <a href="<?= BASE_URL ?>index.php?url=delete&id=<?= $client['id']; ?>" class="btn-delete"
                               onclick="return confirm('Voulez-vous vraiment supprimer ce client ?');">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun client trouvé.</p>
    <?php endif; ?>
</div>

</body>
</html>