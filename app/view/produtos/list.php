<?php ob_start(); ?>
<div class="topo-lista">
    <h2>Produtos</h2>
    <a href="?page=produtos&acao=novo" class="btn">+ Novo Produto</a>
</div>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($produtos as $produto): ?>
    <tr>
        <td><?= $produto->getId() ?></td>
        <td><?= htmlspecialchars($produto->getNome()) ?></td>
        <td><?= htmlspecialchars($produto->getDescricao()) ?></td>
        <td class="acoes">
            <a href="?page=produtos&acao=editar&id=<?= $produto->getId() ?>" class="btn btn-sec">Editar / Variações</a>
            <a href="?page=produtos&acao=deletar&id=<?= $produto->getId() ?>" class="btn btn-perigo"
               onclick="return confirm('Excluir produto?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($produtos)): ?>
    <tr><td colspan="4" style="text-align:center; color:#888;">Nenhum produto cadastrado.</td></tr>
    <?php endif; ?>
</table>
<?php
$conteudo = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
