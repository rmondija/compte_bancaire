<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citoyens Agités</title>
    <!-- ✅ Lien vers Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- ✅ Lien vers FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- ✅ Lien vers le fichier CSS personnalisé -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ✅ Navbar Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Citoyens Agités</a>

    <!-- ✅ Bouton de toggle pour le responsive -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <!-- ✅ Bouton Accueil -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i> Accueil
                </a>
            </li>
            <!-- ✅ Bouton Nouveau Client -->
            <li class="nav-item">
                <a class="nav-link" href="nouveau-client.php">
                    <i class="fas fa-user-plus"></i> Nouveau Client
                </a>
            </li>
            <!-- ✅ Bouton Déconnexion -->
            <li class="nav-item">
                <a class="nav-link btn btn-danger text-white ml-2" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>
</nav>