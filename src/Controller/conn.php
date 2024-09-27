<?php
namespace Matheus\P1\Controller;

use PDO;
use PDOException;

class conn {
    private static $databasePath = __DIR__ . "/../../Database/produtos.db";

    public static function connect() {
        if (!file_exists(self::$databasePath)) {
            die("Erro: O banco de dados não foi encontrado.");
        }

        try {
            $dsn = "sqlite:" . self::$databasePath;
            $pdo = new PDO($dsn);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}