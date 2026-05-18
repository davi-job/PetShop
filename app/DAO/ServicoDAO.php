<?php

class ServicoDAO extends DAO {

    public function salvar(Servico $servico): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO servico (nome, descricao) VALUES (?, ?)"
        );
        $stmt->execute([$servico->getNome(), $servico->getDescricao()]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM servico ORDER BY nome");
        $servicos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $servicos[] = $this->hydrate($row);
        }
        return $servicos;
    }

    public function buscarPorId(int $id): ?Servico {
        $stmt = $this->conn->prepare("SELECT * FROM servico WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function atualizar(Servico $servico): void {
        $stmt = $this->conn->prepare(
            "UPDATE servico SET nome = ?, descricao = ? WHERE id = ?"
        );
        $stmt->execute([$servico->getNome(), $servico->getDescricao(), $servico->getId()]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM servico WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Servico {
        $s = new Servico();
        $s->setId($row['id']);
        $s->setNome($row['nome']);
        $s->setDescricao($row['descricao'] ?? '');
        return $s;
    }
}
