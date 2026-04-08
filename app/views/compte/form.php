<?php
$isEdit = isset($data['id']);
$selectedClient = null;

if ($isEdit && isset($data['client_id'])) {
    foreach ($clients as $fk) {
        if ((int)($fk['id'] ?? 0) === (int)($data['client_id'] ?? 0)) {
            $selectedClient = $fk;
            break;
        }
    }
}
?>
<h1><?= $isEdit ? "Modifier" : "Ajouter" ?> : Compte</h1>

<div class="card border-0 rounded-3 p-4 mt-3" style="box-shadow: 0 0 18px rgba(0,0,0,0.12);">
<?php if (!empty($error)): ?>
<div class="alert alert-danger" role="alert">
    <?= htmlspecialchars((string)$error) ?>
</div>
<?php endif; ?>

<form method='post' action='?controller=compte&action=<?= $isEdit ? "update&id={$data['id']}" : "store" ?>'>
<div class="mb-3">
    <?php if ($isEdit): ?>
        <label class="form-label fw-bold">Client</label>
        <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars(trim((string)($selectedClient['nom'] ?? '') . ' ' . (string)($selectedClient['prenom'] ?? ''))) ?>" readonly>
        <input type="hidden" name="client_id" value="<?= (int)($data['client_id'] ?? 0) ?>">
    <?php else: ?>
        <label class="form-label fw-bold">Client *</label>
        <select name="client_id" class="form-select" required>
            <?php foreach ($clients as $fk): ?>
                <option value="<?= $fk['id'] ?>" <?= (isset($data['client_id']) && $data['client_id']==$fk['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($fk['nom'] . ' ' . $fk['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Numéro de compte *</label>
    <?php if ($isEdit): ?>
        <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars((string)($data['numero_compte'] ?? "")) ?>" readonly>
        <input type="hidden" name="numero_compte" value="<?= htmlspecialchars((string)($data['numero_compte'] ?? "")) ?>">
    <?php else: ?>
        <input type="text" name="numero_compte" class="form-control" value="<?= htmlspecialchars((string)($data['numero_compte'] ?? "")) ?>" required>
    <?php endif; ?>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Solde *</label>
    <div class="input-group">
        <input type="text" id="soldeInput" name="solde" class="form-control" value="<?= number_format((float)($data['solde'] ?? 0), 2, ',', ' ') ?>" required placeholder="1 234,56" inputmode="decimal" aria-describedby="soldeHelp">
        <span class="input-group-text">&nbsp;€</span>
    </div>
    <div id="soldeHelp" class="form-text">Minimum 100,00 €.</div>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Type de compte *</label>
    <?php $selectedTypeCompte = (string)($data['type_compte'] ?? ''); ?>
    <select id="typeCompteSelect" name="type_compte" class="form-select" required>
        <option value="courant" <?= $selectedTypeCompte === 'courant' ? 'selected' : '' ?>>courant</option>
        <option value="épargne" <?= $selectedTypeCompte === 'épargne' ? 'selected' : '' ?>>épargne</option>
    </select>
</div>
<button type="submit" class='btn btn-success'>Enregistrer</button>
<a href='?controller=compte&action=index' class='btn btn-secondary'>Annuler</a>
</form>
</div>

<script>
var soldeInput = document.getElementById('soldeInput');
var typeCompteSelect = document.getElementById('typeCompteSelect');
var soldeHelp = document.getElementById('soldeHelp');

function requiresMinimumSolde() {
    if (!typeCompteSelect) {
        return false;
    }

    return typeCompteSelect.value === 'épargne';
}

function validateSoldeField() {
    if (!soldeInput) {
        return;
    }

    var numericValue = parseFloat(soldeInput.value.replace(/\u00a0/g, '').replace(/ /g, '').replace(',', '.'));

    if (requiresMinimumSolde()) {
        if (soldeHelp) {
            soldeHelp.textContent = 'Minimum 100,00 € pour un compte épargne.';
        }

        if (!Number.isNaN(numericValue) && numericValue < 100) {
            soldeInput.setCustomValidity('Le solde doit etre superieur ou egal a 100,00 EUR pour un compte epargne.');
            return;
        }
    } else if (soldeHelp) {
        soldeHelp.textContent = '';
    }

    soldeInput.setCustomValidity('');
}

soldeInput.addEventListener('input', function () {
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
    validateSoldeField();
    this.setSelectionRange(caretPos, caretPos);
});

if (typeCompteSelect) {
    typeCompteSelect.addEventListener('change', validateSoldeField);
}

validateSoldeField();
</script>



