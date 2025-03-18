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
        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>

        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

        <label>Téléphone</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>" required>

        <label>Adresse</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($client['adresse']) ?>" required>

        <button type="submit" class="btn-save">Modifier</button>
        <a href="<?= BASE_URL ?>index.php?url=dashboard" class="btn-cancel">Annuler</a>
    </form>

</div>

</body>
</html>