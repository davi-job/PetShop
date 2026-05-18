<?php

class CategoriaController extends Controller {

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
        $categorias = (new CategoriaDAO())->listar();
        $this->render('categorias/list', ['categorias' => $categorias]);
    }

    private function novo(): void {
        $this->render('categorias/form', ['categoria' => null]);
    }

    private function salvar(): void {
        $categoria = new Categoria();
        $categoria->setNome($_POST['nome']);
        $categoria->setDescricao($_POST['descricao']);

        (new CategoriaDAO())->salvar($categoria);

        $_SESSION['flash'] = 'Categoria cadastrada com sucesso.';
        $this->redirect('?page=categorias');
    }

    private function editar(): void {
        $categoria = (new CategoriaDAO())->buscarPorId((int) $_GET['id']);
        $this->render('categorias/form', ['categoria' => $categoria]);
    }

    private function atualizar(): void {
        $categoria = (new CategoriaDAO())->buscarPorId((int) $_POST['id']);
        $categoria->setNome($_POST['nome']);
        $categoria->setDescricao($_POST['descricao']);

        (new CategoriaDAO())->atualizar($categoria);

        $_SESSION['flash'] = 'Categoria atualizada com sucesso.';
        $this->redirect('?page=categorias');
    }

    private function deletar(): void {
        (new CategoriaDAO())->deletar((int) $_GET['id']);

        $_SESSION['flash'] = 'Categoria removida.';
        $this->redirect('?page=categorias');
    }
}
