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

<h1>Liste : Compte</h1>

<div class="d-flex align-items-center gap-2 mb-3">
    <a href='?controller=compte&action=create' class='btn btn-success'>Ajouter</a>
    <form method="get" class="d-flex gap-2" id="compteSearchForm">
        <input type="hidden" name="controller" value="compte">
        <input type="hidden" name="action" value="index">
        <input type="text" id="compteSearchInput" name="q" class="form-control" placeholder="Rechercher client..." value="<?= htmlspecialchars((string)($search ?? '')) ?>">
        <button type="button" id="compteSearchClear" class="btn btn-outline-secondary">Effacer</button>
    </form>
</div>

<div class="card border-0 rounded-3 overflow-hidden" style="box-shadow: 0 0 18px rgba(0, 0, 0, 0.12);">
<div class="card-body p-0">
<table class='table table-striped mb-0' id="comptesTable">
<thead class="bg-white">
<tr>
<th>Client</th>
<th>Numéro de compte</th>
<th>Solde</th>
<th>Type de compte</th>
<th>Créé le</th>
<th>Actions</th>
</tr>
</thead>

<?php $openCompteId = $openCompteId ?? null; ?>
<tbody id="comptesAccordion">
<?php foreach ($items as $item): ?>
<?php
    $compteId = (int)($item['id'] ?? 0);
    $compteContrats = array_filter($contrats ?? [], static function ($contrat) use ($compteId) {
        return (int)($contrat['compte_id'] ?? 0) === $compteId;
    });
?>
<tr class="compte-main-row" data-compte-id="<?= $compteId ?>" data-client-search="<?= htmlspecialchars(mb_strtolower(trim((string)($clientsMap[$item['client_id']]['nom'] ?? '') . ' ' . (string)($clientsMap[$item['client_id']]['prenom'] ?? '') . ' ' . (string)($clientsMap[$item['client_id']]['email'] ?? '')))) ?>">
<td><?= htmlspecialchars((string)($clientsMap[$item['client_id']]['nom'] ?? '')) ?> <?= htmlspecialchars((string)($clientsMap[$item['client_id']]['prenom'] ?? '')) ?></td>
<td><?= htmlspecialchars((string)($item['numero_compte'] ?? '')) ?></td>
<td><?= number_format((float)($item['solde'] ?? 0), 2, ',', ' ') ?> €</td>
<td><?= htmlspecialchars((string)($item['type_compte'] ?? '')) ?></td>
<td><?= htmlspecialchars(formatDateFr($item['created_at'] ?? '')) ?></td>
<td class="text-nowrap">
    <a href='?controller=compte&action=show&id=<?= $item["id"] ?>' class='btn btn-primary btn-sm'>Voir</a>
    <a href='?controller=compte&action=edit&id=<?= $item["id"] ?>' class='btn btn-warning btn-sm'>Modifier</a>
    <a href='?controller=compte&action=delete&id=<?= $item["id"] ?>' class='btn btn-danger btn-sm' onclick="return confirm('Supprimer ?');">Supprimer</a>
    <button
        class="btn btn-secondary btn-sm"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#collapse-compte-<?= $compteId ?>"
        aria-expanded="<?= $openCompteId === $compteId ? 'true' : 'false' ?>"
        aria-controls="collapse-compte-<?= $compteId ?>"
    >
        Détails <i class="bi bi-chevron-down"></i>
    </button>
</td>
</tr>

<tr class="compte-detail-row" data-compte-id="<?= $compteId ?>">
    <td colspan="6" class="p-0 border-0">
        <div
            id="collapse-compte-<?= $compteId ?>"
            class="collapse <?= $openCompteId === $compteId ? 'show' : '' ?>"
            data-bs-parent="#comptesAccordion"
        >
            <div class="p-3 bg-white border">
                <h5>Contrats associés</h5>
                <?php if (!empty($compteContrats)): ?>
                    <table class="table table-sm table-striped table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Type de contrat</th>
                                <th>Montant</th>
                                <th>Date de signature</th>
                                <th>Date d'expiration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($compteContrats as $contrat): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string)($contrat['type_contrat'] ?? '')) ?></td>
                                    <td><?= number_format((float)($contrat['montant'] ?? 0), 2, ',', ' ') ?> €</td>
                                    <td><?= htmlspecialchars(formatDateFr($contrat['date_signature'] ?? '')) ?></td>
                                    <td><?= htmlspecialchars(formatDateFr($contrat['date_expiration'] ?? '')) ?></td>
                                    <td>
                                        <a href='?controller=contrat&action=show&id=<?= (int)($contrat["id"] ?? 0) ?>' class='btn btn-info btn-sm'>Voir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucun contrat associé</p>
                <?php endif; ?>
            </div>
        </div>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('compteSearchInput');
    var form = document.getElementById('compteSearchForm');
    var clearButton = document.getElementById('compteSearchClear');
    if (!input || !form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
    });

    var rows = Array.prototype.slice.call(document.querySelectorAll('tr.compte-main-row'));

    function filterRows() {
        var term = input.value.toLowerCase().trim();
        rows.forEach(function (row) {
            var searchValue = (row.getAttribute('data-client-search') || '').toLowerCase();
            var isVisible = term === '' || searchValue.indexOf(term) !== -1;
            var compteId = row.getAttribute('data-compte-id');
            var detailRow = document.querySelector('tr.compte-detail-row[data-compte-id="' + compteId + '"]');

            row.style.display = isVisible ? '' : 'none';
            if (detailRow) {
                detailRow.style.display = isVisible ? '' : 'none';
            }
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




