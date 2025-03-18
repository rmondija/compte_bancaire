<?php require_once __DIR__ . '/../../../config/config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Client</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="container">
    <h1>Ajouter un Client</h1>

    <form method="POST" class="form-container">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" required>

        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="telephone">Téléphone</label>
        <input type="text" name="telephone" id="telephone" required>

        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" id="adresse" required>

        <button type="submit" class="btn-save">Ajouter</button>
        <a href="<?= BASE_URL ?>index.php?url=dashboard" class="btn-cancel">Annuler</a>
    </form>
</div>

</body>
</html>