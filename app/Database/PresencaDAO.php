<?php
namespace App\Database;

use PDO;
use PDOException;

class PresencaDAO {
    private $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }
    public function registrarPresenca($idUsuario, $tipo) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM presencas WHERE id_usuario = :id_usuario AND data_hora >= NOW() - INTERVAL 60 MINUTE");
            $stmt->bindParam(':id_usuario', $idUsuario);
            $stmt->execute();
            if ($stmt->fetch()) {
                return ['status' => false, 'message' => 'Presença já registrada recentemente'];
            }
            $stmt = $this->conexao->prepare("INSERT INTO presencas (id_usuario, data_hora, tipo) VALUES (:id_usuario, NOW(), :tipo)");
            $stmt->bindParam(':id_usuario', $idUsuario);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->execute();
            return ['status' => true];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function listarPresencasPorRegistro($registro = null, $dataFiltro = null) {
        try {
            $query = "
                SELECT p.id, p.data_hora, p.tipo, u.registro, u.nome 
                FROM presencas p
                INNER JOIN users u ON p.id_usuario = u.id
            ";

            $conditions = [];
            $params = [];
            if ($registro) {
                $conditions[] = "u.registro = :registro";
                $params[':registro'] = $registro;
            }
            if ($dataFiltro) {
                $conditions[] = "DATE(p.data_hora) = :dataFiltro";
                $params[':dataFiltro'] = $dataFiltro;
            }
            if ($conditions) {
                $query .= " WHERE " . implode(' AND ', $conditions);
            }
            $query .= " ORDER BY p.data_hora DESC";
            $stmt = $this->conexao->prepare($query);
            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function atualizarPresenca($id, $novaDataHora) {
        try {
            $stmt = $this->conexao->prepare("UPDATE presencas SET data_hora = :novaDataHora WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':novaDataHora', $novaDataHora);
            $stmt->execute();
            return ['status' => true, 'message' => 'Presença atualizada com sucesso'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
}
