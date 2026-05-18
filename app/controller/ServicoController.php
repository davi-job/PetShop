<?php

class ServicoController extends Controller {

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
        $servicos = (new ServicoDAO())->listar();
        $this->render('servicos/list', ['servicos' => $servicos]);
    }

    private function novo(): void {
        $this->render('servicos/form', ['servico' => null]);
    }

    private function salvar(): void {
        $servico = new Servico();
        $servico->setNome($_POST['nome']);
        $servico->setDescricao($_POST['descricao']);

        (new ServicoDAO())->salvar($servico);

        $_SESSION['flash'] = 'Serviço cadastrado com sucesso.';
        $this->redirect('?page=servicos');
    }

    private function editar(): void {
        $servico = (new ServicoDAO())->buscarPorId((int) $_GET['id']);
        $this->render('servicos/form', ['servico' => $servico]);
    }

    private function atualizar(): void {
        $servico = (new ServicoDAO())->buscarPorId((int) $_POST['id']);
        $servico->setNome($_POST['nome']);
        $servico->setDescricao($_POST['descricao']);

        (new ServicoDAO())->atualizar($servico);

        $_SESSION['flash'] = 'Serviço atualizado com sucesso.';
        $this->redirect('?page=servicos');
    }

    private function deletar(): void {
        (new ServicoDAO())->deletar((int) $_GET['id']);

        $_SESSION['flash'] = 'Serviço removido.';
        $this->redirect('?page=servicos');
    }
}
