<?php

class Produto {
    private int $id;
    private int $categoriaId;
    private string $nome;
    private string $descricao;

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getCategoriaId(): int { return $this->categoriaId; }
    public function setCategoriaId(int $categoriaId): void { $this->categoriaId = $categoriaId; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getDescricao(): string { return $this->descricao; }
    public function setDescricao(string $descricao): void { $this->descricao = $descricao; }
}