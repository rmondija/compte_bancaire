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

<h1>Détails de l'administrateur</h1>

<div class="card border-0 rounded-3 p-4" style="box-shadow: 0 0 18px rgba(0,0,0,0.12);">
<ul class='list-group mb-3'>
    <li class='list-group-item d-flex flex-column'>
        <strong>Nom :</strong>
        <span><?= htmlspecialchars((string)($data['nom'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Email :</strong>
        <span><?= htmlspecialchars((string)($data['email'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Créé le :</strong>
        <span><?= formatDateFr($data['created_at'] ?? '') ?></span>
    </li>
</ul>

<div class="d-flex justify-content-start align-items-center gap-2">
    <a href='?controller=administrateur&action=index' class='btn btn-secondary rounded'>Retour</a>
    <button type="button" class='btn btn-warning rounded' onclick="window.location.href='?controller=administrateur&action=edit&id=<?= $data['id'] ?? '' ?>'">Modifier</button>
</div>
</div>




