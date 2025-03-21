<?php
require_once 'config/config.php';

spl_autoload_register(function ($class) {
    $paths = ['app/models/', 'app/controllers/'];
    foreach ($paths as $path) {
        $file = __DIR__ . '/' . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

session_start();

// ✅ Vérification de la connexion
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// ✅ Connexion à la base de données
$db = Database::connect();

$stmt = $db->prepare("SELECT * FROM client");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ✅ Barre de navigation -->
<nav class="navbar">
    <div class="container">
        <a href="index.php" class="logo">Tableau de Bord</a>
        <div class="nav-links">
            <a href="app\views\clients\create.php" class="btn-add">
                <i class="fas fa-plus"></i> Ajouter Client
            </a>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>
</nav>

<!-- ✅ Tableau des clients -->
<div class="container">
    <h1 class="table-title">Liste des Clients</h1>

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
                    <td><?= htmlspecialchars($client['id']) ?></td>
                    <td><?= htmlspecialchars($client['nom']) ?></td>
                    <td><?= htmlspecialchars($client['prenom']) ?></td>
                    <td><?= htmlspecialchars($client['email']) ?></td>
                    <td><?= htmlspecialchars($client['telephone']) ?></td>
                    <td><?= htmlspecialchars($client['adresse']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="app\views\clients\details.php?id=<?= $client['id'] ?>" class="btn-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <a href="app\views\clients\edit.php?id=<?= $client['id'] ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="app\views\clients\delete.php?id=<?= $client['id'] ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>