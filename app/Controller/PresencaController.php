<?php
namespace App\Controller;

use App\Database\PresencaDAO;
use PDOException;

class PresencaController {
    private $presencaDAO;

    public function __construct(PresencaDAO $presencaDAO) {
        $this->presencaDAO = $presencaDAO;
    }
    public function registrarPresenca($idUsuario, $tipo) {
        try {
            $resultado = $this->presencaDAO->registrarPresenca($idUsuario, $tipo);
            return $resultado;
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function listarPresencasPorRegistro($registro = null, $dataFiltro = null) {
        try {
            $presencas = $this->presencaDAO->listarPresencasPorRegistro($registro, $dataFiltro);
            return ['status' => true, 'presencas' => $presencas];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function atualizarPresenca($id, $novaDataHora) {
        try {
            $resultado = $this->presencaDAO->atualizarPresenca($id, $novaDataHora);
            return $resultado;
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
}
