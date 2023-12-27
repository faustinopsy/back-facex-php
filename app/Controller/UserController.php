<?php
namespace App\Controller;
use PDO;
class UserController{
    private $users;
    private $conecta;

    public function __construct($usuario,$conexao){
        $this->users=$usuario;
        $this->conecta=$conexao;
    }
    public function inserir() {
        try {
            $nome=$this->users->getNome();
            $stmt = $this->conecta->prepare("SELECT id FROM users WHERE nome = :nome");
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            if ($stmt->fetch()) {
                return ['status' => false, 'message' => 'Nome de usuário já existe'];
            }
            $stmt = $this->conecta->prepare("INSERT INTO users (nome, registro) VALUES (:nome, :registro)");
            $registro = $this->users->getRegistro();
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':registro', $registro);
            $stmt->execute();
            $userId = $this->conecta->lastInsertId();

            foreach ($this->users->getRostos() as $rosto) {
                $rostoJson = json_encode($rosto); 
                $this->inserirRosto($userId, $rostoJson);
            }

            return ['status' => true, 'id' => $userId];
        } catch (\PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function inserirRosto($userId, $rostoJson) {
        try {
            $stmt = $this->conecta->prepare("INSERT INTO faces (idusers, faces) VALUES (:idusers, :faces)");
            $stmt->bindParam(':idusers', $userId);
            $stmt->bindParam(':faces', $rostoJson);
            $stmt->execute();
        } catch (\PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function buscar($id = null) {
        try {
            $resultados = [];
    
            if ($id) {
                $stmt = $this->conecta->prepare("SELECT * FROM users WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                $stmt = $this->conecta->prepare("SELECT * FROM users");
            }
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($usuarios as $usuario) {
                $stmt = $this->conecta->prepare("SELECT faces FROM faces WHERE idusers = :idusers");
                $stmt->bindParam(':idusers', $usuario['id'], PDO::PARAM_INT);
                $stmt->execute();
                $faces = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
                $usuario['faces'] = array_map('json_decode', $faces); 
                $resultados[] = $usuario;
            }
    
            return $resultados;
        } catch (\PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function buscarRelatorio() {
        try {
            $stmt = $this->conecta->prepare("SELECT id, nome, registro FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function excluir(){
        try {
        $stmt = $this->conecta->prepare("DELETE FROM users where id=:id");
        $id = $this->users->getId();
        $stmt->bindParam(':id', $id);
        $stmt->execute();
            return ['status' => true];
        } catch (\PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function registrar() {
        try {
            $email = $this->users->getEmail();
            $stmt = $this->conecta->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetch()) {
                return ['status' => false, 'message' => 'E-mail já cadastrado'];
            }

            $stmt = $this->conecta->prepare("INSERT INTO users (nome, registro, email, senha) VALUES (:nome, :registro, :email, :senha)");
            $nome = $this->users->getNome();
            $registro = $this->users->getRegistro();
            $senha = $this->users->getSenha(); 
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':registro', $registro);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->execute();

            return ['status' => true, 'id' => $this->conecta->lastInsertId()];
        } catch (\PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function login() {
        try {
            $email = $this->users->getEmail();
            $senha = $this->users->getSenha();

            $stmt = $this->conecta->prepare("SELECT id, nome, senha FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario) {
                $this->users->setSenha($usuario['senha'],'S');
                if ($this->users->verificarSenha($senha)) {
                    unset($usuario['senha']); 
                    return ['status' => true, 'usuario' => $usuario];
                }
            }

            return ['status' => false, 'message' => 'E-mail ou senha incorretos'];
        } catch (\PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
}