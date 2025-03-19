<?php

// Point d'entrée de l'application
require_once 'config/config.php';
require_once 'routes/routes.php';

// Autoloader des classes
spl_autoload_register(function ($class) {
    $paths = ['app/models/', 'app/controllers/'];
    foreach ($paths as $path) {
        if (file_exists($path . $class . '.php')) {
            require_once $path . $class . '.php';
            return;
        }
    }
});

// Connexion à la base de données
require_once 'app/models/Database.php';
Database::connect();

// Lancement du routeur
$router = new Router();
$router->handleRequest();   

try {
    include_once('app\views\dashboard.php');
} catch (Exception $e) {
    error_log("Erreur lors de l'inclusion du fichier dashboard.html : " . $e->getMessage());
    echo "Erreur lors de l'affichage du tableau de bord.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tableau de Bord</title>
</head>
<body>
<nav class="navbar">
    <div class="container">
        <a href="index.php" class="logo">Tableau de Bord</a>
        <div class="nav-links">
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>
</nav>

</body>
</html>
