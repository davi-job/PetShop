<?php

class AgendamentoServicoDAO extends DAO {

    public function salvar(AgendamentoServico $item): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO agendamento_servico (agendamento_id, servico_id, funcionario_id, preco_cobrado, observacoes)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $item->getAgendamentoId(),
            $item->getServicoId(),
            $item->getFuncionarioId() ?: null,
            $item->getPrecoCobrado(),
            $item->getObservacoes(),
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM agendamento_servico");
        $itens = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = $this->hydrate($row);
        }
        return $itens;
    }

    public function buscarPorId(int $id): ?AgendamentoServico {
        $stmt = $this->conn->prepare("SELECT * FROM agendamento_servico WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function listarPorAgendamento(int $agendamentoId): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM agendamento_servico WHERE agendamento_id = ?"
        );
        $stmt->execute([$agendamentoId]);
        $itens = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $itens[] = $this->hydrate($row);
        }
        return $itens;
    }

    public function atualizar(AgendamentoServico $item): void {
        $stmt = $this->conn->prepare(
            "UPDATE agendamento_servico
             SET agendamento_id = ?, servico_id = ?, funcionario_id = ?, preco_cobrado = ?, observacoes = ?
             WHERE id = ?"
        );
        $stmt->execute([
            $item->getAgendamentoId(),
            $item->getServicoId(),
            $item->getFuncionarioId() ?: null,
            $item->getPrecoCobrado(),
            $item->getObservacoes(),
            $item->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM agendamento_servico WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): AgendamentoServico {
        $s = new AgendamentoServico();
        $s->setId($row['id']);
        $s->setAgendamentoId($row['agendamento_id']);
        $s->setServicoId($row['servico_id']);
        $s->setPrecoCobrado((float) $row['preco_cobrado']);
        $s->setObservacoes($row['observacoes'] ?? '');
        if ($row['funcionario_id'] !== null) {
            $s->executar((int) $row['funcionario_id'], $row['observacoes'] ?? '');
        }
        return $s;
    }
}
