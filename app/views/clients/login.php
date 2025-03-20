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

// Récupérer les informations de l'administrateur
$stmt = Database::connect()->prepare("SELECT email, password FROM administrateur LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch();

if ($admin) {
    $adminEmail = $admin['email'];
    $adminPassword = $admin['password'];
} else {
    $adminEmail = null;
    $adminPassword = null;
}