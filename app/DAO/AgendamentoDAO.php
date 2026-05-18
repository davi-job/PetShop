<?php

class AgendamentoDAO extends DAO {

    public function salvar(Agendamento $agendamento): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO agendamento (pet_id, criado_por, data_hora, status)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $agendamento->getPetId(),
            $agendamento->getCriadoPor(),
            $agendamento->getDataHora()->format('Y-m-d H:i:s'),
            $agendamento->getStatus()->value,
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM agendamento ORDER BY data_hora DESC");
        $agendamentos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $agendamentos[] = $this->hydrate($row);
        }
        return $agendamentos;
    }

    public function buscarPorId(int $id): ?Agendamento {
        $stmt = $this->conn->prepare("SELECT * FROM agendamento WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function listarPorPet(int $petId): array {
        $stmt = $this->conn->prepare(
            "SELECT * FROM agendamento WHERE pet_id = ? ORDER BY data_hora DESC"
        );
        $stmt->execute([$petId]);
        $agendamentos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $agendamentos[] = $this->hydrate($row);
        }
        return $agendamentos;
    }

    public function listarPorFuncionario(int $funcionarioId): array {
        $stmt = $this->conn->prepare(
            "SELECT DISTINCT a.* FROM agendamento a
             JOIN agendamento_servico s ON s.agendamento_id = a.id
             WHERE s.funcionario_id = ?
             ORDER BY a.data_hora DESC"
        );
        $stmt->execute([$funcionarioId]);
        $agendamentos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $agendamentos[] = $this->hydrate($row);
        }
        return $agendamentos;
    }

    public function verificarDisponibilidade(string $dataHora, int $funcionarioId): bool {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM agendamento_servico s
             JOIN agendamento a ON a.id = s.agendamento_id
             WHERE s.funcionario_id = ?
               AND a.data_hora = ?
               AND a.status NOT IN ('cancelado', 'finalizado')"
        );
        $stmt->execute([$funcionarioId, $dataHora]);
        return (int) $stmt->fetchColumn() === 0;
    }

    public function atualizar(Agendamento $agendamento): void {
        $stmt = $this->conn->prepare(
            "UPDATE agendamento SET pet_id = ?, data_hora = ?, status = ? WHERE id = ?"
        );
        $stmt->execute([
            $agendamento->getPetId(),
            $agendamento->getDataHora()->format('Y-m-d H:i:s'),
            $agendamento->getStatus()->value,
            $agendamento->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM agendamento WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Agendamento {
        $a = new Agendamento();
        $a->setId($row['id']);
        $a->setPetId($row['pet_id']);
        $a->setCriadoPor($row['criado_por']);
        $a->setDataHora(new DateTime($row['data_hora']));
        $a->setStatus(StatusAgendamento::from($row['status']));
        $a->setCriadoEm(new DateTime($row['criado_em']));
        return $a;
    }
}
