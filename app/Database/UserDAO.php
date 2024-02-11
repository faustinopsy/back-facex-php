<?php
namespace App\Database;

use PDO;
use PDOException;

class UserDAO {
    private $conexao;
    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }
    public function buscarUsuarioPorNome($nome) {
        try {
            $stmt = $this->conexao->prepare("SELECT id FROM users WHERE nome = :nome");
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function inserirUsuario($nome, $registro) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO users (nome, registro) VALUES (:nome, :registro)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':registro', $registro);
            $stmt->execute();
            return $this->conexao->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function inserirRosto($userId, $rostoJson) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO faces (idusers, faces) VALUES (:idusers, :faces)");
            $stmt->bindParam(':idusers', $userId);
            $stmt->bindParam(':faces', $rostoJson);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function buscarTodosUsuarios() {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function buscarUsuarioPorId($id) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function atualizarUsuario($id, $nome, $registro) {
        try {
            $stmt = $this->conexao->prepare("UPDATE users SET nome = :nome, registro = :registro WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':registro', $registro);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function excluirUsuario($id) {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM users WHERE registro = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function buscarRostosPorUsuario($userId) {
        try {
            $stmt = $this->conexao->prepare("SELECT faces FROM faces WHERE idusers = :idusers");
            $stmt->bindParam(':idusers', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $faces = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
            return array_map('json_decode', $faces); 
   
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function buscarUsuarioPorEmail($email) {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    public function inserirUsuarioCompleto($user) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO users (nome, registro, email, senha) VALUES (:nome, :registro, :email, :senha)");
            
            $stmt->bindValue(':nome', $user->getNome());
            $stmt->bindValue(':registro', $user->getRegistro());
            $stmt->bindValue(':email', $user->getEmail());
            $senhaHash = $user->getSenha(); 
            $stmt->bindValue(':senha', $senhaHash);
            $stmt->execute();
            return $this->conexao->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
    
}
