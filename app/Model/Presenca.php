<?php
namespace App\Model;

class Presenca {
    private int $id;
    private int $idUsuario;
    private string $dataHora;
    private string $tipo;

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getIdUsuario(): int
    {
        return $this->idUsuario;
    }
    public function setIdUsuario(int $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }
    public function getDataHora(): string
    {
        return $this->dataHora;
    }
    public function setDataHora(string $dataHora): self
    {
        $this->dataHora = $dataHora;

        return $this;
    }
    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
