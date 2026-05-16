<?php

class ItemVenda {
    private int $id;
    private int $vendaId;
    private int $produtoVariacaoId;
    private int $agendamentoServicoId;
    private int $quantidade;
    private float $precoUnitario;
    private float $subtotal;

    public function isProduto(): bool {
        return isset($this->produtoVariacaoId) ? true : false;
    }
    public function isServico(): bool {
        return isset($this->agendamentoServicoId) ? true : false;
    }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getVendaId(): int { return $this->vendaId; }
    public function setVendaId(int $vendaId): void { $this->vendaId = $vendaId; }

    public function getProdutoVariacaoId(): int { return $this->produtoVariacaoId; }
    public function setProdutoVariacaoId(int $produtoVariacaoId): void { $this->produtoVariacaoId = $produtoVariacaoId; }

    public function getAgendamentoServicoId(): int { return $this->agendamentoServicoId; }
    public function setAgendamentoServicoId(int $agendamentoServicoId): void { $this->agendamentoServicoId = $agendamentoServicoId; }

    public function getQuantidade(): int { return $this->quantidade; }
    public function setQuantidade(int $quantidade): void { $this->quantidade = $quantidade; }

    public function getPrecoUnitario(): float { return $this->precoUnitario; }
    public function setPrecoUnitario(float $precoUnitario): void { $this->precoUnitario = $precoUnitario; }

    public function getSubtotal(): float { return $this->subtotal; }
    public function setSubtotal(): void {
        $this->subtotal = $this->quantidade * $this->subtotal;
    }
}