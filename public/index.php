<?php
use App\Core\Database;

require __DIR__ . '/../app/core/autoload.php';

Database::init(require __DIR__ . '/../config/database.php');

// Démarrer la session
session_start();

// Vérifier si l'administrateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$controller = $_GET['controller'] ?? 'client';
$action     = $_GET['action'] ?? 'index';
$id         = $_GET['id'] ?? null;

$controllerClass = "App\\Controllers\\" . ucfirst($controller) . "Controller";

if (!class_exists($controllerClass)) {
    die("Controller introuvable");
}

$ctrl = new $controllerClass();

if (!method_exists($ctrl, $action)) {
    die("Action introuvable");
}

$id ? $ctrl->$action((int)$id) : $ctrl->$action();



