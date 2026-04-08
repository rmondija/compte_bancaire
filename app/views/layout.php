<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "Compte Bancaire" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Theme -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex">

<!-- TOPBAR MOBILE -->
<nav class="mobile-topbar d-flex d-md-none align-items-center px-3">
    <button id="burgerBtn" class="btn btn-link text-white p-0 me-3" style="font-size:1.7rem;" aria-label="Menu">
        <i class="bi bi-list"></i>
    </button>
    <span class="fw-bold text-white fs-5">
        <i class="bi bi-credit-card-2-front me-1"></i> Banque
    </span>
</nav>

<!-- OVERLAY SIDEBAR -->
<div id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="bg-primary text-white p-3 shadow-lg">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-credit-card-2-front"></i> Banque
        </h3>
        <button id="sidebarClose" class="d-md-none btn btn-link text-white p-0" style="font-size:1.4rem;" aria-label="Fermer">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="nav flex-column gap-2">
        <a class="nav-link text-white" href="?controller=administrateur&action=index">
            <i class="bi bi-person-gear"></i> Administrateurs
        </a>
        <a class="nav-link text-white" href="?controller=client&action=index">
            <i class="bi bi-people"></i> Clients
        </a>
        <a class="nav-link text-white" href="?controller=compte&action=index">
            <i class="bi bi-wallet2"></i> Comptes
        </a>
        <a class="nav-link text-white" href="?controller=contrat&action=index">
            <i class="bi bi-file-earmark-text"></i> Contrats
        </a>
    </nav>

    <hr class="border-light">

    <div class="mt-auto">
        <p class="text-white-50 small mb-2">
            <i class="bi bi-person-circle"></i> Connecté: <?= htmlspecialchars($_SESSION['admin_nom'] ?? 'Admin') ?>
        </p>
        <a class="btn btn-outline-light btn-sm w-100" href="?controller=administrateur&action=logout">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-grow-1 p-4">
    <?= $content ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var burger = document.getElementById('burgerBtn');
    var closeBtn = document.getElementById('sidebarClose');
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (burger)   burger.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay)  overlay.addEventListener('click', closeSidebar);
}());
</script>

</body>
</html>


