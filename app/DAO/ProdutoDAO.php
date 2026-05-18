<?php

class ProdutoDAO extends DAO {

    public function salvar(Produto $produto): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO produto (categoria_id, nome, descricao) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $produto->getCategoriaId(),
            $produto->getNome(),
            $produto->getDescricao(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM produto ORDER BY nome");
        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = $this->hydrate($row);
        }
        return $produtos;
    }

    public function buscarPorId(int $id): ?Produto {
        $stmt = $this->conn->prepare("SELECT * FROM produto WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function atualizar(Produto $produto): void {
        $stmt = $this->conn->prepare(
            "UPDATE produto SET categoria_id = ?, nome = ?, descricao = ? WHERE id = ?"
        );
        $stmt->execute([
            $produto->getCategoriaId(),
            $produto->getNome(),
            $produto->getDescricao(),
            $produto->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM produto WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Produto {
        $p = new Produto();
        $p->setId($row['id']);
        $p->setCategoriaId($row['categoria_id']);
        $p->setNome($row['nome']);
        $p->setDescricao($row['descricao'] ?? '');
        return $p;
    }
}
