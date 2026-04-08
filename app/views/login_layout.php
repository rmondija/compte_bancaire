<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Système Bancaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Connexion administrateur</h3>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php require __DIR__ . '/administrateur/login.php'; ?>
                    </div>
                </div>
                <div class="alert alert-info mt-3 small">
                    <strong>Identifiants de test :</strong><br>
                    Email : <code>admin@test.com</code><br>
                    Mot de passe : <code>admin123</code>
                </div>
                <p class="text-center text-muted mt-2 small">
                    Saisissez votre email et mot de passe pour accéder au tableau de bord.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


