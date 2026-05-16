<?php

enum Status: int {
    case cancelado = -1;
    case marcado = 0;
    case emAndamento = 1;
    case finalizado = 2;
}

class Agendamento {
    private int $id;
    private int $petId;
    private int $criadoPor;
    private DateTime $dataHora;
    private Status $status = Status::marcado;
    private DateTime $criadoEm;
    
    public function cancelar(): void {
        $this->status = Status::cancelado;
    }
    public function iniciar(): void {
        $this->status = Status::emAndamento;
    }
    public function concluir(): void {
        $this->status = Status::finalizado;
    }

    // Getters & Setters
    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getPetId(): int { return $this->petId; }
    public function setPetId(int $petId): void { $this->petId = $petId; }

    public function getCriadoPor(): int { return $this->criadoPor; }

    public function getDataHora(): \DateTime { return $this->dataHora; }
    public function setDataHora(\DateTime $dataHora): void { $this->dataHora = $dataHora; }

    public function getStatus(): Status { return $this->status; }

    public function getCriadoEm(): \DateTime { return $this->criadoEm; }
}