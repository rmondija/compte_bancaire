<h1>Liste des Clients</h1>
<a href="index.php?url=clients/create">Ajouter un Client</a>
<ul>
    <?php foreach ($clients as $client) : ?>
        <li>
            <a href="index.php?url=client-details&id=<?= $client['id_client'] ?>">
                <?= $client['nom'] ?> <?= $client['prenom'] ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>