<?php

class ServicoPreco {
    private int $id;
    private int $servicoId;
    private Porte $porte;
    private float $preco;

    // Getters & Setters
    public function getId(): int { return $this->id; }

    public function setId(int $id): void { $this->id = $id; }

    public function getServicoId(): int { return $this->servicoId; }

    public function setServicoId(int $servicoId): void { $this->servicoId = $servicoId; }

    public function getPorte(): Porte { return $this->porte; }

    public function setPorte(Porte $porte): void { $this->porte = $porte; }

    public function getPreco(): float { return $this->preco; }

    public function setPreco(float $preco): void { $this->preco = $preco; }
}