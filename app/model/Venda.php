<?php

class Vendas {
    private int $id;
    private ?int $clienteId;
    private ?int $caixaId;
    private int $atendenteId;
    private DateTime $data;
    private string $formaPagamento;
    private StatusVenda $status = StatusVenda::pendente;
    private float $total;

    public function calcularTotal(array $itens): float {
        // todo: lógica de cálculo do total
        // $this->total = total;
        // return $total;
        return 888.88;
    }

    public function cancelar(): void {
        $this->status = StatusVenda::cancelada;
    }

    public function finalizar(): void {
        $this->status = StatusVenda::concluida;
    }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getClienteId(): int { return $this->clienteId; }
    public function setClienteId(int $clienteId): void { $this->clienteId = $clienteId; }

    public function getCaixaId(): int { return $this->caixaId; }
    public function setCaixaId(int $caixaId): void { $this->caixaId = $caixaId; }

    public function getAtendenteId(): int { return $this->atendenteId; }
    public function setAtendenteId(int $atendenteId): void { $this->atendenteId = $atendenteId; }

    public function getData(): DateTime { return $this->data; }
    public function setData(DateTime $data): void { $this->data = $data; }

    public function getFormaPagamento(): string { return $this->formaPagamento; }
    public function setFormaPagamento(string $formaPagamento): void { $this->formaPagamento = $formaPagamento; }

    public function getStatus(): StatusVenda { return $this->status; }
    public function setStatus(StatusVenda $status): void { $this->status = $status; }

    public function getTotal(): float { return $this->total; }
    public function setTotal(float $total): void { $this->total = $total; }
}