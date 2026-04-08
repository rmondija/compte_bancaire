<form method="POST" action="?controller=administrateur&action=login" id="loginForm">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="admin@test.com" required autofocus>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </div>
</form>


