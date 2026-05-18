<?php

class CategoriaDAO extends DAO {

    public function salvar(Categoria $categoria): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO categoria (nome, descricao) VALUES (?, ?)"
        );
        $stmt->execute([$categoria->getNome(), $categoria->getDescricao()]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM categoria ORDER BY nome");
        $categorias = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categorias[] = $this->hydrate($row);
        }
        return $categorias;
    }

    public function buscarPorId(int $id): ?Categoria {
        $stmt = $this->conn->prepare("SELECT * FROM categoria WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function atualizar(Categoria $categoria): void {
        $stmt = $this->conn->prepare(
            "UPDATE categoria SET nome = ?, descricao = ? WHERE id = ?"
        );
        $stmt->execute([$categoria->getNome(), $categoria->getDescricao(), $categoria->getId()]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM categoria WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Categoria {
        $c = new Categoria();
        $c->setId($row['id']);
        $c->setNome($row['nome']);
        $c->setDescricao($row['descricao'] ?? '');
        return $c;
    }
}
