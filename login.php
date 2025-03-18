<?php
session_start();
require_once 'config/config.php';
require_once 'app/models/Database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM administrateur WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
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
    <link rel="stylesheet" href="style1.css">
</head>
<body>

<div class="login-container">
    <h1>Connexion</h1>
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <div class="form-group">
            <input type="email" name="email" placeholder="Adresse email" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Mot de passe" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>
