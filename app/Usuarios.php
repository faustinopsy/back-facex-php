<?php

namespace App;

require "../vendor/autoload.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

use App\Database\Conexao;
use App\Database\UsuarioDAO;
use App\Model\User;
use App\Controller\UserController;

$conexao = Conexao::getConexao();
$usuarioDAO = new UsuarioDAO($conexao);
$userController = new UserController($usuarioDAO);

$body = json_decode(file_get_contents('php://input'), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $resultado = '';
        if (isset($body['acao'])) {
            $user = new User();
            switch ($body['acao']) {
                case 'cadastrar':
                    $user->setNome($body['nome']);
                    $user->setRegistro($body['registro']);
                    $user->setRostos($body['rosto']);
                    $resultado = $userController->inserir($user);
                    break;
                case 'registrar':
                    $user->setNome($body['nome']);
                    $user->setRegistro($body['registro']);
                    $user->setEmail($body['email']);
                    $user->setSenha($body['senha']); 
                    $resultado = $userController->registrar($user);
                    break;
                case 'login':
                    $user->setEmail($body['email']);
                    $user->setSenha($body['senha'],'S');
                    $resultado = $userController->login($user);
                    break;
            }
        }
        echo json_encode($resultado);
        break;
    case "GET":
        if (isset($_GET['relatorio'])) {
            $resultado = $userController->buscarTodos();
            echo json_encode(["usuarios" => $resultado]);
        } else {
            $id = $_GET['id'] ?? null;
            $resultado = $userController->buscarPorId($id);
            echo json_encode(["usuarios" => $resultado]);
        }
        break;
    case "DELETE":
        $id = $_GET['id'] ?? '';
        $resultado = $userController->excluir($id);
        echo json_encode($resultado);
        break;
}
