<?php

class Database {
    private static $host = 'localhost';
    private static $dbname = 'compte_bancaire';
    private static $username = 'root';
    private static $password = '';
    private static $connection = null;

    // ✅ Connexion à la base de données
    public static function connect() {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=utf8',
                    self::$username,
                    self::$password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
