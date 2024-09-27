<?php
namespace Matheus\P1\Controller;

use PDO;

class produtos {
    private $pdo;

    public function __construct() {
        // Instancia a classe Conn e chama o método connect
        $conn = new conn();
        $this->pdo = $conn->connect();
    }

    // Obter todos os produtos
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM produtos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obter produto por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Criar produto
    public function create($data) {
        // Validação
        if (strlen($data['nome']) < 3) {
            http_response_code(400);
            return "O nome do produto deve ter pelo menos 3 caracteres.";
        }
        if ($data['preco'] <= 0) {
            http_response_code(400);
            return "O preço deve ser um valor positivo.";
        }
        if ($data['estoque'] < 0 || !is_int($data['estoque'])) {
            http_response_code(400);
            return "O estoque deve ser um número inteiro maior ou igual a zero.";
        }

        $stmt = $this->pdo->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, userInsert, data_hora) 
                                      VALUES (:nome, :descricao, :preco, :estoque, :userInsert, :data_hora)");
        $stmt->execute([
            'nome' => $data['nome'],
            'descricao' => $data['descricao'],
            'preco' => $data['preco'],
            'estoque' => $data['estoque'],
            'userInsert' => $data['userInsert'],
            'data_hora' => date('Y-m-d H:i:s')
        ]);

        $log = new log();
        $log->create('create', $this->pdo->lastInsertId(), $data['userInsert']);

        http_response_code(201);
        return "Produto criado com sucesso!";
    }


    // Atualizar produto
    public function update($id, $data) {
        // Validação
        if (strlen($data['nome']) < 3) {
            http_response_code(400);
            return "O nome do produto deve ter pelo menos 3 caracteres.";
        }
        if ($data['preco'] <= 0) {
            http_response_code(400);
            return "O preço deve ser um valor positivo.";
        }
        if ($data['estoque'] < 0 || !is_int($data['estoque'])) {
            http_response_code(400);
            return "O estoque deve ser um número inteiro maior ou igual a zero.";
        }
    
        $stmt = $this->pdo->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, 
                                      estoque = :estoque, userInsert = :userInsert, data_hora = :data_hora WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'nome' => $data['nome'],
            'descricao' => $data['descricao'],
            'preco' => $data['preco'],
            'estoque' => $data['estoque'],
            'userInsert' => $data['userInsert'],
            'data_hora' => date('Y-m-d H:i:s')
        ]);
    
        // Aqui você deve passar o ID do produto para o log
        $log = new log();
        $log->create('update', $id, $data['userInsert']); // Aqui, $id é o ID do produto que foi atualizado
    
        http_response_code(200);
        return "Produto atualizado com sucesso!";
    }
    

    // Excluir produto
    public function delete($id, $userInsert) {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $result = $stmt->execute(['id' => $id]);
        
        // testar o $result para só criar o log caso sucesso
        // Log de exclusão
        $log = new Log();
        $log->create('delete', $id, $userInsert); // Verifique se $id não é nulo
    
        http_response_code(200);
        return "Produto excluído com sucesso!";
    }
    
}
?>
