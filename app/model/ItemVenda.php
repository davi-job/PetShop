<?php

class ItemVenda {
    private int $id;
    private int $vendaId;
    private ?int $produtoVariacaoId = null;
    private ?int $agendamentoServicoId = null;
    private ?int $servicoId = null;
    private ?Porte $porte = null;
    private int $quantidade;
    private float $precoUnitario;
    private float $subtotal;

    public function isProduto(): bool { return isset($this->produtoVariacaoId); }
    public function isServicoDireto(): bool { return isset($this->servicoId); }
    public function isServico(): bool { return isset($this->agendamentoServicoId); }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getVendaId(): int { return $this->vendaId; }
    public function setVendaId(int $vendaId): void { $this->vendaId = $vendaId; }

    public function getProdutoVariacaoId(): ?int { return $this->produtoVariacaoId; }
    public function setProdutoVariacaoId(int $produtoVariacaoId): void { $this->produtoVariacaoId = $produtoVariacaoId; }

    public function getAgendamentoServicoId(): ?int { return $this->agendamentoServicoId; }
    public function setAgendamentoServicoId(int $agendamentoServicoId): void { $this->agendamentoServicoId = $agendamentoServicoId; }

    public function getServicoId(): ?int { return $this->servicoId; }
    public function setServicoId(int $servicoId): void { $this->servicoId = $servicoId; }

    public function getPorte(): ?Porte { return $this->porte; }
    public function setPorte(?Porte $porte): void { $this->porte = $porte; }

    public function getQuantidade(): int { return $this->quantidade; }
    public function setQuantidade(int $quantidade): void { $this->quantidade = $quantidade; }

    public function getPrecoUnitario(): float { return $this->precoUnitario; }
    public function setPrecoUnitario(float $precoUnitario): void { $this->precoUnitario = $precoUnitario; }

    public function getSubtotal(): float { return $this->subtotal; }
    public function setSubtotal(float $subtotal): void { $this->subtotal = $subtotal; }
}
