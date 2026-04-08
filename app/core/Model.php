<?php
namespace App\Core;

abstract class Model
{
    protected string $table;

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = Database::getConnection()->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $pdo = Database::getConnection();
        $keys = implode(",", array_keys($data));
        $values = implode(",", array_fill(0, count($data), "?"));
        $stmt = $pdo->prepare("INSERT INTO {$this->table} ($keys) VALUES ($values)");
        $stmt->execute(array_values($data));
    }

    public function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $set = implode(",", array_map(fn($k) => "$k = ?", array_keys($data)));
        $stmt = $pdo->prepare("UPDATE {$this->table} SET $set WHERE id = ?");
        $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
    }
}



