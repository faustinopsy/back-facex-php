<?php
namespace App\Controller;

use App\Database\UserDAO;
use App\Model\User;
use PDOException;

class UserController {
    private $userDAO;

    public function __construct(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    public function inserir(User $user) {
        try {
            $usuarioExistente = $this->userDAO->buscarUsuarioPorNome($user->getNome());
            if ($usuarioExistente) {
                return ['status' => false, 'message' => 'Nome de usuário já existe'];
            }
            $userId = $this->userDAO->inserirUsuario($user->getNome(), $user->getRegistro());
            foreach ($user->getRostos() as $rosto) {
                $rostoJson = json_encode($rosto);
                $this->userDAO->inserirRosto($userId, $rostoJson);
            }
            return ['status' => true, 'id' => $userId];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function buscarTodos() {
        try {
            $usuariosComRosto = []; 
            $usuarios = $this->userDAO->buscarTodosUsuarios();
            foreach ($usuarios as $usuario) {
                $rostos = $this->userDAO->buscarRostosPorUsuario($usuario['id']);
                if (!empty($rostos)) { 
                    $usuario['rostos'] = $rostos; 
                    $usuariosComRosto[] = $usuario; 
                }
            }
            
            return $usuariosComRosto;
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    

    public function buscarPorId($id) {
        try {
            $usuario = $this->userDAO->buscarUsuarioPorId($id);
            if ($usuario) {
                $usuario['rosto'] = $this->userDAO->buscarRostosPorUsuario($usuario['id']);
                return ['status' => true, 'usuario' => $usuario];
            }
            return ['status' => false, 'message' => 'Usuário não encontrado'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function atualizar(User $user) {
        try {
            $this->userDAO->atualizarUsuario($user->getId(), $user->getNome(), $user->getRegistro());
            return ['status' => true, 'message' => 'Usuário atualizado com sucesso'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function excluir($id) {
        try {
            $this->userDAO->excluirUsuario($id);
            return ['status' => true, 'message' => 'Usuário excluído com sucesso'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function registrar(User $user) {
        try {
            $usuarioExistente = $this->userDAO->buscarUsuarioPorEmail($user->getEmail());
            if ($usuarioExistente) {
                return ['status' => false, 'message' => 'E-mail já cadastrado'];
            }
            $userId = $this->userDAO->inserirUsuarioCompleto($user);
            return ['status' => true, 'id' => $userId];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function login(User $user) { 
        try {
            $usuario = $this->userDAO->buscarUsuarioPorEmail($user->getEmail());
            if ($usuario && password_verify($user->getSenha(), $usuario['senha'])) {
                unset($usuario['senha']); // Remove a senha por segurança
                return ['status' => true, 'usuario' => $usuario];
            }
            return ['status' => false, 'message' => 'E-mail ou senha incorretos'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
}
