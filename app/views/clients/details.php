<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../app/models/Database.php';

// ✅ Récupération des informations du client
$clientId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($clientId > 0) {
    $db = Database::connect();
    $stmt = $db->prepare("SELECT * FROM client WHERE id = :id");
    $stmt->execute(['id' => $clientId]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Client introuvable.");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Client</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
</head>
<body>

<!-- ✅ Barre de navigation -->
<nav class="navbar">
    <div class="container">
        <a href="<?= BASE_URL ?>index.php" class="logo">Tableau de Bord</a>
        <div class="nav-links">
            <a href="<?= BASE_URL ?>index.php" class="btn-add">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <a href="<?= BASE_URL ?>index.php?url=dashboard" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="<?= BASE_URL ?>logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>
</nav>

<!-- ✅ Contenu de la page -->
<div class="container">
    <h1>Détails du Client</h1>

    <p><strong>Nom :</strong> <?= htmlspecialchars($client['nom'] ?? '') ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($client['prenom'] ?? '') ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($client['email'] ?? '') ?></p>
    <p><strong>Téléphone :</strong> <?= htmlspecialchars($client['telephone'] ?? '') ?></p>
    <p><strong>Adresse :</strong> <?= htmlspecialchars($client['adresse'] ?? '') ?></p>

    <hr>

    <h2>Comptes Associés</h2>
    <?php
    $stmt = $db->prepare("SELECT * FROM compte WHERE client_id = :client_id");
    $stmt->execute(['client_id' => $clientId]);
    $comptes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($comptes):
        foreach ($comptes as $compte):
    ?>
        <p>
            <strong>Type de compte :</strong> <?= htmlspecialchars($compte['type_compte']) ?><br>
            <strong>Numéro :</strong> <?= htmlspecialchars($compte['numero_compte']) ?><br>
            <strong>Solde :</strong> <?= htmlspecialchars($compte['solde']) ?> €
        </p>
        <hr>
    <?php endforeach;
    else: ?>
        <p>Aucun compte associé.</p>
    <?php endif; ?>

    <h2>Contrats Associés</h2>
    <?php
    $stmt = $db->prepare("
        SELECT contrat.* 
        FROM contrat 
        INNER JOIN compte ON contrat.compte_id = compte.id 
        WHERE compte.client_id = :client_id
    ");
    $stmt->execute(['client_id' => $clientId]);
    $contrats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($contrats):
        foreach ($contrats as $contrat):
    ?>
        <p>
            <strong>Type de contrat :</strong> <?= htmlspecialchars($contrat['type_contrat']) ?><br>
            <strong>Montant :</strong> <?= htmlspecialchars($contrat['montant']) ?> €<br>
            <strong>Date de signature :</strong> <?= htmlspecialchars($contrat['date_signature']) ?><br>
            <strong>Date d'expiration :</strong> <?= htmlspecialchars($contrat['date_expiration']) ?>
        </p>
        <hr>
    <?php endforeach;
    else: ?>
        <p>Aucun contrat associé.</p>
    <?php endif; ?>

</div>

</body>
</html>