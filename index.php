<?php
namespace Matheus\P1;
require 'vendor/autoload.php';

use Matheus\P1\Controller\log;
use Matheus\P1\Controller\produtos;

header('Content-Type: application/json');

$produto = new produtos();
$log = new log();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch ($method) {
    case 'GET':        
        if (preg_match('/\/produtos\/(\d+)/', $uri, $match)) {
            $id = $match[1];
            $result = $produto->getById($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Produto não encontrado']);
            }
        } 
        
        if ($uri == '/produtos') {
            echo json_encode($produto->getAll());
        }
        
        if ($uri == '/logs') {
            $result = $log->getAll();
            echo json_encode($result);

        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $produto->create($data);
        echo json_encode(['message' => $result]);
        break;

    case 'PUT':
        preg_match('/\/produtos\/(\d+)/', $uri, $match);
        $id = $id = $match[1];
        $data = json_decode(file_get_contents('php://input'), true); // Captura os dados do corpo da requisição
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos para a atualização.']);
            break;
        }
        $result = $produto->update($id, $data);
        echo json_encode(['message' => $result]);
        break;
    
    case 'DELETE':
        preg_match('/\/produtos\/(\d+)/', $uri, $match);
        $id = $id = $match[1];
        $data = json_decode(file_get_contents('php://input'), true); // Captura os dados do corpo da requisição
        $userInsert = $data['userInsert'] ?? null; // Certifique-se de que está capturando o userInsert corretamente
        $result = $produto->delete($id, $userInsert);
        echo json_encode(['message' => $result]);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método não permitido']);
        break;
}