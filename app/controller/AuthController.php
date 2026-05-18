<?php

class AuthController extends Controller {

    public function handle(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'login') {
            $this->login();
            return;
        }

        if (($_GET['acao'] ?? '') === 'logout') {
            Auth::logout();
            $this->redirect('?page=login');
        }

        $this->render('auth/login');
    }

    private function login(): void {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $dao     = new UsuarioDAO();
        $usuario = $dao->buscarPorEmail($email);

        if ($usuario === null || !$usuario->verificarSenha($senha)) {
            $this->render('auth/login', ['erro' => 'Email ou senha inválidos.']);
            return;
        }

        if (!$usuario->getAtivo()) {
            $this->render('auth/login', ['erro' => 'Usuário inativo.']);
            return;
        }

        Auth::login($usuario);
        $this->redirect('?page=clientes');
    }
}
