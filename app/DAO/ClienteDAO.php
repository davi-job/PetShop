<?php

class ClienteDAO extends DAO {

    public function salvar(Cliente $cliente): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO cliente (nome, cpf, telefone, email, endereco)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $cliente->getNome(),
            $cliente->getCpf(),
            $cliente->getTelefone(),
            $cliente->getEmail(),
            $cliente->getEndereco(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM cliente ORDER BY nome");
        $clientes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = $this->hydrate($row);
        }
        return $clientes;
    }

    public function buscarPorId(int $id): ?Cliente {
        $stmt = $this->conn->prepare("SELECT * FROM cliente WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function atualizar(Cliente $cliente): void {
        $stmt = $this->conn->prepare(
            "UPDATE cliente SET nome = ?, cpf = ?, telefone = ?, email = ?, endereco = ? WHERE id = ?"
        );
        $stmt->execute([
            $cliente->getNome(),
            $cliente->getCpf(),
            $cliente->getTelefone(),
            $cliente->getEmail(),
            $cliente->getEndereco(),
            $cliente->getId(),
        ]);
    }

    public function buscarPorPet(int $petId): ?Cliente {
        $stmt = $this->conn->prepare(
            "SELECT c.* FROM cliente c
             JOIN pet p ON p.cliente_id = c.id
             WHERE p.id = ?"
        );
        $stmt->execute([$petId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM cliente WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Cliente {
        $c = new Cliente();
        $c->setId($row['id']);
        $c->setNome($row['nome']);
        $c->setCpf($row['cpf']);
        $c->setTelefone($row['telefone']);
        $c->setEmail($row['email']);
        $c->setEndereco($row['endereco']);
        return $c;
    }
}
