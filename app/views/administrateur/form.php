<?php
$isEdit = isset($data) && isset($data['id']) && $data['id']; ?>
<h1><?= $isEdit ? "Modifier l'administrateur" : "Ajouter un administrateur" ?></h1>

<div class="card border-0 rounded-3 p-4 mt-3" style="box-shadow: 0 0 18px rgba(0,0,0,0.12);">
<?php if (!empty($error)): ?>
<div class="alert alert-danger" role="alert">
    <?= htmlspecialchars((string)$error) ?>
</div>
<?php endif; ?>

<form method='post' action='?controller=administrateur&action=<?= $isEdit ? "update&id={$data['id']}" : "store" ?>'>
<div class="mb-3">
    <label class="form-label">Nom *</label>
    <input type="text" name="nom" class="form-control <?= $isEdit ? 'bg-light text-muted' : '' ?>" value="<?= htmlspecialchars((string)($data['nom'] ?? '')) ?>" <?= $isEdit ? 'readonly' : '' ?> required placeholder="Nom complet">
</div>
<div class="mb-3">
    <label class="form-label">Email *</label>
    <input type="email" name="email" class="form-control <?= $isEdit ? 'bg-light text-muted' : '' ?>" value="<?= htmlspecialchars((string)($data['email'] ?? '')) ?>" <?= $isEdit ? 'readonly' : '' ?> required placeholder="admin@example.com">
</div>
<div class="mb-3">
    <label class="form-label">Mot de passe<?= $isEdit ? '' : ' *' ?></label>
    <div class="input-group">
        <input type="password" id="adminPasswordInput" name="password" class="form-control" placeholder="Mot de passe" <?= $isEdit ? '' : 'required' ?>>
        <span id="toggleAdminPasswordText" class="input-group-text password-toggle-text" role="button" tabindex="0">Afficher mot de passe</span>
        <button class="btn btn-outline-secondary" type="button" id="toggleAdminPassword" aria-label="Afficher le mot de passe">
            <i class="bi bi-eye-slash"></i>
        </button>
    </div>
    <?php if ($isEdit): ?>
        <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
    <?php endif; ?>
</div>
<button type="submit" class='btn btn-success'>Enregistrer</button>
<a href='?controller=administrateur&action=index' class='btn btn-secondary'>Annuler</a>
</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('adminPasswordInput');
    var toggle = document.getElementById('toggleAdminPassword');
    var toggleText = document.getElementById('toggleAdminPasswordText');
    if (!input || !toggle || !toggleText) {
        return;
    }

    function updatePasswordVisibility() {
        var isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');
        toggle.setAttribute('aria-label', isPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
        toggle.innerHTML = isPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        toggleText.classList.toggle('active', isPassword);
    }

    toggle.addEventListener('click', updatePasswordVisibility);
    toggleText.addEventListener('click', updatePasswordVisibility);
    toggleText.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            updatePasswordVisibility();
        }
    });
});
</script>



