<?php
require_once __DIR__ . '/../../../config/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Client</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="container">
    <h1>Détails du Client</h1>

    <p><strong>Nom :</strong> <?= htmlspecialchars($client['nom']) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($client['prenom']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($client['email']) ?></p>
    <p><strong>Téléphone :</strong> <?= htmlspecialchars($client['telephone']) ?></p>
    <p><strong>Adresse :</strong> <?= htmlspecialchars($client['adresse']) ?></p>

    <hr>

    <!-- ✅ Liste des comptes -->
    <h2>Comptes associés</h2>
    <?php if (count($comptes) > 0): ?>
        <ul>
            <?php foreach ($comptes as $compte): ?>
                <li>
                    <strong>Type :</strong> <?= htmlspecialchars($compte['type_compte']) ?> <br>
                    <strong>RIB :</strong> <?= htmlspecialchars($compte['numero_compte']) ?> <br>
                    <strong>Solde :</strong> <?= htmlspecialchars($compte['solde']) ?> € <br>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun compte associé.</p>
    <?php endif; ?>

    <!-- ✅ Liste des contrats -->
    <h2>Contrats associés</h2>
    <?php if (count($contrats) > 0): ?>
        <ul>
            <?php foreach ($contrats as $contrat): ?>
                <li>
                    <strong>Type :</strong> <?= htmlspecialchars($contrat['type_contrat']) ?> <br>
                    <strong>Montant :</strong> <?= htmlspecialchars($contrat['montant']) ?> € <br>
                    <strong>Date de signature :</strong> <?= htmlspecialchars($contrat['date_signature']) ?> <br>
                    <strong>Date d'expiration :</strong> <?= htmlspecialchars($contrat['date_expiration']) ?> <br>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun contrat associé.</p>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>index.php?url=dashboard" class="btn-back">Retour</a>
</div>

</body>
</html>