<?php
namespace App\Models;

use App\Core\Model;

class Administrateur extends Model
{
    protected string $table = "administrateur";

    public function create(array $data): void
    {
        // Hasher le mot de passe avant l'insertion
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        parent::create($data);
    }

    public function login(string $email, string $password): ?array
    {
        $pdo = \App\Core\Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$admin) {
            return null;
        }

        // Authentification sur mot de passe hashé
        if (password_verify($password, $admin['password'])) {
            return $admin;
        }

        // Si la base contient un mot de passe en clair, accepter et migrer vers un hash sécurisé
        if ($admin['password'] === $password) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE {$this->table} SET password = ? WHERE id = ?");
            $update->execute([$hashed, $admin['id']]);
            return $admin;
        }

        return null;
    }
}



