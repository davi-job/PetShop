<?php

enum StatusAgendamento: int {
    case cancelado   = -1;
    case marcado     = 0;
    case emAndamento = 1;
    case finalizado  = 2;
}
