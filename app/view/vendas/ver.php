<?php ob_start();
$status  = $venda->getStatus();
$pendente = $status === StatusVenda::pendente;

// Monta mapa produto_id → variacoes para o JS filtrar
$mapaVariacoes = [];
foreach ($variacoes as $v) {
    $mapaVariacoes[$v->getProdutoId()][] = [
        'id'      => $v->getId(),
        'nome'    => $v->getNome() ?: '(padrão)',
        'preco'   => $v->getPreco(),
        'estoque' => $v->getEstoque(),
    ];
}
?>
<div class="topo-lista">
    <h2>Venda #<?= $venda->getId() ?></h2>
    <a href="?page=vendas" class="btn btn-sec">← Voltar</a>
</div>

<div class="card">
    <dl class="info-grid">
        <dt>Data</dt>
        <dd><?= $venda->getData()->format('d/m/Y H:i') ?></dd>
        <dt>Status</dt>
        <dd><?= $status->value ?></dd>
        <dt>Cliente (ID)</dt>
        <dd><?= $venda->getClienteId() ?? '—' ?></dd>
        <dt>Atendente (ID)</dt>
        <dd><?= $venda->getAtendenteId() ?></dd>
        <dt>Total</dt>
        <dd>R$ <?= number_format($venda->getTotal(), 2, ',', '.') ?></dd>
    </dl>
</div>

<h3 style="margin-bottom:10px;">Itens da Venda</h3>
<table>
    <tr>
        <th>Tipo</th>
        <th>Descrição</th>
        <th>Qtd</th>
        <th>Preço Unit.</th>
        <th>Subtotal</th>
        <?php if ($pendente): ?><th></th><?php endif; ?>
    </tr>
    <?php foreach ($itens as $item): ?>
    <tr>
        <?php if ($item->isProduto()): ?>
            <td>Produto</td>
            <td>Variação #<?= $item->getProdutoVariacaoId() ?></td>
        <?php elseif ($item->isServicoDireto()): ?>
            <td>Serviço</td>
            <td>Serviço #<?= $item->getServicoId() ?> — <?= $item->getPorte()?->value ?? '' ?></td>
        <?php else: ?>
            <td>Serviço</td>
            <td>AgendServiço #<?= $item->getAgendamentoServicoId() ?></td>
        <?php endif; ?>
        <td><?= $item->getQuantidade() ?></td>
        <td>R$ <?= number_format($item->getPrecoUnitario(), 2, ',', '.') ?></td>
        <td>R$ <?= number_format($item->getSubtotal(), 2, ',', '.') ?></td>
        <?php if ($pendente): ?>
        <td>
            <a href="?page=vendas&acao=removerItem&item_id=<?= $item->getId() ?>" class="btn btn-perigo"
               onclick="return confirm('Remover item?')">Remover</a>
        </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($itens)): ?>
    <tr><td colspan="<?= $pendente ? 6 : 5 ?>" style="text-align:center; color:#888;">Nenhum item adicionado.</td></tr>
    <?php endif; ?>
</table>

<?php if ($pendente): ?>
<div class="card" style="margin-top:20px;">
    <h4 style="margin-bottom:14px;">Adicionar Item</h4>

    <div style="margin-bottom:12px;">
        <label style="font-weight:bold; font-size:13px; display:inline; margin-right:16px;">
            <input type="radio" name="tipo_item" value="produto" id="rb-produto" onchange="trocarTipo()" checked>
            Produto
        </label>
        <label style="font-weight:bold; font-size:13px; display:inline;">
            <input type="radio" name="tipo_item" value="servico" id="rb-servico" onchange="trocarTipo()">
            Serviço
        </label>
    </div>

    <!-- Form Produto -->
    <form id="form-produto" method="post" action="?page=vendas">
        <input type="hidden" name="acao" value="adicionarItem">
        <input type="hidden" name="venda_id" value="<?= $venda->getId() ?>">
        <input type="hidden" name="tipo" value="produto">
        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div>
                <label>Produto</label>
                <select id="sel-produto" onchange="atualizarVariacoes()" style="width:220px;">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p->getId() ?>"><?= htmlspecialchars($p->getNome()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Variação</label>
                <select name="produto_variacao_id" id="sel-variacao" required style="width:220px;">
                    <option value="">-- Selecione o produto primeiro --</option>
                </select>
            </div>
            <div>
                <label>Quantidade</label>
                <input type="number" name="quantidade" min="1" value="1" required style="width:80px;">
            </div>
            <button type="submit">Adicionar</button>
        </div>
    </form>

    <!-- Form Serviço -->
    <form id="form-servico" method="post" action="?page=vendas" style="display:none;">
        <input type="hidden" name="acao" value="adicionarItem">
        <input type="hidden" name="venda_id" value="<?= $venda->getId() ?>">
        <input type="hidden" name="tipo" value="servico">
        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <div>
                <label>Serviço</label>
                <select name="servico_id" required style="width:220px;">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($servicos as $s): ?>
                        <option value="<?= $s->getId() ?>"><?= htmlspecialchars($s->getNome()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Porte do Animal</label>
                <select name="porte" required style="width:140px;">
                    <?php foreach (Porte::cases() as $p): ?>
                        <option value="<?= $p->value ?>"><?= ucfirst($p->value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Adicionar</button>
        </div>
    </form>
</div>

<div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;">
    <form method="post" action="?page=vendas">
        <input type="hidden" name="acao" value="finalizar">
        <input type="hidden" name="venda_id" value="<?= $venda->getId() ?>">
        <div style="display:flex; gap:10px; align-items:flex-end;">
            <div>
                <label style="display:block; font-size:13px; font-weight:bold; margin-bottom:4px;">Forma de pagamento</label>
                <select name="forma_pagamento" required style="padding:6px 10px; border:1px solid #ccc; border-radius:3px;">
                    <option value="dinheiro">Dinheiro</option>
                    <option value="cartao_credito">Cartão de Crédito</option>
                    <option value="cartao_debito">Cartão de Débito</option>
                    <option value="pix">PIX</option>
                </select>
            </div>
            <button type="submit" onclick="return confirm('Finalizar esta venda?')">Finalizar Venda</button>
        </div>
    </form>
    <a href="?page=vendas&acao=cancelar&id=<?= $venda->getId() ?>" class="btn btn-perigo"
       style="align-self:flex-end;"
       onclick="return confirm('Cancelar esta venda?')">Cancelar Venda</a>
</div>

<script>
const variacoesPorProduto = <?= json_encode($mapaVariacoes) ?>;

function trocarTipo() {
    const isProduto = document.getElementById('rb-produto').checked;
    document.getElementById('form-produto').style.display = isProduto ? '' : 'none';
    document.getElementById('form-servico').style.display = isProduto ? 'none' : '';
}

function atualizarVariacoes() {
    const produtoId = document.getElementById('sel-produto').value;
    const sel = document.getElementById('sel-variacao');
    sel.innerHTML = '<option value="">-- Selecione --</option>';
    if (!produtoId || !variacoesPorProduto[produtoId]) return;
    variacoesPorProduto[produtoId].forEach(function(v) {
        const opt = document.createElement('option');
        opt.value = v.id;
        opt.text = v.nome + ' — R$ ' + v.preco.toFixed(2).replace('.', ',') + ' (estoque: ' + v.estoque + ')';
        sel.appendChild(opt);
    });
}
</script>
<?php endif; ?>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
