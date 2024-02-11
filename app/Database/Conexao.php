<?php
namespace App\Database;

use PDO;
class Conexao {
    public static function getConexao() {
        $host = 'localhost';
        $db   = 'pontozero'; //nome do banco de dados
        $user = 'root'; //nome de usuário do banco de dados
        $pass = 'root123'; //senha de usuário do banco de dados
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
