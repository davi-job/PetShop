<?php

class ItemVendaDAO extends DAO {

    public function salvar(ItemVenda $item): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO item_venda (venda_id, produto_variacao_id, agendamento_servico_id, quantidade, preco_unitario, subtotal)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $item->getVendaId(),
            $item->isProduto() ? $item->getProdutoVariacaoId() : null,
            $item->isServico() ? $item->getAgendamentoServicoId() : null,
            $item->getQuantidade(),
            $item->getPrecoUnitario(),
            $item->getSubtotal(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM item_venda");
        $itens = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = $this->hydrate($row);
        }
        return $itens;
    }

    public function buscarPorId(int $id): ?ItemVenda {
        $stmt = $this->conn->prepare("SELECT * FROM item_venda WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function listarPorVenda(int $vendaId): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM item_venda WHERE venda_id = ?"
        );
        $stmt->execute([$vendaId]);
        $itens = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = $this->hydrate($row);
        }
        return $itens;
    }

    public function atualizar(ItemVenda $item): void {
        $stmt = $this->conn->prepare(
            "UPDATE item_venda
             SET venda_id = ?, produto_variacao_id = ?, agendamento_servico_id = ?,
                 quantidade = ?, preco_unitario = ?, subtotal = ?
             WHERE id = ?"
        );
        $stmt->execute([
            $item->getVendaId(),
            $item->isProduto() ? $item->getProdutoVariacaoId() : null,
            $item->isServico() ? $item->getAgendamentoServicoId() : null,
            $item->getQuantidade(),
            $item->getPrecoUnitario(),
            $item->getSubtotal(),
            $item->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM item_venda WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): ItemVenda {
        $i = new ItemVenda();
        $i->setId($row['id']);
        $i->setVendaId($row['venda_id']);
        if ($row['produto_variacao_id'] !== null) {
            $i->setProdutoVariacaoId((int) $row['produto_variacao_id']);
        }
        if ($row['agendamento_servico_id'] !== null) {
            $i->setAgendamentoServicoId((int) $row['agendamento_servico_id']);
        }
        $i->setQuantidade((int) $row['quantidade']);
        $i->setPrecoUnitario((float) $row['preco_unitario']);
        $i->setSubtotal((float) $row['subtotal']);
        return $i;
    }
}
