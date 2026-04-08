<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Administrateur;
use Exception;

class AdministrateurController extends Controller
{
public function index()
    {
        $items = (new Administrateur())->all();
        $this->render('administrateur/index', compact('items'));
    }

    public function show(int $id)
    {
        $data = (new Administrateur())->find($id);
        $this->render('administrateur/show', compact('data'));
    }

    public function create()
    {
        $this->render('administrateur/form', ['data' => null]);
    }

    public function store()
    {
        // Validation basique
        if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header("Location: ?controller=administrateur&action=create");
            exit;
        }

        try {
            (new Administrateur())->create($_POST);
            header("Location: ?controller=administrateur&action=index");
            exit;
        } catch (Exception $e) {
            // En cas d'erreur, afficher un message et rediriger
            $_SESSION['error'] = "Erreur lors de la création de l'administrateur: " . $e->getMessage();
            header("Location: ?controller=administrateur&action=create");
            exit;
        }
    }

    public function edit(int $id)
    {
        $data = (new Administrateur())->find($id);
        $this->render('administrateur/form', compact('data'));
    }

    public function update(int $id)
    {
        // Validation basique
        if (empty($_POST['nom']) || empty($_POST['email'])) {
            $_SESSION['error'] = "Le nom et l'email sont obligatoires.";
            header("Location: ?controller=administrateur&action=edit&id=$id");
            exit;
        }

        // Si le mot de passe est vide, ne pas le mettre à jour
        if (empty($_POST['password'])) {
            unset($_POST['password']);
        }

        try {
            (new Administrateur())->update($id, $_POST);
            header("Location: ?controller=administrateur&action=index");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de la modification: " . $e->getMessage();
            header("Location: ?controller=administrateur&action=edit&id=$id");
            exit;
        }
    }

    public function delete(int $id)
    {
        try {
            (new Administrateur())->delete($id);
            header("Location: ?controller=administrateur&action=index");
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
            header("Location: ?controller=administrateur&action=index");
            exit;
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $admin = (new Administrateur())->login($email, $password);

            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nom'] = $admin['nom'];
                $_SESSION['admin_email'] = $admin['email'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
                $this->renderLoginPage(compact('error'));
                return;
            }
        }

        $this->renderLoginPage();
    }

    private function renderLoginPage($data = [])
    {
        extract($data);

        // Inclure directement le layout qui contiendra la vue
        require __DIR__ . '/../views/login_layout.php';
    }

    public function logout()
    {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}




