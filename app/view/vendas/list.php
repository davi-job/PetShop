<?php ob_start(); ?>
<div class="topo-lista">
    <h2>Vendas</h2>
    <a href="?page=vendas&acao=abrir" class="btn">+ Abrir Venda</a>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Data</th>
        <th>Cliente (ID)</th>
        <th>Atendente (ID)</th>
        <th>Status</th>
        <th>Total</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($vendas as $venda): ?>
    <tr>
        <td><?= $venda->getId() ?></td>
        <td><?= $venda->getData()->format('d/m/Y H:i') ?></td>
        <td><?= $venda->getClienteId() ?? '—' ?></td>
        <td><?= $venda->getAtendenteId() ?></td>
        <td><?= $venda->getStatus()->value ?></td>
        <td>R$ <?= number_format($venda->getTotal(), 2, ',', '.') ?></td>
        <td class="acoes">
            <a href="?page=vendas&acao=ver&id=<?= $venda->getId() ?>" class="btn btn-sec">Ver</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($vendas)): ?>
    <tr><td colspan="7" style="text-align:center; color:#888;">Nenhuma venda registrada.</td></tr>
    <?php endif; ?>
</table>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
