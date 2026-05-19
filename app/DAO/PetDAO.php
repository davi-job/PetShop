<?php

class PetDAO extends DAO {

    public function salvar(Pet $pet): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO pet (cliente_id, nome, especie, raca, porte, peso, data_nascimento, observacoes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $pet->getClienteId(),
            $pet->getNome(),
            $pet->getEspecie(),
            $pet->getRaca(),
            $pet->getPorte()->value,
            $pet->getPeso(),
            $pet->getDataNascimento()->format('Y-m-d'),
            $pet->getObservacoes(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM pet ORDER BY nome");
        $pets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pets[] = $this->hydrate($row);
        }
        return $pets;
    }

    public function buscarPorId(int $id): ?Pet {
        $stmt = $this->conn->prepare("SELECT * FROM pet WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function listarPorCliente(int $clienteId): array {
        $stmt = $this->conn->prepare("SELECT * FROM pet WHERE cliente_id = ? ORDER BY nome");
        $stmt->execute([$clienteId]);
        $pets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pets[] = $this->hydrate($row);
        }
        return $pets;
    }

    public function atualizar(Pet $pet): void {
        $stmt = $this->conn->prepare(
            "UPDATE pet SET cliente_id = ?, nome = ?, especie = ?, raca = ?, porte = ?,
             peso = ?, data_nascimento = ?, observacoes = ? WHERE id = ?"
        );
        $stmt->execute([
            $pet->getClienteId(),
            $pet->getNome(),
            $pet->getEspecie(),
            $pet->getRaca(),
            $pet->getPorte()->value,
            $pet->getPeso(),
            $pet->getDataNascimento()->format('Y-m-d'),
            $pet->getObservacoes(),
            $pet->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM pet WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Pet {
        $p = new Pet();
        $p->setId($row['id']);
        $p->setClienteId($row['cliente_id']);
        $p->setNome($row['nome']);
        $p->setEspecie($row['especie']);
        $p->setRaca($row['raca']);
        $p->setPorte(Porte::from($row['porte']));
        $p->setPeso((float) $row['peso']);
        $p->setDataNascimento(new DateTime($row['data_nascimento']));
        $p->setObservacoes($row['observacoes'] ?? '');
        return $p;
    }
}
