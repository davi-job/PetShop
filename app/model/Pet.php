<?php

class Pet {
    private int $id;
    private int $clienteId;
    private string $nome;
    private string $especie;
    private string $raca;
    private Porte $porte;
    private float $peso;
    private DateTime $dataNascimento;
    private string $observacoes = '';
    
    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getClienteId(): int { return $this->clienteId; }
    public function setClienteId(int $clienteId): void { $this->clienteId = $clienteId; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getEspecie(): string { return $this->especie; }
    public function setEspecie(string $especie): void { $this->especie = $especie; }

    public function getRaca(): string { return $this->raca; }
    public function setRaca(string $raca): void { $this->raca = $raca; }

    public function getPorte(): Porte { return $this->porte; }
    public function setPorte(Porte $porte): void { $this->porte = $porte; }

    public function getPeso(): float { return $this->peso; }
    public function setPeso(float $peso): void { $this->peso = $peso; }

    public function getDataNascimento(): DateTime { return $this->dataNascimento; }
    public function setDataNascimento(DateTime $dataNascimento): void { $this->dataNascimento = $dataNascimento; }

    public function getObservacoes(): string { return $this->observacoes; }
    public function setObservacoes(string $observacoes): void { $this->observacoes = $observacoes; }
}