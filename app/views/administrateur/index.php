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

<h1>Liste : Administrateurs</h1>

<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Succès !</strong> L'administrateur a été enregistré avec succès.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="d-flex align-items-center gap-2 mb-3">
    <a href='?controller=administrateur&action=create' class='btn btn-success'>Ajouter</a>
    <form method="get" class="d-flex gap-2" id="administrateurSearchForm">
        <input type="hidden" name="controller" value="administrateur">
        <input type="hidden" name="action" value="index">
        <input type="text" id="administrateurSearchInput" name="q" class="form-control" placeholder="Rechercher administrateur..." value="<?= htmlspecialchars((string)($search ?? '')) ?>">
        <button type="button" id="administrateurSearchClear" class="btn btn-outline-secondary">Effacer</button>
    </form>
</div>

<div class="card border-0 rounded-3 overflow-hidden" style="box-shadow: 0 0 18px rgba(0, 0, 0, 0.12);">
<div class="card-body p-0">
<table class='table mb-0' id="administrateursTable">
<thead class="bg-white">
<tr>
    <th>Nom</th>
    <th>Email</th>
    <th>Créé le</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>
<?php foreach ($items as $item): ?>
<tr class="admin-row" data-admin-search="<?= htmlspecialchars(mb_strtolower(trim((string)($item['nom'] ?? '') . ' ' . (string)($item['email'] ?? '')))) ?>">
    <td><?= htmlspecialchars($item['nom']) ?></td>
    <td><?= htmlspecialchars($item['email']) ?></td>
    <td><?= formatDateFr($item['created_at']) ?></td>
    <td class="text-nowrap">
        <a href='?controller=administrateur&action=show&id=<?= $item["id"] ?>' class='btn btn-primary btn-sm'>Voir</a>
        <a href='?controller=administrateur&action=edit&id=<?= $item["id"] ?>' class='btn btn-warning btn-sm'>Modifier</a>
        <a href='?controller=administrateur&action=delete&id=<?= $item["id"] ?>' class='btn btn-danger btn-sm' onclick="return confirm('Voulez-vous vraiment supprimer cet administrateur ?');">Supprimer</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('administrateurSearchInput');
    var form = document.getElementById('administrateurSearchForm');
    var clearButton = document.getElementById('administrateurSearchClear');
    if (!input || !form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
    });

    var rows = Array.prototype.slice.call(document.querySelectorAll('tr.admin-row'));

    function filterRows() {
        var term = input.value.toLowerCase().trim();
        rows.forEach(function (row) {
            var searchValue = (row.getAttribute('data-admin-search') || '').toLowerCase();
            row.style.display = term === '' || searchValue.indexOf(term) !== -1 ? '' : 'none';
        });
    }

    input.addEventListener('input', filterRows);

    if (clearButton) {
        clearButton.addEventListener('click', function () {
            input.value = '';
            filterRows();
            input.focus();
        });
    }
});
</script>



