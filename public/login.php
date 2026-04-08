<?php
use App\Core\Database;

require __DIR__ . '/../app/core/autoload.php';

Database::init(require __DIR__ . '/../config/database.php');

// Démarrer la session
session_start();

// Si l'administrateur est déjà connecté, rediriger vers l'accueil
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$controller = 'administrateur';
$action = 'login';

$controllerClass = "App\\Controllers\\" . ucfirst($controller) . "Controller";

if (!class_exists($controllerClass)) {
    die("Controller introuvable");
}

$ctrl = new $controllerClass();

if (!method_exists($ctrl, $action)) {
    die("Action introuvable");
}

$ctrl->$action();


