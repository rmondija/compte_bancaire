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

<h1>Liste : Contrat</h1>

<div class="d-flex align-items-center gap-2 mb-3">
    <a href='?controller=contrat&action=create' class='btn btn-success'>Ajouter</a>
    <form method="get" class="d-flex gap-2" id="contratSearchForm">
        <input type="hidden" name="controller" value="contrat">
        <input type="hidden" name="action" value="index">
        <input type="text" id="contratSearchInput" name="q" class="form-control" placeholder="Rechercher client..." value="<?= htmlspecialchars((string)($search ?? '')) ?>">
        <button type="button" id="contratSearchClear" class="btn btn-outline-secondary">Effacer</button>
    </form>
</div>

<div class="card border-0 rounded-3 overflow-hidden" style="box-shadow: 0 0 18px rgba(0, 0, 0, 0.12);">
<div class="card-body p-0">
<table class='table mb-0' id="contratsTable">
<thead class="bg-white">
<tr>
<th>Client</th>
<th>Type de contrat</th>
<th>Montant</th>
<th>Date de signature</th>
<th>Date d'expiration</th>
<th>Créé le</th>
<th>Actions</th>
</tr>
</thead>

<tbody id="contratsBody">

<?php foreach ($items as $item): ?>
<tr class="contrat-row" data-client-search="<?= htmlspecialchars(mb_strtolower((string)($item['client_name'] ?? ''))) ?>">
<td><?= htmlspecialchars((string)($item['client_name'] ?? '')) ?></td>
<td><?= htmlspecialchars((string)($item['type_contrat'] ?? '')) ?></td>
<td><?= number_format((float)($item['montant'] ?? 0), 2, ',', ' ') ?> €</td>
<td><?= htmlspecialchars(formatDateFr($item['date_signature'] ?? '')) ?></td>
<td><?= htmlspecialchars(formatDateFr($item['date_expiration'] ?? '')) ?></td>
<td><?= htmlspecialchars(formatDateFr($item['created_at'] ?? '')) ?></td>
<td>
    <a href='?controller=contrat&action=show&id=<?= $item["id"] ?>' class='btn btn-primary btn-sm'>Voir</a>
    <a href='?controller=contrat&action=edit&id=<?= $item["id"] ?>' class='btn btn-warning btn-sm'>Modifier</a>
    <a href='?controller=contrat&action=delete&id=<?= $item["id"] ?>' class='btn btn-danger btn-sm' onclick="return confirm('Supprimer ?');">Supprimer</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('contratSearchInput');
    var form = document.getElementById('contratSearchForm');
    var clearButton = document.getElementById('contratSearchClear');
    if (!input || !form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
    });

    var rows = Array.prototype.slice.call(document.querySelectorAll('tr.contrat-row'));

    function filterRows() {
        var term = input.value.toLowerCase().trim();
        rows.forEach(function (row) {
            var searchValue = (row.getAttribute('data-client-search') || '').toLowerCase();
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




