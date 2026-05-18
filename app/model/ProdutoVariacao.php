<?php

class ProdutoVariacao {
    private int $id;
    private int $produtoId;
    private string $nome;
    private float $preco;
    private int $estoque;

    public function temEstoque(int $quantidade): bool {
        return $this->estoque >= $quantidade;
    }

    public function decrementarEstoque(int $quantidade): void {
        if (!$this->temEstoque($quantidade)) {
            echo 'WARN: Quantidade em estoque inferior à informada, nenhuma modificação foi feita.';
            return;
        }

        $this->estoque -= $quantidade;
    }
    public function incrementarEstoque(int $quantidade): void {
        $this->estoque += $quantidade;
    }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getProdutoId(): int { return $this->produtoId; }
    public function setProdutoId(int $produtoId): void { $this->produtoId = $produtoId; }

    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): void { $this->nome = $nome; }

    public function getPreco(): float { return $this->preco; }
    public function setPreco(float $preco): void { 
        if ($preco < 0) {
            echo 'WARN: Preço não pode ser menor que 0, nenhuma modificação foi feita.';
            return;
        }

        $this->preco = $preco;
     }

    public function getEstoque(): int { return $this->estoque; }
    public function setEstoque(int $estoque): void { $this->estoque = $estoque; }
}