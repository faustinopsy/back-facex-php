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
use App\Database\PresencaDAO;
use App\Controller\PresencaController;

$conexao = Conexao::getConexao();
$presencaDAO = new PresencaDAO($conexao);
$presencaController = new PresencaController($presencaDAO);

$body = json_decode(file_get_contents('php://input'), true);

switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        if (isset($body['tipo']) && isset($body['id_usuario'])) {
            $idUsuario = $body['id_usuario'];
            $tipo = $body['tipo'];
            $resultado = $presencaController->registrarPresenca($idUsuario, $tipo);
            echo json_encode($resultado);
        }
        break;
    case "GET":
        $registro = isset($_GET['registro']) ? $_GET['registro'] : null;
        $dataFiltro = isset($_GET['data']) ? $_GET['data'] : null;
        $resultado = $presencaController->listarPresencasPorRegistro($registro, $dataFiltro);
        echo json_encode(["presencas" => $resultado]);
        break;
    case "PUT":
        if (isset($body['id']) && isset($body['novaDataHora'])) {
            $resultado = $presencaController->atualizarPresenca($body['id'], $body['novaDataHora']);
            echo json_encode($resultado);
        }
        break;
}
