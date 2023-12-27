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
use App\Controller\PresencaController;

$conexao = Conexao::getConexao();
$usuario = new Users();


$body = json_decode(file_get_contents('php://input'), true);


switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        if ($body['tipo']== 'E') {
            $idUsuario = $body['id_usuario'];
            $tipo = $body['tipo']; 
            $presencaController = new PresencaController($conexao);
            $resultado = $presencaController->registrarPresenca($idUsuario, $tipo);
            echo json_encode($resultado);
        }
        break;
        case "GET":
            $presencaController = new PresencaController($conexao);
            $registro = isset($_GET['registro']) ? $_GET['registro'] : null;
            $dataFiltro = isset($_GET['data']) ? $_GET['data'] : null;
            $resultado = $presencaController->listarPresencasPorRegistro($registro, $dataFiltro);
            echo json_encode(["presencas" => $resultado]);
            break;
        
        case "PUT":
            $body = json_decode(file_get_contents('php://input'), true);
            if (isset($body['id']) && isset($body['novaDataHora'])) {
                $presencaController = new PresencaController($conexao);
                $resultado = $presencaController->atualizarPresenca($body['id'], $body['novaDataHora']);
                echo json_encode($resultado);
            }
            break;
    
    
}