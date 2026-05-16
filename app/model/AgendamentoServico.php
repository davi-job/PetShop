<?php

class AgendamentoServico {
    private int $id;
    private int $agendamentoId;
    private int $servicoId;
    private int $funcionarioId;
    private float $precoCobrado;
    private string $observacoes;

    public function executar(int $funcionarioId, string $observacoes = '') {
        if (isset($this->funcionarioId)) {
            echo 'WARN: Esse serviço já foi atribuído para outro funcionário, nenhuma modificação foi feita.';
            return;
        }
        
        $this->funcionarioId = $funcionarioId;
        $this->observacoes = $observacoes;
    }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getAgendamentoId(): int { return $this->agendamentoId; }
    public function setAgendamentoId(int $agendamentoId): void { $this->agendamentoId = $agendamentoId; }

    public function getServicoId(): int { return $this->servicoId; }
    public function setServicoId(int $servicoId): void { $this->servicoId = $servicoId; }

    public function getFuncionarioId(): int { return $this->funcionarioId; }

    public function getPrecoCobrado(): float { return $this->precoCobrado; }
    public function setPrecoCobrado(float $precoCobrado): void { $this->precoCobrado = $precoCobrado; }

    public function getObservacoes(): string { return $this->observacoes; }
    public function setObservacoes(string $observacoes): void { $this->observacoes = $observacoes; }

}