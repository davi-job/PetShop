<?php

class ProdutoController extends Controller {

    public function handle(): void {
        $this->requireAuth();
        $acao = $_GET['acao'] ?? $_POST['acao'] ?? 'listar';

        match ($acao) {
            'novo'              => $this->novo(),
            'salvar'            => $this->salvar(),
            'editar'            => $this->editar(),
            'atualizar'         => $this->atualizar(),
            'deletar'           => $this->deletar(),
            'novaVariacao'      => $this->novaVariacao(),
            'salvarVariacao'    => $this->salvarVariacao(),
            'deletarVariacao'   => $this->deletarVariacao(),
            default             => $this->listar(),
        };
    }

    private function listar(): void {
        $produtos = (new ProdutoDAO())->listar();
        $this->render('produtos/list', ['produtos' => $produtos]);
    }

    private function novo(): void {
        $categorias = (new CategoriaDAO())->listar();
        $this->render('produtos/form', ['produto' => null, 'categorias' => $categorias]);
    }

    private function salvar(): void {
        $produto = new Produto();
        $produto->setCategoriaId((int) $_POST['categoria_id']);
        $produto->setNome($_POST['nome']);
        $produto->setDescricao($_POST['descricao']);

        (new ProdutoDAO())->salvar($produto);

        $_SESSION['flash'] = 'Produto cadastrado com sucesso.';
        $this->redirect('?page=produtos');
    }

    private function editar(): void {
        $produto    = (new ProdutoDAO())->buscarPorId((int) $_GET['id']);
        $categorias = (new CategoriaDAO())->listar();
        $variacoes  = (new ProdutoVariacaoDAO())->listarPorProduto($produto->getId());
        $this->render('produtos/form', [
            'produto'    => $produto,
            'categorias' => $categorias,
            'variacoes'  => $variacoes,
        ]);
    }

    private function atualizar(): void {
        $produto = (new ProdutoDAO())->buscarPorId((int) $_POST['id']);
        $produto->setCategoriaId((int) $_POST['categoria_id']);
        $produto->setNome($_POST['nome']);
        $produto->setDescricao($_POST['descricao']);

        (new ProdutoDAO())->atualizar($produto);

        $_SESSION['flash'] = 'Produto atualizado com sucesso.';
        $this->redirect('?page=produtos');
    }

    private function deletar(): void {
        (new ProdutoDAO())->deletar((int) $_GET['id']);

        $_SESSION['flash'] = 'Produto removido.';
        $this->redirect('?page=produtos');
    }

    private function novaVariacao(): void {
        $produto = (new ProdutoDAO())->buscarPorId((int) $_GET['id']);
        $this->render('produtos/form_variacao', ['produto' => $produto]);
    }

    private function salvarVariacao(): void {
        $variacao = new ProdutoVariacao();
        $variacao->setProdutoId((int) $_POST['produto_id']);
        $variacao->setNome($_POST['nome']);
        $variacao->setPreco((float) $_POST['preco']);
        $variacao->setEstoque((int) $_POST['estoque']);

        (new ProdutoVariacaoDAO())->salvar($variacao);

        $_SESSION['flash'] = 'Variação cadastrada com sucesso.';
        $this->redirect('?page=produtos&acao=editar&id=' . $_POST['produto_id']);
    }

    private function deletarVariacao(): void {
        $variacaoDAO = new ProdutoVariacaoDAO();
        $variacao    = $variacaoDAO->buscarPorId((int) $_GET['id']);
        $produtoId   = $variacao->getProdutoId();

        $variacaoDAO->deletar($variacao->getId());

        $_SESSION['flash'] = 'Variação removida.';
        $this->redirect('?page=produtos&acao=editar&id=' . $produtoId);
    }
}
