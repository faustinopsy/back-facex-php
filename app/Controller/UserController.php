<?php
namespace App\Controller;

use App\Database\UsuarioDAO;
use App\Model\User;
use PDOException;

class UserController {
    private $usuarioDAO;

    public function __construct(UsuarioDAO $usuarioDAO) {
        $this->usuarioDAO = $usuarioDAO;
    }

    public function inserir(User $user) {
        try {
            $usuarioExistente = $this->usuarioDAO->buscarUsuarioPorNome($user->getNome());
            if ($usuarioExistente) {
                return ['status' => false, 'message' => 'Nome de usuário já existe'];
            }
            $userId = $this->usuarioDAO->inserirUsuario($user->getNome(), $user->getRegistro());
            foreach ($user->getRostos() as $rosto) {
                $rostoJson = json_encode($rosto);
                $this->usuarioDAO->inserirRosto($userId, $rostoJson);
            }
            return ['status' => true, 'id' => $userId];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function buscarTodos() {
        try {
            $usuariosComRosto = []; 
            $usuarios = $this->usuarioDAO->buscarTodosUsuarios();
            foreach ($usuarios as $usuario) {
                $rostos = $this->usuarioDAO->buscarRostosPorUsuario($usuario['id']);
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
            $usuario = $this->usuarioDAO->buscarUsuarioPorId($id);
            if ($usuario) {
                $usuario['rosto'] = $this->usuarioDAO->buscarRostosPorUsuario($usuario['id']);
                return ['status' => true, 'usuario' => $usuario];
            }
            return ['status' => false, 'message' => 'Usuário não encontrado'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function atualizar(User $user) {
        try {
            $this->usuarioDAO->atualizarUsuario($user->getId(), $user->getNome(), $user->getRegistro());
            return ['status' => true, 'message' => 'Usuário atualizado com sucesso'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function excluir($id) {
        try {
            $this->usuarioDAO->excluirUsuario($id);
            return ['status' => true, 'message' => 'Usuário excluído com sucesso'];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
    public function registrar(User $user) {
        try {
            $usuarioExistente = $this->usuarioDAO->buscarUsuarioPorEmail($user->getEmail());
            if ($usuarioExistente) {
                return ['status' => false, 'message' => 'E-mail já cadastrado'];
            }
            $userId = $this->usuarioDAO->inserirUsuarioCompleto($user);
            return ['status' => true, 'id' => $userId];
        } catch (PDOException $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function login(User $user) { 
        try {
            $usuario = $this->usuarioDAO->buscarUsuarioPorEmail($user->getEmail());
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
