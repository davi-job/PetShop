<?php

class Categoria {
    private string $nome;
    private string $descricao;

    // Getters & Setters
    public function getNome(): string {return $this->nome;}
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getDescricao(): string { return $this->descricao; }
    public function setDescricao(string $descricao): void { $this->descricao = $descricao; }
}