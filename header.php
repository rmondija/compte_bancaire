<?php
// ✅ Vérifier si une session est déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Tableau de Bord</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <!-- ✅ Bouton Ajouter Client -->
                <li class="nav-item">
                    <a class="btn btn-success me-2" href="create.php">
                        <i class="fas fa-user-plus"></i> Ajouter Client
                    </a>
                </li>
                <!-- ✅ Bouton Déconnexion -->
                <li class="nav-item">
                    <a class="btn btn-danger" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>