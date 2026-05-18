<?php

class Auth {

    public static function login(Usuario $usuario): void {
        $_SESSION['usuario_id']    = $usuario->getId();
        $_SESSION['usuario_nome']  = $usuario->getNome();
        $_SESSION['usuario_cargo'] = $usuario->getCargo();
    }

    public static function logout(): void {
        session_destroy();
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['usuario_id']);
    }

    public static function getUsuario(): ?Usuario {
        if (!self::isLoggedIn()) {
            return null;
        }

        $u = new Usuario();
        $u->setId($_SESSION['usuario_id']);
        $u->setNome($_SESSION['usuario_nome']);
        $u->setCargo($_SESSION['usuario_cargo']);
        return $u;
    }

    public static function hasRole(string ...$roles): bool {
        $usuario = self::getUsuario();
        if ($usuario === null) {
            return false;
        }
        return in_array($usuario->getCargo(), $roles, true);
    }
}
