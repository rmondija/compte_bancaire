<?php
$isEdit = isset($data['id']);
$selectedCompte = null;
$noExpirationChecked = isset($data['no_expiration'])
    ? (string) $data['no_expiration'] === '1'
    : empty($data['date_expiration']);

if ($isEdit && isset($data['compte_id'])) {
    foreach ($comptes as $fk) {
        if ((int)($fk['id'] ?? 0) === (int)($data['compte_id'] ?? 0)) {
            $selectedCompte = $fk;
            break;
        }
    }
}
?>
<h1><?= $isEdit ? "Modifier" : "Ajouter" ?> : Contrat</h1>

<div class="card border-0 rounded-3 p-4 mt-3" style="box-shadow: 0 0 18px rgba(0,0,0,0.12);">
<?php if (!empty($error)): ?>
<div class="alert alert-danger" role="alert">
    <?= htmlspecialchars((string)$error) ?>
</div>
<?php endif; ?>

<form method='post' action='?controller=contrat&action=<?= $isEdit ? "update&id={$data['id']}" : "store" ?>'>
<div class="mb-3">
    <?php if ($isEdit): ?>
        <label class="form-label fw-bold">Client</label>
        <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars((string)($selectedCompte['client_name'] ?? '')) ?>" readonly>
    <?php else: ?>
        <label class="form-label fw-bold">Compte *</label>
        <select name="compte_id" class="form-select" required>
            <?php foreach ($comptes as $fk): ?>
                <option value="<?= $fk['id'] ?>" <?= (isset($data['compte_id']) && $data['compte_id']==$fk['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($fk['numero_compte'] . ' - ' . $fk['client_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</div>
<?php if ($isEdit): ?>
<div class="mb-3">
    <label class="form-label fw-bold">Numéro de compte</label>
    <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars((string)($selectedCompte['numero_compte'] ?? '')) ?>" readonly>
    <input type="hidden" name="compte_id" value="<?= (int)($data['compte_id'] ?? 0) ?>">
</div>
<?php endif; ?>
<div class="mb-3">
    <label class="form-label fw-bold">Type de contrat *</label>
    <input type="text" name="type_contrat" class="form-control" value="<?= htmlspecialchars((string)($data['type_contrat'] ?? "")) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Montant *</label>
    <div class="input-group">
        <input type="text" id="montantInput" name="montant" class="form-control" value="<?= number_format((float)($data['montant'] ?? 0), 2, ',', ' ') ?>" required placeholder="1 234,56" inputmode="decimal" aria-describedby="montantHelp">
        <span class="input-group-text">&nbsp;€</span>
    </div>
    <div id="montantHelp" class="form-text">Minimum 100,00 €.</div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Date de signature *</label>
    <input type="date" name="date_signature" class="form-control" value="<?= htmlspecialchars((string)($data['date_signature'] ?? "")) ?>" required>
</div>
<div class="mb-3">
    <div class="d-flex align-items-center gap-3 mb-2">
        <label class="form-label fw-bold mb-0">Date d'expiration</label>
        <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" id="noExpirationCheckbox" name="no_expiration" value="1" <?= $noExpirationChecked ? 'checked' : '' ?>>
            <label class="form-check-label no-expiration-label" id="noExpirationLabel" for="noExpirationCheckbox" style="cursor: pointer;">Pas de date d'expiration</label>
        </div>
    </div>
    <input type="date" id="dateExpirationInput" name="date_expiration" class="form-control" value="<?= htmlspecialchars((string)($data['date_expiration'] ?? "")) ?>">
</div>
<button type="submit" class='btn btn-success'>Enregistrer</button>
<a href='?controller=contrat&action=index' class='btn btn-secondary'>Annuler</a>
</form>
</div>

<style>
.form-check-input#noExpirationCheckbox {
    border-color: var(--bs-primary);
}

.form-check-input#noExpirationCheckbox:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-check-input#noExpirationCheckbox:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.no-expiration-label:hover,
.no-expiration-label.active {
    color: var(--bs-primary);
}
</style>

<script>
document.getElementById('montantInput').addEventListener('input', function () {
    var currentValue = this.value;
    var currentCaretPos = this.selectionStart || 0;
    var rawBeforeCaret = currentValue.substring(0, currentCaretPos)
        .replace(/ /g, '')
        .replace(/\u00a0/g, '')
        .replace('.', ',')
        .replace(/[^\d,]/g, '');
    var raw = currentValue.replace(/ /g, '').replace(/\u00a0/g, '').replace('.', ',').replace(/[^\d,]/g, '');
    var comma = raw.indexOf(',');
    if (comma !== -1) {
        raw = raw.substring(0, comma + 1) + raw.substring(comma + 1).replace(/,/g, '').substring(0, 2);
    }
    var parts = raw.split(',');
    var formatted = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0');
    if (parts.length > 1) {
        formatted += ',' + (parts[1] + '00').substring(0, 2);
    } else {
        formatted += ',00';
    }
    this.value = formatted;
    var caretPos = this.value.indexOf(',');
    if (rawBeforeCaret.indexOf(',') !== -1 && caretPos !== -1) {
        var decimalsBeforeCaret = rawBeforeCaret.split(',')[1] || '';
        decimalsBeforeCaret = decimalsBeforeCaret.replace(/,/g, '').substring(0, 2).length;
        caretPos = caretPos + 1 + decimalsBeforeCaret;
    } else if (caretPos < 0) {
        caretPos = this.value.length;
    }
    var numericValue = parseFloat(this.value.replace(/\u00a0/g, '').replace(/ /g, '').replace(',', '.'));
    if (!Number.isNaN(numericValue) && numericValue < 100) {
        this.setCustomValidity('Le montant doit etre superieur ou egal a 100,00 EUR.');
    } else {
        this.setCustomValidity('');
    }
    this.setSelectionRange(caretPos, caretPos);
});

var noExpirationCheckbox = document.getElementById('noExpirationCheckbox');
var noExpirationLabel = document.getElementById('noExpirationLabel');
var dateExpirationInput = document.getElementById('dateExpirationInput');

function syncExpirationField() {
    if (!noExpirationCheckbox || !dateExpirationInput) {
        return;
    }

    if (noExpirationCheckbox.checked) {
        dateExpirationInput.value = '';
        dateExpirationInput.disabled = true;
        dateExpirationInput.required = false;
        dateExpirationInput.classList.add('bg-light', 'text-muted');
        if (noExpirationLabel) {
            noExpirationLabel.classList.add('active');
        }
    } else {
        dateExpirationInput.disabled = false;
        dateExpirationInput.required = true;
        dateExpirationInput.classList.remove('bg-light', 'text-muted');
        if (noExpirationLabel) {
            noExpirationLabel.classList.remove('active');
        }
    }
}

if (noExpirationCheckbox && dateExpirationInput) {
    noExpirationCheckbox.addEventListener('change', syncExpirationField);
    if (noExpirationLabel) {
        noExpirationLabel.addEventListener('click', function () {
            setTimeout(syncExpirationField, 0);
        });
    }
    syncExpirationField();
}
</script>



