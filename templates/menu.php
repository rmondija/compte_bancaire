<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!-- ✅ Barre de navigation -->
<nav class="navbar">
    <div class="container">
        <a href="<?= BASE_URL ?>index.php" class="logo">Accueil</a>
        <div class="nav-links">
            <a href="<?= BASE_URL ?>index.php" class="btn-add">
                <i class="fas fa-user-plus"></i> Nouveau Client
            </a>
            <a href="<?= BASE_URL ?>logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>
</nav>