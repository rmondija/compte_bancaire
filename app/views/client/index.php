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

<h1>Liste : Client</h1>

<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Succès !</strong> Le client a été enregistré avec succès.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="d-flex align-items-center gap-2 mb-3">
    <a href='?controller=client&action=create' class='btn btn-success'>Ajouter</a>
    <form method="get" class="d-flex gap-2" id="clientSearchForm">
        <input type="hidden" name="controller" value="client">
        <input type="hidden" name="action" value="index">
        <input type="text" id="clientSearchInput" name="q" class="form-control" placeholder="Rechercher client..." value="<?= htmlspecialchars((string)($search ?? '')) ?>">
        <button type="button" id="clientSearchClear" class="btn btn-outline-secondary">Effacer</button>
    </form>
</div>

<?php $openClientId = $openClientId ?? null; ?>
<div class="card border-0 rounded-3 overflow-hidden" style="box-shadow: 0 0 18px rgba(0, 0, 0, 0.12);">
<div class="card-body p-0">
<table class='table table-striped mb-0' id="clientsTable">
<thead class="bg-white">
<tr>
    <th>Nom</th>
    <th>Email</th>
    <th>Téléphone</th>
    <th>Adresse</th>
    <th>Actions</th>
</tr>
</thead>

<tbody id="clientsAccordion">
<?php foreach ($items as $index => $item): ?>
    <?php
        $clientId = (int)($item['id'] ?? 0);
        $clientComptes = array_filter($item['comptes'] ?? [], static function ($compte) use ($clientId) {
            return (int)($compte['client_id'] ?? 0) === $clientId;
        });
        $clientCompteIds = array_map(static function ($compte) {
            return (int)($compte['id'] ?? 0);
        }, $clientComptes);
    ?>
    <tr class="client-main-row" data-client-id="<?= $clientId ?>" data-client-search="<?= htmlspecialchars(mb_strtolower(trim((string)($item['nom'] ?? '') . ' ' . (string)($item['prenom'] ?? '') . ' ' . (string)($item['email'] ?? '') . ' ' . (string)($item['telephone'] ?? '') . ' ' . (string)($item['adresse'] ?? '')))) ?>">
        <td><?= htmlspecialchars((string)($item['nom'] ?? '')) ?> <?= htmlspecialchars((string)($item['prenom'] ?? '')) ?></td>
        <td><?= htmlspecialchars((string)($item['email'] ?? '')) ?></td>
        <td><span class="text-nowrap"><?= htmlspecialchars(trim(chunk_split(preg_replace('/\D/', '', (string)($item['telephone'] ?? '')), 2, ' '))) ?></span></td>
        <td><?= htmlspecialchars((string)($item['adresse'] ?? '')) ?></td>
        <td class="text-nowrap">
            <a href='?controller=client&action=show&id=<?= $clientId ?>' class='btn btn-primary btn-sm'>Voir</a>
            <a href='?controller=client&action=edit&id=<?= $clientId ?>' class='btn btn-warning btn-sm'>Modifier</a>
            <a href='?controller=client&action=delete&id=<?= $clientId ?>' class='btn btn-danger btn-sm' onclick="return confirm('Supprimer ?');">Supprimer</a>
            <button
                class="btn btn-secondary btn-sm"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse-client-<?= $clientId ?>"
                aria-expanded="<?= $openClientId === $clientId ? 'true' : 'false' ?>"
                aria-controls="collapse-client-<?= $clientId ?>"
            >
                Détails <i class="bi bi-chevron-down"></i>
            </button>
        </td>
    </tr>

    <tr class="detail-row client-detail-row" data-client-id="<?= $clientId ?>">
        <td colspan="5" class="p-0 border-0">
            <div
                id="collapse-client-<?= $clientId ?>"
                class="collapse <?= $openClientId === $clientId ? 'show' : '' ?>"
                data-bs-parent="#clientsAccordion"
            >
                <div class="p-3 bg-white border">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h5>Comptes associés</h5>
                            <?php if (!empty($clientComptes)): ?>
                                <table class="table table-sm table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Numéro</th>
                                            <th>Solde</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clientComptes as $compte): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string)($compte['numero_compte'] ?? '')) ?></td>
                                                <td><?= number_format((float)($compte['solde'] ?? 0), 2, ',', ' ') ?> €</td>
                                                <td><?= htmlspecialchars((string)($compte['type_compte'] ?? '')) ?></td>
                                                <td>
                                                    <a href='?controller=compte&action=show&id=<?= (int)($compte["id"] ?? 0) ?>' class='btn btn-info btn-sm'>Voir</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted mb-0">Aucun compte associé</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <h5>Contrats associés</h5>
                            <?php
                                $clientContrats = array_filter($item['contrats'] ?? [], static function ($contrat) use ($clientCompteIds) {
                                    return in_array((int)($contrat['compte_id'] ?? 0), $clientCompteIds, true);
                                });
                            ?>
                            <?php if (!empty($clientContrats)): ?>
                                <table class="table table-sm table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Montant</th>
                                            <th>Date signature</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clientContrats as $contrat): ?>
                                            <tr>
                                                <td><?= htmlspecialchars((string)($contrat['type_contrat'] ?? '')) ?></td>
                                                <td><?= number_format((float)($contrat['montant'] ?? 0), 2, ',', ' ') ?> €</td>
                                                <td><?= formatDateFr($contrat['date_signature'] ?? '') ?></td>
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
    var input = document.getElementById('clientSearchInput');
    var form = document.getElementById('clientSearchForm');
    var clearButton = document.getElementById('clientSearchClear');
    if (!input || !form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
    });

    var mainRows = Array.prototype.slice.call(document.querySelectorAll('tr.client-main-row'));

    function filterRows() {
        var term = input.value.toLowerCase().trim();
        mainRows.forEach(function (row) {
            var searchValue = (row.getAttribute('data-client-search') || '').toLowerCase();
            var isVisible = term === '' || searchValue.indexOf(term) !== -1;
            var clientId = row.getAttribute('data-client-id');
            var detailRow = document.querySelector('tr.client-detail-row[data-client-id="' + clientId + '"]');

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




