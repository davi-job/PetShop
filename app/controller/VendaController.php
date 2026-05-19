<?php

class VendaController extends Controller {

    public function handle(): void {
        $this->requireAuth();
        $acao = $_GET['acao'] ?? $_POST['acao'] ?? 'listar';

        match ($acao) {
            'abrir'          => $this->abrir(),
            'salvar'         => $this->salvar(),
            'ver'            => $this->ver(),
            'adicionarItem'  => $this->adicionarItem(),
            'removerItem'    => $this->removerItem(),
            'finalizar'      => $this->finalizar(),
            'cancelar'       => $this->cancelar(),
            default          => $this->listar(),
        };
    }

    private function listar(): void {
        $vendas = (new VendaDAO())->listar();
        $this->render('vendas/list', ['vendas' => $vendas]);
    }

    private function abrir(): void {
        // R4: só atendente e gerente podem abrir vendas
        $this->requireRole('atendente', 'gerente');

        $clientes = (new ClienteDAO())->listar();
        $this->render('vendas/form_abrir', ['clientes' => $clientes]);
    }

    private function salvar(): void {
        $this->requireRole('atendente', 'gerente');

        $venda = new Vendas();
        $venda->setAtendenteId(Auth::getUsuario()->getId());
        $venda->setClienteId(!empty($_POST['cliente_id']) ? (int) $_POST['cliente_id'] : null);
        $venda->setData(new DateTime());
        $venda->setTotal(0);

        $dao = new VendaDAO();
        $id  = $dao->salvar($venda);

        $this->redirect('?page=vendas&acao=ver&id=' . $id);
    }

    private function ver(): void {
        $venda    = (new VendaDAO())->buscarPorId((int) $_GET['id']);
        $itens    = (new ItemVendaDAO())->listarPorVenda($venda->getId());
        $variacoes = (new ProdutoVariacaoDAO())->listar();
        $produtos  = (new ProdutoDAO())->listar();
        $servicos  = (new ServicoDAO())->listar();
        $this->render('vendas/ver', [
            'venda'     => $venda,
            'itens'     => $itens,
            'variacoes' => $variacoes,
            'produtos'  => $produtos,
            'servicos'  => $servicos,
        ]);
    }

    private function adicionarItem(): void {
        $vendaId = (int) $_POST['venda_id'];
        $tipo    = $_POST['tipo'] ?? '';

        if ($tipo === 'servico') {
            $servicoId = (int) $_POST['servico_id'];
            $porte     = Porte::from($_POST['porte']);

            $preco = (new ServicoPrecoDAO())->buscarPorPorte($servicoId, $porte->value);
            $precoUnitario = $preco ? $preco->getPreco() : 0.0;

            $item = new ItemVenda();
            $item->setVendaId($vendaId);
            $item->setServicoId($servicoId);
            $item->setPorte($porte);
            $item->setQuantidade(1);
            $item->setPrecoUnitario($precoUnitario);
            $item->setSubtotal($precoUnitario);

            (new ItemVendaDAO())->salvar($item);
            $_SESSION['flash'] = 'Serviço adicionado.';

        } elseif ($tipo === 'produto') {
            $produtoVariacaoId = (int) $_POST['produto_variacao_id'];
            $quantidade        = max(1, (int) $_POST['quantidade']);

            $variacao = (new ProdutoVariacaoDAO())->buscarPorId($produtoVariacaoId);

            if (!$variacao->temEstoque($quantidade)) {
                $_SESSION['flash'] = 'Estoque insuficiente.';
                $this->redirect('?page=vendas&acao=ver&id=' . $vendaId);
            }

            $item = new ItemVenda();
            $item->setVendaId($vendaId);
            $item->setProdutoVariacaoId($produtoVariacaoId);
            $item->setQuantidade($quantidade);
            $item->setPrecoUnitario($variacao->getPreco());
            $item->setSubtotal($variacao->getPreco() * $quantidade);

            (new ItemVendaDAO())->salvar($item);
            $_SESSION['flash'] = 'Produto adicionado.';

        } else {
            $_SESSION['flash'] = 'Selecione o tipo do item.';
        }

        $this->redirect('?page=vendas&acao=ver&id=' . $vendaId);
    }

    private function removerItem(): void {
        $itemDAO = new ItemVendaDAO();
        $item    = $itemDAO->buscarPorId((int) $_GET['item_id']);
        $vendaId = $item->getVendaId();

        $itemDAO->deletar($item->getId());

        $_SESSION['flash'] = 'Item removido.';
        $this->redirect('?page=vendas&acao=ver&id=' . $vendaId);
    }

    private function finalizar(): void {
        // R5: só caixa e gerente podem finalizar
        $this->requireRole('caixa', 'gerente');

        $vendaDAO = new VendaDAO();
        $venda    = $vendaDAO->buscarPorId((int) $_POST['venda_id']);
        $itens    = (new ItemVendaDAO())->listarPorVenda($venda->getId());

        // Decrementa estoque dos produtos (R7)
        $variacaoDAO = new ProdutoVariacaoDAO();
        foreach ($itens as $item) {
            if ($item->isProduto()) {
                $variacao = $variacaoDAO->buscarPorId($item->getProdutoVariacaoId());
                $variacao->decrementarEstoque($item->getQuantidade());
                $variacaoDAO->atualizar($variacao);
            }
        }

        $venda->setCaixaId(Auth::getUsuario()->getId());
        $venda->setFormaPagamento($_POST['forma_pagamento']);
        $venda->setTotal($venda->calcularTotal($itens));
        $venda->finalizar();
        $vendaDAO->atualizar($venda);

        $_SESSION['flash'] = 'Venda finalizada com sucesso.';
        $this->redirect('?page=vendas');
    }

    private function cancelar(): void {
        $vendaDAO = new VendaDAO();
        $venda    = $vendaDAO->buscarPorId((int) $_GET['id']);
        $venda->cancelar();
        $vendaDAO->atualizar($venda);

        $_SESSION['flash'] = 'Venda cancelada.';
        $this->redirect('?page=vendas');
    }
}
