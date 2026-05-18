<?php

enum StatusAgendamento: string {
    case cancelado   = 'cancelado';
    case marcado     = 'marcado';
    case emAndamento = 'em_andamento';
    case finalizado  = 'finalizado';
}
