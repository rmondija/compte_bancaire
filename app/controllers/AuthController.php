<?php

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = Database::connect()->prepare("SELECT * FROM administrateur WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                session_start();
                $_SESSION['admin_id'] = $admin['id_admin'];
                header('Location: index.php?url=dashboard');
                exit();
            } else {
                $error = 'Identifiants incorrects';
            }
        }
        require_once 'app/views/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?url=login');
    }

    public function editPassword() {
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header('Location: index.php?url=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'];
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = Database::connect()->prepare("UPDATE administrateur SET password = :password WHERE id_admin = :id");
            $stmt->execute([
                'password' => $hashedPassword,
                'id' => $_SESSION['admin_id']
            ]);

            header('Location: index.php?url=dashboard');
            exit();
        }

        require_once 'app/views/edit_password.php';
    }
}


