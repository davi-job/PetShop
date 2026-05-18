<?php

class Agendamento {
    private int $id;
    private int $petId;
    private int $criadoPor;
    private DateTime $dataHora;
    private StatusAgendamento $status = StatusAgendamento::marcado;
    private DateTime $criadoEm;
    
    public function cancelar(): void {
        $this->status = StatusAgendamento::cancelado;
    }
    public function iniciar(): void {
        $this->status = StatusAgendamento::emAndamento;
    }
    public function concluir(): void {
        $this->status = StatusAgendamento::finalizado;
    }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getPetId(): int { return $this->petId; }
    public function setPetId(int $petId): void { $this->petId = $petId; }

    public function getCriadoPor(): int { return $this->criadoPor; }
    public function setCriadoPor(int $criadoPor): void { $this->criadoPor = $criadoPor; }

    public function getDataHora(): \DateTime { return $this->dataHora; }
    public function setDataHora(\DateTime $dataHora): void { $this->dataHora = $dataHora; }

    public function getStatus(): StatusAgendamento { return $this->status; }
    public function setStatus(StatusAgendamento $status): void { $this->status = $status; }

    public function getCriadoEm(): \DateTime { return $this->criadoEm; }
    public function setCriadoEm(\DateTime $criadoEm): void { $this->criadoEm = $criadoEm; }
}