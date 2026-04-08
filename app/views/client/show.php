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

<h1 id="showTitle">Détails du client</h1>

<div class="card border-0 rounded-3 p-4" style="box-shadow: 0 0 18px rgba(0,0,0,0.12);">

<!-- Section détails -->
<div id="detailsSection">
<ul class='list-group mb-3'>
    <li class='list-group-item d-flex flex-column'>
        <strong>Nom :</strong>
        <span><?= htmlspecialchars((string)($data['nom'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Prénom :</strong>
        <span><?= htmlspecialchars((string)($data['prenom'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Email :</strong>
        <span><?= htmlspecialchars((string)($data['email'] ?? '')) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Téléphone :</strong>
        <span class="text-nowrap"><?= htmlspecialchars(trim(chunk_split(preg_replace('/\D/', '', (string)($data['telephone'] ?? '')), 2, ' '))) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Adresse :</strong>
        <span><?= nl2br(htmlspecialchars((string)($data['adresse'] ?? ''))) ?></span>
    </li>
    <li class='list-group-item d-flex flex-column'>
        <strong>Créé le :</strong>
        <span><?= formatDateFr($data['created_at'] ?? '') ?></span>
    </li>
</ul>

<div class="d-flex justify-content-start align-items-center gap-2">
    <a href='?controller=client&action=index' class='btn btn-secondary rounded'>Retour</a>
    <button type="button" class='btn btn-warning rounded' onclick="showEditForm()">Modifier</button>
</div>
</div>

<!-- Section formulaire (cachée par défaut) -->
<div id="editSection" style="display:none;">
<form method="post" action="?controller=client&action=update&id=<?= (int)($data['id'] ?? 0) ?>" onsubmit="return validateEditForm()">
<div class="mb-3">
    <label class="form-label">Nom *</label>
    <input type="text" name="nom" class="form-control bg-light text-muted" value="<?= htmlspecialchars((string)($data['nom'] ?? '')) ?>" readonly required>
</div>
<div class="mb-3">
    <label class="form-label">Prénom *</label>
    <input type="text" name="prenom" class="form-control bg-light text-muted" value="<?= htmlspecialchars((string)($data['prenom'] ?? '')) ?>" readonly required>
</div>
<div class="mb-3">
    <label class="form-label">Email *</label>
    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars((string)($data['email'] ?? '')) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Téléphone</label>
    <input type="tel" id="telephoneInputShow" name="telephone" class="form-control" value="<?= htmlspecialchars(trim(chunk_split(preg_replace('/\D/', '', (string)($data['telephone'] ?? '')), 2, ' '))) ?>" maxlength="14" placeholder="06 12 34 56 78">
</div>
<div class="mb-3">
    <label class="form-label">Adresse</label>
    <textarea name="adresse" class="form-control"><?= htmlspecialchars((string)($data['adresse'] ?? '')) ?></textarea>
</div>
<button type="submit" class='btn btn-success'>Enregistrer</button>
<button type="button" class='btn btn-secondary ms-2' onclick="hideEditForm()">Annuler</button>
</form>
</div>

</div>

<script>
function showEditForm() {
    document.getElementById('detailsSection').style.display = 'none';
    document.getElementById('editSection').style.display = '';
    document.getElementById('showTitle').textContent = 'Modifier le client';
}
function hideEditForm() {
    document.getElementById('editSection').style.display = 'none';
    document.getElementById('detailsSection').style.display = '';
    document.getElementById('showTitle').textContent = 'Détails du client';
}

document.getElementById('telephoneInputShow').addEventListener('input', function () {
    const cursor = this.selectionStart;
    const before = this.value.length;
    const digits = this.value.replace(/\D/g, '').substring(0, 10);
    const groups = digits.match(/.{1,2}/g);
    this.value = groups ? groups.join(' ') : '';
    const after = this.value.length;
    this.setSelectionRange(cursor + (after - before), cursor + (after - before));
});

function validateEditForm() {
    const email = document.querySelector('#editSection input[name="email"]').value.trim();
    if (!email) {
        alert('Veuillez remplir l\'adresse email.');
        return false;
    }
    return confirm('Confirmer la modification du client ?');
}
</script>




