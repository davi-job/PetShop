<?php

enum Cargo: int {
    case funcionario = 0;
    case atendente = 1;
    case caixa = 2;
    case gerente = 3;
}

class Usuario {
    private int $id;
    private string $nome;
    private string $email;
    private string $senhaHash;
    private string $cargo;
    private bool $ativo;

    public function verificarSenha(string $senha): bool {
        // todo: hash da $senha e comparação
        return true;
    }

    public function temPermissao(int $permissaoMinima): bool {
        return Cargo::{$this->cargo}->value >= $permissaoMinima;
    }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }
    
    public function setSenha(string $senha): void {
        // todo: hash da $senha
        $this->senhaHash = $senha;
    }

    public function getCargo(): string { return $this->cargo; }
    public function setCargo(Cargo $cargo): void { $this->cargo = Cargo::{$cargo}; }
    
    public function getAtivo(): bool { return $this->ativo; }
    public function setAtivo(bool $ativo): void { $this->ativo = $ativo; }
}