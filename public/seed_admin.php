<?php
use App\Core\Database;
use App\Models\Administrateur;

require __DIR__ . '/../app/core/autoload.php';

Database::init(require __DIR__ . '/../config/database.php');

// Créer un administrateur de test
$admin = new Administrateur();
$adminData = [
    'nom' => 'Admin Test',
    'email' => 'admin@test.com',
    'password' => password_hash('admin123', PASSWORD_DEFAULT),
    'created_at' => date('Y-m-d H:i:s')
];

try {
    $admin->create($adminData);
    echo "Administrateur de test créé avec succès !<br>";
    echo "Email: admin@test.com<br>";
    echo "Mot de passe: admin123<br>";
} catch (Exception $e) {
    echo "Erreur lors de la création de l'administrateur: " . $e->getMessage();
}


