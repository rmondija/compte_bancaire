<?php
session_start();
require_once 'config/config.php';
require_once 'app/models/Database.php';

$error = "";

// ✅ Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $db = Database::connect();

        // ✅ Requête pour vérifier si l'email existe dans la base de données
        $stmt = $db->prepare("SELECT * FROM administrateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // ✅ Vérification du mot de passe (sans hachage)
        if ($admin && $password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];

            // ✅ Redirection vers le tableau de bord
            header('Location: index.php');
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de la connexion : " . $e->getMessage());
        $error = "Erreur lors de la connexion à la base de données.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- ✅ Fichier CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ✅ Boîte de connexion centrée -->
<div class="login-wrapper">
    <div class="login-container">
        <h1 class="login-title">Connexion</h1>

        <!-- ✅ Message d'erreur -->
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- ✅ Formulaire de connexion -->
        <form method="POST" action="login.php">
            <div class="form-group">
                <input type="email" name="email" placeholder="Adresse email" required>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Mot de passe" required>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>
    </div>
</div>

</body>
</html>