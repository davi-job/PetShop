<?php

class ServicoPrecoDAO extends DAO {

    public function salvar(ServicoPreco $preco): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO servico_preco (servico_id, porte, preco) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $preco->getServicoId(),
            $preco->getPorte()->value,
            $preco->getPreco(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM servico_preco");
        $precos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $precos[] = $this->hydrate($row);
        }
        return $precos;
    }

    public function buscarPorId(int $id): ?ServicoPreco {
        $stmt = $this->conn->prepare("SELECT * FROM servico_preco WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function buscarPorPorte(int $servicoId, string $porte): ?ServicoPreco {
        $stmt = $this->conn->prepare(
            "SELECT * FROM servico_preco WHERE servico_id = ? AND porte = ?"
        );
        $stmt->execute([$servicoId, $porte]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function atualizar(ServicoPreco $preco): void {
        $stmt = $this->conn->prepare(
            "UPDATE servico_preco SET servico_id = ?, porte = ?, preco = ? WHERE id = ?"
        );
        $stmt->execute([
            $preco->getServicoId(),
            $preco->getPorte()->value,
            $preco->getPreco(),
            $preco->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM servico_preco WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): ServicoPreco {
        $s = new ServicoPreco();
        $s->setId($row['id']);
        $s->setServicoId($row['servico_id']);
        $s->setPorte(Porte::from($row['porte']));
        $s->setPreco((float) $row['preco']);
        return $s;
    }
}
