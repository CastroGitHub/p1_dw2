<?php
namespace Matheus\P1\Controller;

use PDO;

class log {
    private $pdo;

    public function __construct() {
        // Instanciando a classe Conn para chamar o método connect
        $conn = new conn();
        $this->pdo = $conn->connect();
    }

    // Criar log
    public function create($acao, $produto_id, $userInsert) {
        var_dump($produto_id); // Verifique se produto_id não é nulo
        $stmt = $this->pdo->prepare("INSERT INTO logs (acao, data_hora, produto_id, userInsert) 
                                      VALUES (:acao, :data_hora, :produto_id, :userInsert)");
        $stmt->execute([
            'acao' => $acao,
            'data_hora' => date('Y-m-d H:i:s'),
            'produto_id' => $produto_id,
            'userInsert' => $userInsert
        ]);
    } 

    // Obter todos os logs
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM logs");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

