<?php
namespace App\Model;
class Users{
    private int $id;
    private string $nome;
    private string $registro;
    private array $rostos;
    private string $email; 
    private string $senha;
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }
    public function getRegistro(): string
    {
        return $this->registro;
    }
    public function setRegistro(string $registro): self
    {
        $this->registro = $registro;

        return $this;
    }
    public function getRostos(): array {
        return $this->rostos;
    }

    public function setRostos(array $rostos): self {
        $this->rostos = $rostos;
        return $this;
    }
    public function addRosto(array $rosto): self {
        $this->rostos[] = $rosto;
        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }
    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }
    public function getSenha(): string {
        return $this->senha;
    }
    public function setSenha(string $senha, string $login=''): self {
        if($login=='S'){
            $this->senha=$senha;
            return $this;
        }
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
        return $this;
    }
    public function verificarSenha(string $senha): bool {
        return password_verify($senha, $this->senha);
    }
}