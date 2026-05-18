<?php

abstract class Controller {
    abstract public function handle(): void;

    protected function render(string $view, array $data = []): void {
        extract($data, EXTR_SKIP);
        $path = __DIR__ . '/../view/' . $view . '.php';

        if (!file_exists($path)) {
            http_response_code(500);
            die("View não encontrada: $view");
        }

        require $path;
    }

    protected function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }

    protected function requireAuth(): void {
        if (!Auth::isLoggedIn()) {
            $this->redirect('?page=login');
        }
    }

    protected function requireRole(int ...$roles): void {
        $this->requireAuth();

        $usuario = Auth::getUsuario();
        foreach ($roles as $role) {
            if ($usuario->temPermissao($role)) {
                return;
            }
        }

        http_response_code(403);
        die('Acesso negado.');
    }
}
