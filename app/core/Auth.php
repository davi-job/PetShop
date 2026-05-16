<?php

class Auth {
    private ?Usuario $usuario;
    
    public function login(Usuario $usuario): bool {
        // todo: lógica de login
        return true;
    }

    public function logout(): void {
        $this->usuario = null;
        // todo: redirecionamento
    }

    public function isLoggedIn(): bool {
        return isset($this->usuario);
    }

    public function getUsuario(): Usuario { return $this->usuario; }
}