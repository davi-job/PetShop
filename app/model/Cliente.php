<?php

class Cliente {
    private int $id;
    private string $nome;
    private string $cpf;
    private string $telefone;
    private string $email;
    private string $endereco;

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getCpf(): string { return $this->cpf; }
    public function setCpf(string $cpf): void { $this->cpf = $cpf; }

    public function getTelefone(): string { return $this->telefone; }
    public function setTelefone(string $telefone): void { $this->telefone = $telefone; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getEndereco(): string { return $this->endereco; }
    public function setEndereco(string $endereco): void { $this->endereco = $endereco; }
}