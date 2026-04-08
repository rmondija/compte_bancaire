<?php
function formatDateFr(?string $date): string
{
    if (empty($date)) {
        return '';
    }

    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $date) ?: DateTime::createFromFormat('Y-m-d', $date);
    if (!$dt) {
        return htmlspecialchars($date);
    }

    $months = [
        1 => 'janvier',
        2 => 'février',
        3 => 'mars',
        4 => 'avril',
        5 => 'mai',
        6 => 'juin',
        7 => 'juillet',
        8 => 'août',
        9 => 'septembre',
        10 => 'octobre',
        11 => 'novembre',
        12 => 'décembre',
    ];

    $day = $dt->format('d');
    $month = $months[(int)$dt->format('n')] ?? $dt->format('F');
    $year = $dt->format('Y');

    return sprintf('%s %s %s', $day, $month, $year);
}
?>

<h1>Détails du compte</h1>

<div class="card border-0 rounded-3 p-4" style="box-shadow: 0 0 18px rgba(0,0,0,0.12);">

<ul class='list-group mb-3'>
    <li class='list-group-item d-flex flex-column'>
        <strong>Client :</strong>
        <span><?= htmlspecialchars((string)($client['nom'] ?? '')) ?> <?= htmlspecialchars((string)($client['prenom'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Numéro de compte :</strong>
        <span><?= htmlspecialchars((string)($data['numero_compte'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Solde :</strong>
        <span><?= number_format((float)($data['solde'] ?? 0), 2, ',', ' ') ?> €</span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Type de compte :</strong>
        <span><?= htmlspecialchars((string)($data['type_compte'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Créé le :</strong>
        <span><?= htmlspecialchars(formatDateFr($data['created_at'] ?? '')) ?></span>
    </li>
</ul>

<div class="d-flex justify-content-start align-items-center gap-2">
    <a href='?controller=compte&action=index' class='btn btn-secondary rounded'>Retour</a>
    <button type="button" class='btn btn-warning rounded' onclick="window.location.href='?controller=compte&action=edit&id=<?= $data['id'] ?? '' ?>'">Modifier</button>
</div>

</div>




