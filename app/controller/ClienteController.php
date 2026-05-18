<?php

class ClienteController extends Controller {

    public function handle(): void {
        $this->requireAuth();
        $acao = $_GET['acao'] ?? $_POST['acao'] ?? 'listar';

        match ($acao) {
            'novo'      => $this->novo(),
            'salvar'    => $this->salvar(),
            'editar'    => $this->editar(),
            'atualizar' => $this->atualizar(),
            'deletar'   => $this->deletar(),
            default     => $this->listar(),
        };
    }

    private function listar(): void {
        $clientes = (new ClienteDAO())->listar();
        $this->render('clientes/list', ['clientes' => $clientes]);
    }

    private function novo(): void {
        $this->render('clientes/form', ['cliente' => null]);
    }

    private function salvar(): void {
        $cliente = new Cliente();
        $cliente->setNome($_POST['nome']);
        $cliente->setCpf($_POST['cpf']);
        $cliente->setTelefone($_POST['telefone']);
        $cliente->setEmail($_POST['email']);
        $cliente->setEndereco($_POST['endereco']);

        (new ClienteDAO())->salvar($cliente);

        $_SESSION['flash'] = 'Cliente cadastrado com sucesso.';
        $this->redirect('?page=clientes');
    }

    private function editar(): void {
        $cliente = (new ClienteDAO())->buscarPorId((int) $_GET['id']);
        $this->render('clientes/form', ['cliente' => $cliente]);
    }

    private function atualizar(): void {
        $cliente = (new ClienteDAO())->buscarPorId((int) $_POST['id']);
        $cliente->setNome($_POST['nome']);
        $cliente->setCpf($_POST['cpf']);
        $cliente->setTelefone($_POST['telefone']);
        $cliente->setEmail($_POST['email']);
        $cliente->setEndereco($_POST['endereco']);

        (new ClienteDAO())->atualizar($cliente);

        $_SESSION['flash'] = 'Cliente atualizado com sucesso.';
        $this->redirect('?page=clientes');
    }

    private function deletar(): void {
        (new ClienteDAO())->deletar((int) $_GET['id']);

        $_SESSION['flash'] = 'Cliente removido.';
        $this->redirect('?page=clientes');
    }
}
