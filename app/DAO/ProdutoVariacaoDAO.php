<?php

class ProdutoVariacaoDAO extends DAO {

    public function salvar(ProdutoVariacao $variacao): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO produto_variacao (produto_id, nome, preco, estoque) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $variacao->getProdutoId(),
            $variacao->getNome(),
            $variacao->getPreco(),
            $variacao->getEstoque(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM produto_variacao ORDER BY nome");
        $variacoes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $variacoes[] = $this->hydrate($row);
        }
        return $variacoes;
    }

    public function buscarPorId(int $id): ?ProdutoVariacao {
        $stmt = $this->conn->prepare("SELECT * FROM produto_variacao WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function listarPorProduto(int $produtoId): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM produto_variacao WHERE produto_id = ? ORDER BY nome"
        );
        $stmt->execute([$produtoId]);
        $variacoes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $variacoes[] = $this->hydrate($row);
        }
        return $variacoes;
    }

    public function atualizar(ProdutoVariacao $variacao): void {
        $stmt = $this->conn->prepare(
            "UPDATE produto_variacao SET produto_id = ?, nome = ?, preco = ?, estoque = ? WHERE id = ?"
        );
        $stmt->execute([
            $variacao->getProdutoId(),
            $variacao->getNome(),
            $variacao->getPreco(),
            $variacao->getEstoque(),
            $variacao->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM produto_variacao WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): ProdutoVariacao {
        $v = new ProdutoVariacao();
        $v->setId($row['id']);
        $v->setProdutoId($row['produto_id']);
        $v->setNome($row['nome'] ?? '');
        $v->setPreco((float) $row['preco']);
        $v->setEstoque((int) $row['estoque']);
        return $v;
    }
}
