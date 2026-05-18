<?php

class VendaDAO extends DAO {

    public function salvar(Vendas $venda): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO venda (cliente_id, atendente_id, forma_pagamento, status, total)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $venda->getClienteId() ?: null,
            $venda->getAtendenteId(),
            $venda->getFormaPagamento(),
            $venda->getStatus()->name,
            $venda->getTotal(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM venda ORDER BY data DESC");
        $vendas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vendas[] = $this->hydrate($row);
        }
        return $vendas;
    }

    public function buscarPorId(int $id): ?Vendas {
        $stmt = $this->conn->prepare("SELECT * FROM venda WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function listarPorCliente(int $clienteId): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM venda WHERE cliente_id = ? ORDER BY data DESC"
        );
        $stmt->execute([$clienteId]);
        $vendas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vendas[] = $this->hydrate($row);
        }
        return $vendas;
    }

    public function atualizar(Vendas $venda): void {
        $stmt = $this->conn->prepare(
            "UPDATE venda SET cliente_id = ?, caixa_id = ?, forma_pagamento = ?, status = ?, total = ? WHERE id = ?"
        );
        $stmt->execute([
            $venda->getClienteId() ?: null,
            $venda->getCaixaId() ?: null,
            $venda->getFormaPagamento(),
            $venda->getStatus()->name,
            $venda->getTotal(),
            $venda->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM venda WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Vendas {
        $v = new Vendas();
        $v->setId($row['id']);
        $v->setClienteId($row['cliente_id'] !== null ? (int) $row['cliente_id'] : null);
        $v->setCaixaId($row['caixa_id'] !== null ? (int) $row['caixa_id'] : null);
        $v->setAtendenteId($row['atendente_id']);
        $v->setData(new DateTime($row['data']));
        $v->setFormaPagamento($row['forma_pagamento'] ?? '');
        $v->setStatus(StatusVenda::from($row['status']));
        $v->setTotal((float) $row['total']);
        return $v;
    }
}
