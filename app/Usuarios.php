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
use App\Model\Users;
use App\Controller\UserController;

$conexao = Conexao::getConexao();
$usuario = new Users();


$body = json_decode(file_get_contents('php://input'), true);


switch($_SERVER["REQUEST_METHOD"]){
    case "POST"; 
    $users = new UserController($usuario,$conexao);
    $resultado='';
        if (isset($body['acao']) && $body['acao'] == 'cadastrar') {
            $usuario->setNome($body['nome']);
            $usuario->setRegistro($body['registro']);
            $usuario->setRostos($body['rosto']);
            $resultado = $users->inserir();
        }
        elseif (isset($body['acao']) && $body['acao'] == 'registrar') {
            $usuario->setNome($body['nome']);
            $usuario->setRegistro($body['registro']);
            $usuario->setEmail($body['email']);
            $usuario->setSenha($body['senha']);
            $resultado = $users->registrar();
        } elseif (isset($body['acao']) && $body['acao'] == 'login') {
            $usuario->setEmail($body['email']);
            $usuario->setSenha($body['senha'],'S');
            $resultado = $users->login();
        }
        echo json_encode($resultado);
    break;
    case "GET";
        $users = new UserController($usuario, $conexao);
        if (isset($_GET['relatorio'])) {
            $resultado = $users->buscarRelatorio();
            echo json_encode(["usuarios" => $resultado]);
        } else {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $resultado = $users->buscar($id);
            echo json_encode(["usuarios" => $resultado]);
        }
    break;
    case "DELETE";
        $id=isset($_GET['id'])?$_GET['id']:'';
        $usuario->setId($id);
        $users = new UserController($usuario,$conexao);
        $resultado = $users->excluir();
        echo json_encode([$resultado]);
    break;
    
}