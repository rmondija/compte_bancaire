<?php

class Router {
    public function handleRequest() {
        $url = isset($_GET['url']) ? htmlspecialchars($_GET['url']) : 'dashboard';
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;

        switch ($url) {
            // ✅ Dashboard
            case 'dashboard':
                if (class_exists('DashboardController')) {
                    $controller = new DashboardController();
                    $controller->index();
                } else {
                    http_response_code(404);
                    echo "Erreur 404 : Page introuvable.";
                }
                break;

                // Ajouter un client
                case 'create':
                    if (class_exists('ClientController')) {
                        $controller = new ClientController();
                        $controller->create();
                    } else {
                        http_response_code(404);
                        echo "Erreur 404 : Page introuvable.";
                    }
                    break;
                
            // ✅ Détails du client
            case 'details':
                if ($id && class_exists('ClientController')) {
                    $controller = new ClientController();
                    $controller->details($id);
                } else {
                    http_response_code(400);
                    echo "Erreur 400 : ID client invalide.";
                }
                break;

            // ✅ Modification du client
            case 'edit':
                if ($id && class_exists('ClientController')) {
                    $controller = new ClientController();
                    $controller->edit($id);
                } else {
                    http_response_code(400);
                    echo "Erreur 400 : ID client invalide.";
                }
                break;

            // ✅ Suppression du client
            case 'delete':
                if ($id && class_exists('ClientController')) {
                    $controller = new ClientController();
                    $controller->delete($id);
                } else {
                    http_response_code(400);
                    echo "Erreur 400 : ID client invalide.";
                }
                break;

            // ✅ Page non trouvée
            default:
                http_response_code(404);
                echo "Erreur 404 : Page introuvable.";
                break;
        }
    }
}