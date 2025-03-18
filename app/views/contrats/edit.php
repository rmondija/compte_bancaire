<h1>Modifier le Contrat</h1>
<form method="POST">
    <input type="hidden" name="id_client" value="<?= $contrat['id_client'] ?>">

    <label>Type de contrat :</label>
    <select name="type" required>
        <option value="assurance vie" <?= $contrat['type'] === 'assurance vie' ? 'selected' : '' ?>>Assurance Vie</option>
        <option value="habitation" <?= $contrat['type'] === 'habitation' ? 'selected' : '' ?>>Assurance Habitation</option>
        <option value="crédit immobilier" <?= $contrat['type'] === 'crédit immobilier' ? 'selected' : '' ?>>Crédit Immobilier</option>
        <option value="crédit consommation" <?= $contrat['type'] === 'crédit consommation' ? 'selected' : '' ?>>Crédit Consommation</option>
        <option value="CEL" <?= $contrat['type'] === 'CEL' ? 'selected' : '' ?>>Compte Épargne Logement (CEL)</option>
    </select>

    <label>Montant :</label>
    <input type="number" name="montant" value="<?= $contrat['montant'] ?>" required>

    <label>Durée (en mois) :</label>
    <input type="number" name="duree" value="<?= $contrat['duree'] ?>" required>

    <button type="submit">Mettre à jour</button>
</form>