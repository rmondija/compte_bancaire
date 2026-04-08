<?php
$isEdit = isset($data) && isset($data['id']) && $data['id'];
$actionUrl = $isEdit ? "?controller=client&action=update&id={$data['id']}" : "?controller=client&action=store";
?>
<h1><?= $isEdit ? "Modifier un client" : "Ajouter un client" ?></h1>

<div class="card border-0 rounded-3 p-4 mt-3" style="box-shadow: 0 0 18px rgba(0,0,0,0.12);">
<form method="post" action="<?= htmlspecialchars($actionUrl) ?>" onsubmit="return validateForm()">
<div class="mb-3">
    <label class="form-label fw-bold">Nom *</label>
    <input type="text" name="nom" class="form-control <?= $isEdit ? 'bg-light text-muted' : '' ?>" value="<?= $data['nom'] ?? "" ?>" <?= $isEdit ? 'readonly' : '' ?> required>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Prénom *</label>
    <input type="text" name="prenom" class="form-control <?= $isEdit ? 'bg-light text-muted' : '' ?>" value="<?= $data['prenom'] ?? "" ?>" <?= $isEdit ? 'readonly' : '' ?> required>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Email *</label>
    <input type="email" name="email" class="form-control" value="<?= $data['email'] ?? "" ?>" required>
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Téléphone</label>
    <input type="tel" id="telephoneInput" name="telephone" class="form-control" value="<?= htmlspecialchars(trim(chunk_split(preg_replace('/\D/', '', (string)($data['telephone'] ?? '')), 2, ' '))) ?>" maxlength="14" placeholder="06 12 34 56 78">
</div>
<div class="mb-3">
    <label class="form-label fw-bold">Adresse</label>
    <textarea name="adresse" class="form-control"><?= $data['adresse'] ?? "" ?></textarea>
</div>
<button type="submit" class='btn btn-success'>Enregistrer</button>
<a href='?controller=client&action=index' class='btn btn-secondary'>Annuler</a>
</form>
</div>

<script>
document.getElementById('telephoneInput').addEventListener('input', function () {
    const cursor = this.selectionStart;
    const before = this.value.length;
    const digits = this.value.replace(/\D/g, '').substring(0, 10);
    const groups = digits.match(/.{1,2}/g);
    this.value = groups ? groups.join(' ') : '';
    const after = this.value.length;
    this.setSelectionRange(cursor + (after - before), cursor + (after - before));
});

function validateForm() {
    const nom = document.querySelector('input[name="nom"]').value.trim();
    const prenom = document.querySelector('input[name="prenom"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();

    if (!nom || !prenom || !email) {
        alert('Veuillez remplir tous les champs obligatoires (nom, prénom, email).');
        return false;
    }

    return confirm('Confirmer l\'enregistrement du client ?');
}
</script>



