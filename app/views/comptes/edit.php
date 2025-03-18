<?php
require_once __DIR__ . '/../../../config/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Client</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="container">
    <h1>Modifier le Client</h1>

    <form method="POST">
    <input type="text" name="nom" value="<?= htmlspecialchars($client['nom'] ?? '') ?>" required>
    <input type="text" name="prenom" value="<?= htmlspecialchars($client['prenom'] ?? '') ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($client['email'] ?? '') ?>" required>
    <input type="text" name="telephone" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>" required>
    <input type="text" name="adresse" value="<?= htmlspecialchars($client['adresse'] ?? '') ?>" required>

    <button type="submit">Modifier</button>
</form>
</div>

</body>
</html>